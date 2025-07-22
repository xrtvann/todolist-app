<?php
session_start();
require_once '../config/auth.php';

if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    $result = read("SELECT id, username, full_name FROM users WHERE id = '$id'");

    if (!empty($result) && $key === hash('sha256', $result[0]['username'])) {
        setSecureUserSession($result[0]['id'], $result[0]['username'], $result[0]['full_name']);
    }
}

if (validateSession()) {
    header('Location: index.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signIn'])) {
    if (signIn()) {
        exit();
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = "Please try again.";
    }
    header("Location: signin.php");
    exit();
}

$alertType = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : '';
$alertMessage = isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : '';

if (!empty($alertType)) {
    unset($_SESSION['alert_type'], $_SESSION['alert_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="bg-gray-100 font-poppins">
    <div class="w-full flex justify-center items-center min-h-screen">
        <div class="sign-in-container w-110 bg-white shadow-md flex flex-col justify-center py-2 px-6 rounded-xl"
            id="signIn">
            <div class="header pt-5 flex flex-col items-center">
                <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="" class="w-13">
                <h1 class="font-bold text-2xl">Sign In</h1>
            </div>
            <div class="content mt-10 mb-5 mx-2">
                <form action="" method="post">
                    <div
                        class="input-group flex items-center mb-4 border border-gray-300 focus-within:border-primary rounded-md p-3">
                        <div class="icon flex justify-center items-center text-gray-600 ms-1 me-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="input">
                            <input type="text" class="focus:outline-none" name="username" placeholder="Username"
                                required>
                        </div>
                    </div>
                    <div
                        class="input-group flex items-center mb-4 border border-gray-300 focus-within:border-primary rounded-md p-3">
                        <div class="icon flex justify-center items-center text-gray-600 ms-1 me-3">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="input w-full">
                            <input type="password" id="inputSignInPassword" class="focus:outline-none w-full"
                                name="password" placeholder="Password" required>
                        </div>
                        <div class="show-password">
                            <div class="icon text-gray-600 cursor-pointer" id="toggleSignInPassword">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="remember-me flex items-center my-5">
                        <input type="checkbox" id="remember-me" name="remember-me" class="w-4 h-4">
                        <label for="remember-me" class="ms-2">Remember me</label>
                    </div>
                    <div class="action-button mt-10">
                        <p>Don't have an account? <a href="signup.php" class="text-primary">Sign up</a></p>
                        <button type="submit" name="signIn"
                            class="w-full py-2 mt-1.5 font-semibold bg-primary opacity-95 hover:opacity-100 text-white rounded-md cursor-pointer">Sign
                            In</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="js/alert.js"></script>

    <script src="js/auth.js"></script>
    <script>
        <?php if (!empty($alertType) && !empty($alertMessage)): ?>
            <?php if ($alertType === 'error'): ?>
                showErrorAlert('Sign In Failed!', <?= json_encode($alertMessage) ?>);
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>

</html>