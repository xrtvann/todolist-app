<?php

require_once __DIR__ . '/../utility/databaseUtility.php';
$connection = connectDatabase();
function signIn()
{
    global $connection;

    if (empty($_POST['username']) || empty($_POST['password'])) {
        return false;
    }

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $escapedUsername = mysqli_real_escape_string($connection, $username);
    $escapedPassword = mysqli_real_escape_string($connection, $password);
    $query = "SELECT id, username, password, full_name FROM users WHERE username = '$escapedUsername'";

    $result = read($query);
    if (empty($result)) {
        return false;
    }

    $user = $result[0];



    if ($user['username'] && password_verify($escapedPassword, $user['password'])) {
        setSecureUserSession($user['id'], $user['username'], $user['full_name']);

        if (isset($_POST['remember-me'])) {
            setcookie('id', $user['id'], time() + 3600);
            setcookie('key', hash('sha256', $user['username']), time() + 3600);
        }

        header("Location: index.php");
        exit();
    }

    return false;
}

function signUp()
{
    global $connection;

    $fullName = htmlspecialchars($_POST['full_name']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirmationPassword = htmlspecialchars($_POST['confirmationPassword']);
    $id = uniqid();

    $existingUser = read("SELECT * FROM users WHERE username = '$username'");

    if (count($existingUser) > 0) {
        return false;
    }
    if ($password !== $confirmationPassword) {
        return false;
    }

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $result = insert('users', [
        'id' => $id,
        'full_name' => $fullName,
        'username' => $username,
        'password' => $hashPassword
    ]);

    if ($result) {
        return true;
    } else {
        return false;
    }
}
