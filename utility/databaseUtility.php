<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

// Load environment variables
if (!isset($_ENV['SESSION_ENCRYPT_KEY'])) {
    loadENV();
}

// Security key for session data encryption
$sessionKey = isset($_ENV['SESSION_ENCRYPT_KEY']) ? $_ENV['SESSION_ENCRYPT_KEY'] : 'default-key-change-in-production';

// Function to encrypt session data
function encryptSessionData($data)
{
    global $sessionKey;
    $key = hash('sha256', $sessionKey);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Function to decrypt session data
function decryptSessionData($encryptedData)
{
    global $sessionKey;
    $key = hash('sha256', $sessionKey);
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

// Function to set secure user session
function setSecureUserSession($userId, $username, $fullName = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['login'] = true;
    $_SESSION['user_id_hash'] = encryptSessionData($userId);
    $_SESSION['username_hash'] = encryptSessionData($username);
    if ($fullName) {
        $_SESSION['full_name_hash'] = encryptSessionData($fullName);
    }
    $_SESSION['session_token'] = hash('sha256', $userId . time()); // Additional security token
}

// Function to get current user ID from encrypted session
function getCurrentUserId()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id_hash'])) {
        return null;
    }

    return decryptSessionData($_SESSION['user_id_hash']);
}

// Function to get current username from encrypted session
function getCurrentUsername()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['username_hash'])) {
        return null;
    }

    return decryptSessionData($_SESSION['username_hash']);
}

// Function to get current full name from encrypted session
function getCurrentFullName()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['full_name_hash'])) {
        return null; // Return null if no full name is set
    }

    $fullName = decryptSessionData($_SESSION['full_name_hash']);

    // Return null if full name is empty or false (decryption failed)
    if (empty($fullName) || $fullName === false) {
        return null;
    }

    return $fullName;
}

// Function to validate session integrity
function validateSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['login']) &&
        $_SESSION['login'] &&
        isset($_SESSION['user_id_hash']) &&
        isset($_SESSION['username_hash']) &&
        isset($_SESSION['session_token']);
}

// Function to clear secure session
function clearSecureSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    unset($_SESSION['login']);
    unset($_SESSION['user_id_hash']);
    unset($_SESSION['username_hash']);
    unset($_SESSION['full_name_hash']);
    unset($_SESSION['session_token']);
    session_destroy();
}

function read($query)
{
    global $connection;

    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Function to store data in the database
function insert($table, $data)
{
    global $connection;

    $columns = implode(', ', array_keys($data));
    $values = [];

    foreach ($data as $value) {
        $values[] = "'" . mysqli_real_escape_string($connection, $value) . "'";
    }
    $values = implode(', ', $values);

    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return $result;
}

function edit($table, $data, $condition)
{
    global $connection;

    $setParts = [];
    foreach ($data as $column => $value) {
        $escapedColumn = mysqli_real_escape_string($connection, $column);
        $escapedValue = mysqli_real_escape_string($connection, $value);
        $setParts[] = "{$escapedColumn} = '{$escapedValue}'";
    }

    $set = implode(', ', $setParts);
    $query = "UPDATE {$table} SET {$set} WHERE {$condition}";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return mysqli_affected_rows($connection);
}

function delete($table, $field, $value)
{
    global $connection;

    $escapedField = mysqli_real_escape_string($connection, $field);
    $escapedValue = mysqli_real_escape_string($connection, $value);

    $query = "DELETE FROM {$table} WHERE {$escapedField} = '{$escapedValue}'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return mysqli_affected_rows($connection);
}

function pagination($table, $dataPerPage, $userIdColumn = 'user_id')
{
    $userId = getCurrentUserId();
    $whereClause = '';

    // Always filter by user_id for data isolation
    if ($userId) {
        $whereClause = " WHERE {$userIdColumn} = '{$userId}'";
    }

    $result = read("SELECT COUNT(*) as total FROM $table{$whereClause}");
    $amountOfData = $result[0]['total'];
    $amountOfPage = max(1, ceil($amountOfData / $dataPerPage)); // Ensure at least 1 page
    $currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;

    if ($currentPage < 1) {
        $currentPage = 1;
    }
    if ($currentPage > $amountOfPage) {
        $currentPage = $amountOfPage;
    }

    $start = ($currentPage * $dataPerPage) - $dataPerPage;

    return [
        'start' => $start,
        'currentPage' => $currentPage,
        'amountOfPage' => $amountOfPage,
        'dataPerPage' => $dataPerPage,
        'amountOfData' => $amountOfData
    ];
}

function previousButton($currentPage)
{
    return $currentPage > 1 ? $currentPage - 1 : 1;
}

function nextButton($currentPage, $amountOfPage)
{
    return $currentPage < $amountOfPage ? $currentPage + 1 : $amountOfPage;
}
