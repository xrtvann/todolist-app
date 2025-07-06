<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
</head>

<body class="font-poppins bg-gray-100">
    <div class="flex min-h-screen">
        <?php include('../views/templates/sidebar.php'); ?>

        <div class="flex-1 flex flex-col">
            <?php include('../views/templates/navbar.php'); ?>
        </div>
    </div>


    <script src="js/app.js"></script>
</body>

</html>