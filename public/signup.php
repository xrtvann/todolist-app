<?php
require_once '../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUp'])) {
    if (signUp()) {
        header("Location: signin.php");
        exit();
    } else {
        $showError = true;
        $errorMessage = "Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>

<body class="bg-gray-100 font-poppins">
    <div class="w-full flex justify-center items-center min-h-screen">
        <div class="sign-up-container w-110 bg-white shadow-md flex flex-col justify-center py-2 px-6 rounded-xl"
            id="signUp">
            <div class="header pt-5 flex flex-col items-center">
                <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="" class="w-13">
                <h1 class="font-bold text-2xl">Sign Up</h1>
            </div>
            <div class="content mt-10 mb-5 mx-2">
                <form action="" method="post">
                    <div
                        class="input-group flex items-center mb-4 border border-gray-300 focus-within:border-primary rounded-md p-3">
                        <div class="icon flex justify-center items-center text-gray-600 ms-1 me-2">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="input">
                            <input type="text" class="focus:outline-none" name="full_name" placeholder="Full Name"
                                required>
                        </div>
                    </div>
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
                            <input type="password" id="inputSignUpPassword" class="focus:outline-none w-full"
                                name="password" placeholder="Password" required>
                        </div>
                        <div class="show-password">
                            <div class="icon text-gray-600 cursor-pointer" id="toggleSignUpPassword">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div
                        class="input-group flex items-center mb-4 border border-gray-300 focus-within:border-primary rounded-md p-3">
                        <div class="icon flex justify-center items-center text-gray-600 ms-1 me-3">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="input w-full">
                            <input type="password" id="inputConfirmationPassword" class="focus:outline-none w-full"
                                name="confirmationPassword" placeholder="Confirm Password" required>
                        </div>
                        <div class="show-password">
                            <div class="icon text-gray-600 cursor-pointer" id="toggleConfirmationPassword">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="action-button mt-10">
                        <p>Already have an account? <a href="signin.php" class="text-primary">Sign In</a></p>
                        <button type="submit" name="signUp"
                            class="w-full py-2 mt-1.5 font-semibold bg-primary opacity-95 hover:opacity-100 text-white rounded-md cursor-pointer">Sign
                            Up</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        <?php if ($showError): ?>
            Swal.fire({
                icon: 'error',
                title: 'Sign Up Failed!',
                text: '<?= htmlspecialchars($errorMessage, ENT_QUOTES) ?>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>
    </script>
    <script src="js/auth.js"></script>
</body>

</html>