<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once '../utility/databaseUtility.php';

if (!validateSession()) {
    header("Location: signin.php");
    exit();
}
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

    if ($page === 'task' && isset($_POST['saveChangesTask'])) {
        require_once '../controller/taskController.php';
        $result = update();

        if ($result > 0) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Task has been successfully updated!';
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Failed to update task!';
        }

        // Redirect sebelum ada HTML output
        header("Location: index.php?page=task");
        exit();
    }

    // Handle PDF export
    if ($page === 'report' && isset($_POST['export_pdf'])) {
        require_once '../controller/reportController.php';

        try {
            exportToPDF();
            exit(); // PDF export will handle the output
        } catch (Exception $e) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'PDF export failed: ' . $e->getMessage();
        }

        header("Location: index.php?page=report");
        exit();
    }

    // Handle Excel export
    if ($page === 'report' && isset($_POST['export_excel'])) {
        require_once '../controller/reportController.php';

        try {
            exportToExcel();
            exit(); // Excel export will handle the output
        } catch (Exception $e) {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = 'Excel export failed: ' . $e->getMessage();
        }

        header("Location: index.php?page=report");
        exit();
    }

    // Handle Settings form submissions
    if ($page === 'settings') {
        require_once '../controller/settingsController.php';

        // Change Password
        if (isset($_POST['change_password'])) {
            $result = changePassword();

            if ($result > 0) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_message'] = 'Password changed successfully!';
            } elseif ($result === -1) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'New passwords do not match!';
            } elseif ($result === -2) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Password must be at least 6 characters long!';
            } elseif ($result === -3) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Current password is incorrect!';
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Failed to change password!';
            }

            header("Location: index.php?page=settings");
            exit();
        }

        // Export User Data
        if (isset($_POST['export_data'])) {
            try {
                exportUserData();
                exit(); // Export will handle the output
            } catch (Exception $e) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Data export failed: ' . $e->getMessage();
            }

            header("Location: index.php?page=settings");
            exit();
        }

        // Clear Completed Tasks
        if (isset($_POST['clear_completed'])) {
            $result = clearCompletedTasks();

            if ($result > 0) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_message'] = "Successfully cleared $result completed tasks!";
            } else {
                $_SESSION['alert_type'] = 'info';
                $_SESSION['alert_message'] = 'No completed tasks found to clear.';
            }

            header("Location: index.php?page=settings");
            exit();
        }

        // Delete All Tasks
        if (isset($_POST['delete_all_tasks'])) {
            $result = deleteAllTasks();

            if ($result > 0) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_message'] = "Successfully deleted $result tasks!";
            } else {
                $_SESSION['alert_type'] = 'info';
                $_SESSION['alert_message'] = 'No tasks found to delete.';
            }

            header("Location: index.php?page=settings");
            exit();
        }

        // Delete All Data
        if (isset($_POST['delete_all_data'])) {
            $result = deleteAllCategories();

            if ($result > 0) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_message'] = 'All categories and tasks have been deleted successfully!';
            } else {
                $_SESSION['alert_type'] = 'info';
                $_SESSION['alert_message'] = 'No data found to delete.';
            }

            header("Location: index.php?page=settings");
            exit();
        }

        // Delete Account
        if (isset($_POST['delete_account'])) {
            $result = deleteUserAccount();

            if ($result > 0) {
                // Destroy session and redirect to sign-in page
                session_destroy();
                $_SESSION = array();
                header("Location: signin.php?message=Account deleted successfully");
                exit();
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Failed to delete account!';
            }

            header("Location: index.php?page=settings");
            exit();
        }
    }

    // Handle Profile form submissions
    if ($page === 'profile') {
        require_once '../controller/settingsController.php';

        // Update Profile
        if (isset($_POST['update_profile'])) {
            $result = updateProfile();

            if ($result > 0) {
                $_SESSION['alert_type'] = 'success';
                $_SESSION['alert_message'] = 'Profile updated successfully!';
            } elseif ($result === -1) {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Username already exists! Please choose a different username.';
            } else {
                $_SESSION['alert_type'] = 'error';
                $_SESSION['alert_message'] = 'Failed to update profile!';
            }

            header("Location: index.php?page=profile");
            exit();
        }
    }
}

// handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (isset($_GET['page']) && $_GET['page'] === 'category' && isset($_GET['id'])) {
        require_once '../controller/categoryController.php';
        $categoryID = htmlspecialchars($_GET['id']);
        $result = destroy($categoryID);
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


    if (isset($_GET['page']) && $_GET['page'] === 'task' && isset($_GET['id'])) {
        require_once '../controller/taskController.php';
        $taskID = htmlspecialchars($_GET['id']);
        $result = destroy($taskID);
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
    'profile' => 'Profile',
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
    <script src="./js/jquery-3.7.1.min.js"></script>
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
                $allowed_pages = ['dashboard', 'task', 'category', 'report', 'settings', 'logout', 'profile'];

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
    <script src="./js/jquery.js"></script>
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