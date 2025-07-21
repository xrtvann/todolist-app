<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
</head>

<body class="bg-gray-100 font-poppins">
    <div class="w-full flex justify-center items-center min-h-screen">
        <div class="sign-in-container w-110 bg-white shadow-md flex flex-col justify-center py-2 px-6 rounded-xl">
            <div class="header pt-5 flex flex-col items-center">
                <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="" class="w-13">
                <h1 class="font-bold text-2xl">Sign In</h1>
            </div>
            <div class="content my-10 mx-2">
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
                        <div class="input">
                            <input type="password" id="inputPassword" class="focus:outline-none" name="password" placeholder="Password"
                                required>
                        </div>
                        <div class="show-password w-full flex justify-end">
                            <div class="icon text-gray-600 cursor-pointer" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    <div class="remember-me flex items-center my-5">
                        <input type="checkbox" id="remember-me" name="remember-me" class="w-4 h-4">
                        <label for="remember-me" class="ms-2">Remember me</label>
                    </div>
                </form>
            </div>
            <div class="action-button mb-2 mx-2">
                <p>Don't have an account? <a href="signup.php" class="text-primary">Sign up</a></p>
                <button
                    class="w-full py-2 mt-1.5 font-semibold bg-primary opacity-95 hover:opacity-100 text-white rounded-md cursor-pointer">Sign
                    In</button>
            </div>
        </div>
    </div>
    <script src="js/auth.js"></script>
</body>

</html>