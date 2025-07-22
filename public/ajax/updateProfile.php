<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once '../../utility/databaseUtility.php';
require_once '../../controller/settingsController.php';
require_once '../../config/database.php';

// Initialize database connection
$connection = connectDatabase();

// Set JSON header
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Validate session
if (!validateSession()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get current user ID
$userId = getCurrentUserId();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Get and validate input data
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username is required']);
    exit;
}

// Validate username format (alphanumeric and underscore only)
if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Username can only contain letters, numbers, and underscores']);
    exit;
}

// Update the profile using existing controller function
$result = updateProfile();

if ($result > 0) {
    // Get updated user data for navbar
    $displayName = $fullName ? $fullName : $username;

    // Generate initials (first + second word)
    $initials = '';
    if ($displayName) {
        $nameParts = explode(' ', $displayName);
        $initials = strtoupper(substr($nameParts[0], 0, 1));
        if (count($nameParts) > 1) {
            $initials .= strtoupper(substr($nameParts[1], 0, 1));
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully',
        'data' => [
            'username' => $username,
            'full_name' => $fullName,
            'display_name' => $displayName,
            'initials' => $initials
        ]
    ]);
} elseif ($result === -1) {
    echo json_encode(['success' => false, 'message' => 'Username is already taken']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}
?>