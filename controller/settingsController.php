<?php
// Import database utility
require_once '../utility/databaseUtility.php';

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connection = connectDatabase();

/**
 * Get current user settings/profile information
 */
function getUserSettings()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return null;
    }

    $query = "SELECT id, username, full_name, created_at FROM users WHERE id = '$userId'";
    $result = read($query);

    return !empty($result) ? $result[0] : null;
}

/**
 * Update user profile information
 */
function updateProfile()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    // Get and sanitize input data
    $fullName = isset($_POST['full_name']) ? mysqli_real_escape_string($connection, trim($_POST['full_name'])) : '';
    $username = isset($_POST['username']) ? mysqli_real_escape_string($connection, trim($_POST['username'])) : '';

    // Validate inputs
    if (empty($username)) {
        return 0;
    }

    // Check if username is already taken by another user
    $checkQuery = "SELECT id FROM users WHERE username = '$username' AND id != '$userId'";
    $checkResult = read($checkQuery);

    if (!empty($checkResult)) {
        return -1; // Username already exists
    }

    // Update user data
    $updateData = [
        'username' => $username,
        'full_name' => $fullName
    ];

    $result = edit('users', $updateData, "id = '$userId'");

    if ($result > 0) {
        // Update session variables with new encrypted values
        $_SESSION['username_hash'] = encryptSessionData($username);
        if (!empty($fullName)) {
            $_SESSION['full_name_hash'] = encryptSessionData($fullName);
        } else {
            // Remove full_name_hash if full name is empty
            unset($_SESSION['full_name_hash']);
        }
    }

    return $result;
}

/**
 * Change user password
 */
function changePassword()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    // Get input data
    $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        return 0;
    }

    if ($newPassword !== $confirmPassword) {
        return -1; // Passwords don't match
    }

    if (strlen($newPassword) < 6) {
        return -2; // Password too short
    }

    // Get current user data
    $userQuery = "SELECT password FROM users WHERE id = '$userId'";
    $userResult = read($userQuery);

    if (empty($userResult)) {
        return 0;
    }

    // Verify current password
    if (!password_verify($currentPassword, $userResult[0]['password'])) {
        return -3; // Current password incorrect
    }

    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $hashedPassword = mysqli_real_escape_string($connection, $hashedPassword);

    // Update password
    $updateData = ['password' => $hashedPassword];
    return edit('users', $updateData, "id = '$userId'");
}

/**
 * Get user's data statistics for export
 */
function getUserDataStats()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [
            'totalCategories' => 0,
            'totalTasks' => 0,
            'completedTasks' => 0,
            'pendingTasks' => 0
        ];
    }

    // Get categories count
    $categoriesQuery = "SELECT COUNT(*) as total FROM category WHERE user_id = '$userId'";
    $categoriesResult = read($categoriesQuery);
    $totalCategories = $categoriesResult[0]['total'] ?? 0;

    // Get tasks count
    $tasksQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId'";
    $tasksResult = read($tasksQuery);
    $totalTasks = $tasksResult[0]['total'] ?? 0;

    // Get completed tasks
    $completedQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND status = 'done'";
    $completedResult = read($completedQuery);
    $completedTasks = $completedResult[0]['total'] ?? 0;

    $pendingTasks = $totalTasks - $completedTasks;

    return [
        'totalCategories' => $totalCategories,
        'totalTasks' => $totalTasks,
        'completedTasks' => $completedTasks,
        'pendingTasks' => $pendingTasks
    ];
}

/**
 * Export all user data as JSON
 */
function exportUserData()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return false;
    }

    // Get user info
    $userInfo = getUserSettings();

    // Get categories
    $categoriesQuery = "SELECT id, name, created_at FROM category WHERE user_id = '$userId' ORDER BY created_at DESC";
    $categories = read($categoriesQuery);

    // Get tasks with category names
    $tasksQuery = "SELECT t.id, t.name, t.status, t.created_at, c.name as category_name 
                   FROM task t 
                   LEFT JOIN category c ON t.category_id = c.id 
                   WHERE t.user_id = '$userId' 
                   ORDER BY t.created_at DESC";
    $tasks = read($tasksQuery);

    // Prepare export data
    $exportData = [
        'export_info' => [
            'exported_at' => date('Y-m-d H:i:s'),
            'user' => $userInfo['username'],
            'full_name' => $userInfo['full_name']
        ],
        'statistics' => getUserDataStats(),
        'categories' => $categories,
        'tasks' => $tasks
    ];

    // Set headers for JSON download
    $filename = 'TodoList_Data_Export_' . date('Y-m-d_H-i-s') . '.json';

    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    echo json_encode($exportData, JSON_PRETTY_PRINT);
    return true;
}

/**
 * Clear all completed tasks
 */
function clearCompletedTasks()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    $query = "DELETE FROM task WHERE user_id = '$userId' AND status = 'done'";
    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}

/**
 * Delete all user categories (and their tasks)
 */
function deleteAllCategories()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    // First delete all tasks
    $deleteTasksQuery = "DELETE FROM task WHERE user_id = '$userId'";
    mysqli_query($connection, $deleteTasksQuery);

    // Then delete all categories
    $deleteCategoriesQuery = "DELETE FROM category WHERE user_id = '$userId'";
    mysqli_query($connection, $deleteCategoriesQuery);

    return mysqli_affected_rows($connection);
}

/**
 * Delete all user tasks only
 */
function deleteAllTasks()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    $query = "DELETE FROM task WHERE user_id = '$userId'";
    mysqli_query($connection, $query);

    return mysqli_affected_rows($connection);
}

/**
 * Delete user account completely
 */
function deleteUserAccount()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return 0;
    }

    // Delete user's tasks
    $deleteTasksQuery = "DELETE FROM task WHERE user_id = '$userId'";
    mysqli_query($connection, $deleteTasksQuery);

    // Delete user's categories
    $deleteCategoriesQuery = "DELETE FROM category WHERE user_id = '$userId'";
    mysqli_query($connection, $deleteCategoriesQuery);

    // Delete user account
    $deleteUserQuery = "DELETE FROM users WHERE id = '$userId'";
    mysqli_query($connection, $deleteUserQuery);

    return mysqli_affected_rows($connection);
}
