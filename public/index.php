<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
connectDatabase();
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// handle form subsmission

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($page === 'category' && isset($_POST['saveCategory'])) {

        require_once '../controller/categoryController.php'; // 
        $result = store();

        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'New category has been successfully added!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to add new category!';
        }

        // Redirect sebelum ada HTML output
        header("Location: index.php?page=category");
        exit();
    }

    if ($page === 'task' && isset($_POST['saveTask'])) {

        require_once '../controller/taskController.php'; // 
        $result = store();

        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'New task has been successfully added!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to add new task!';
        }

        // Redirect sebelum ada HTML output
        header("Location: index.php?page=task");
        exit();
    }

    if ($page === 'task' && isset($_POST['markAsDone'])) {
        require_once '../controller/taskController.php';
        $result = markDoneTask();

        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Task has been successfully marked as done!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to mark task as done!';
        }

        // Redirect sebelum ada HTML output
        header("Location: index.php?page=task");
        exit();
    }

    if ($page === 'category' && isset($_POST['saveChangesCategory'])) {
        require_once '../controller/categoryController.php';
        $result = update();

        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Category has been successfully updated!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to update category!';
        }

        // Redirect sebelum ada HTML output
        header("Location: index.php?page=category");
        exit();
    }
}

// handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (isset($_GET['categoryName'])) {
        require_once '../controller/categoryController.php';
        $categoryName = htmlspecialchars($_GET['categoryName']);
        $result = destroy($categoryName);
        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Category has been successfully deleted!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to delete category!';
        }
        // Redirect sebelum ada HTML output
        header("Location: index.php?page=category");
        exit();
    }


    if (isset($_GET['taskName'])) {
        require_once '../controller/taskController.php';
        $taskName = htmlspecialchars($_GET['taskName']);
        $result = destroy($taskName);
        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Task has been successfully deleted!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to delete task!';
        }
        // Redirect sebelum ada HTML output
        header("Location: index.php?page=task");
        exit();
    }
}


$pageTitles = [
    'dashboard' => 'Dashboard',
    'task' => 'Task',
    'category' => 'Category',
    'report' => 'Report',
    'settings' => 'Settings',
];
$title = isset($pageTitles[$page]) ? $pageTitles[$page] : 'Todolist App';

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
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/fluency/96/000000/todo-list.png">
    <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="./js/jquery.js"></script>
</head>

<body class="font-poppins bg-gray-50">
    <div class="flex min-h-screen">
        <?php include('../views/templates/sidebar.php'); ?>
        <div id="mainContent" class="flex-1 flex flex-col h-screen">
            <div class="sticky top-0 bg-white shadow">
                <?php include('../views/templates/navbar.php'); ?>
            </div>
            <div class="flex-1 overflow-auto">
                <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
                $allowed_pages = ['dashboard', 'task', 'category', 'report', 'settings', 'logout'];

                if (in_array($page, $allowed_pages)) {
                    include("../views/pages/" . $page . ".php");
                } else {
                    echo "<p>Halaman tidak ditemukan.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="./js/alert.js"></script>
    <script src="./js/app.js"></script>

    <script>
        <?php if (!empty($alertType) && !empty($alertMessage)): ?>
            <?php if ($alertType === 'success'): ?>
                showSuccessAlert('Success', <?= json_encode($alertMessage) ?>);
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>

</html>