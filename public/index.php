<!DOCTYPE html>
<html lang="en">

<?php
require_once '../config/database.php';
connectDatabase();
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pageTitles = [
    'dashboard' => 'Dashboard',
    'tasks' => 'Tasks',
    'category' => 'Category',
    'report' => 'Report',
    'settings' => 'Settings',
    'logout' => 'Logout',
];
$title = isset($pageTitles[$page]) ? $pageTitles[$page] : 'Todolist App';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
</head>

<body class="font-poppins bg-gray-50">
    <div class="flex min-h-screen">
        <?php include('../views/templates/sidebar.php'); ?>

        <div id="mainContent" class="flex-1 flex flex-col">
            <?php include('../views/templates/navbar.php'); ?>

            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
            $allowed_pages = ['dashboard', 'tasks', 'category', 'report', 'settings', 'logout'];

            if (in_array($page, $allowed_pages)) {
                include("../views/pages/" . $page . ".php");
            } else {
                echo "<p>Halaman tidak ditemukan.</p>";
            }
            ?>
        </div>
    </div>


    <script src="js/app.js"></script>
</body>

</html>