<?php

session_start();
require_once "../../config/database.php";
require_once "../../utility/databaseUtility.php";

// Validate session before processing request
if (!validateSession()) {
    http_response_code(401);
    echo '<tr><td colspan="4" class="text-center py-4 text-red-500">Session expired. Please login again.</td></tr>';
    exit;
}

// Get search parameter
$searchInput = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$page = isset($_GET['page']) ? $_GET['page'] : '';
$currentPage = isset($_GET['p']) ? (int) $_GET['p'] : 1;

$dataPerPage = 10; // Default items per page
// Handle category search
if ($page === 'category') {
    require_once "../../controller/categoryController.php";
    if (empty($searchInput)) {
        $pagination = pagination('category', $dataPerPage);
        $categories = show($dataPerPage, $pagination['start']);
    } else {
        // Search categories
        $result = searchCategories($searchInput, $dataPerPage, $currentPage);
        $categories = $result['categories'];
    }

    // Generate table rows - COPIED FROM views/pages/category.php
    if (empty($categories)) {
        ?>
        <tr>
            <td colspan="4" class="text-center py-12 text-gray-500">
                <div class="flex flex-col items-center">
                    <?php if (!empty($searchInput)): ?>
                        <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">No categories found</p>
                        <p class="text-sm">No results for "<?= htmlspecialchars($searchInput) ?>"</p>
                    <?php else: ?>
                        <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">No categories found</p>
                        <p class="text-sm">Start by creating your first category</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php
    } else {
        // COPIED: Pagination calculation dari category.php
        $startNumber = 1; // Untuk search results mulai dari 1
        $i = $startNumber;

        foreach ($categories as $category): ?>
            <!-- COPIED: Table row dari views/pages/category.php -->
            <tr class="table-row border-b border-slate-200 hover:bg-gray-50 transition-colors duration-200">
                <?php
                $createdAt = new DateTime($category['created_at']);
                $createdAtFormatted = $createdAt->format('d M Y H:i');
                ?>
                <td class="table-cell py-2 px-4"><?php echo $i++; ?></td>
                <td class="table-cell py-2 px-4">
                    <?php
                    // Highlight search term
                    $categoryName = htmlspecialchars($category['name']);
                    if (!empty($searchInput)) {
                        $highlighted = str_ireplace(
                            $searchInput,
                            '<mark class="bg-yellow-200 px-1 rounded">' . htmlspecialchars($searchInput) . '</mark>',
                            $categoryName
                        );
                        echo $highlighted;
                    } else {
                        echo $categoryName;
                    }
                    ?>
                </td>
                <td class="table-cell py-2 px-4"><?php echo $createdAtFormatted; ?></td>
                <td class="table-cell py-2 px-4">
                    <!-- COPIED: Action buttons dari views/pages/category.php -->
                    <div class="action-button flex gap-3">
                        <button type="button"
                            onclick="showEditModal('category', {id: '<?= htmlspecialchars($category['id'], ENT_QUOTES); ?>', name: '<?= htmlspecialchars($category['name'], ENT_QUOTES); ?>'})"
                            class="flex justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                            onclick="showConfirmationDelete('category', '<?= htmlspecialchars($category['id'], ENT_QUOTES) ?>', '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>')"
                            class="flex justify-center items-center px-2 py-2 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach;

        // Show search results info
        if (!empty($searchInput)) {
            $resultCount = count($categories);
            ?>
            <tr class="bg-blue-50">
                <td colspan="4" class="text-center py-2 text-sm text-blue-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Found <?= $resultCount ?> result<?= $resultCount != 1 ? 's' : '' ?> for "<?= htmlspecialchars($searchInput) ?>"
                </td>
            </tr>
            <?php
        }
    }
}

if ($page === 'task') {
    require_once "../../controller/taskController.php";
    if (empty($searchInput)) {
        $pagination = pagination('task', $dataPerPage);
        $tasks = show($dataPerPage, $pagination['start']);
    } else {
        // Search tasks
        $result = searchTask($searchInput, $dataPerPage, $currentPage);
        $tasks = $result['tasks'];
    }

    // Generate table rows - COPIED FROM views/pages/task.php
    if (empty($tasks)) {
        ?>
        <tr>
            <td colspan="6" class="text-center py-12 text-gray-500">
                <div class="flex flex-col items-center">
                    <?php if (!empty($searchInput)): ?>
                        <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">No tasks found</p>
                        <p class="text-sm">No results for "<?= htmlspecialchars($searchInput) ?>"</p>
                    <?php else: ?>
                        <i class="fas fa-tasks text-4xl mb-3 text-gray-300"></i>
                        <p class="text-lg font-medium">No tasks found</p>
                        <p class="text-sm">Start by creating your first task</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php
    } else {
        // COPIED: Pagination calculation dari task.php
        $startNumber = 1; // Untuk search results mulai dari 1
        $i = $startNumber;

        foreach ($tasks as $task): ?>
            <!-- COPIED: Table row dari views/pages/task.php -->
            <tr class="table-row border-b border-slate-200 hover:bg-gray-50 transition-colors duration-200">
                <?php
                $createdAt = new DateTime($category['created_at']);
                $createdAtFormatted = $createdAt->format('d M Y H:i');
                ?>
                <td class="table-cell py-2 px-4"><?php echo $i++; ?></td>
                <td class="table-cell py-2 px-4">
                    <?php
                    // Highlight search term
                    $taskName = htmlspecialchars($task['name']);
                    if (!empty($searchInput)) {
                        $highlighted = str_ireplace(
                            $searchInput,
                            '<mark class="bg-yellow-200 px-1 rounded">' . htmlspecialchars($searchInput) . '</mark>',
                            $taskName
                        );
                        echo $highlighted;
                    } else {
                        echo $taskName;
                    }
                    ?>
                </td>
                <?php if ($task['status'] === 'pending'): ?>
                    <td class="table-cell py-2 px-4">
                        <p class="flex bg-orange-100 px-2 rounded-full justify-center w-25 text-orange-600 font-semibold">
                            <?php echo $task['status']; ?>
                        </p>
                    </td>
                <?php elseif ($task['status'] === 'done'): ?>
                    <td class="table-cell py-2 px-4">
                        <p class="flex bg-green-100 px-2 rounded-full justify-center w-25 text-green-600 font-semibold">
                            <?php echo $task['status']; ?>
                        </p>
                    </td>
                <?php endif; ?>
                <td class="table-cell py-2 px-4">
                    <?php
                    // Highlight search term
                    $taskCategory = htmlspecialchars($task['category_name']);
                    if (!empty($searchInput)) {
                        $highlighted = str_ireplace(
                            $searchInput,
                            '<mark class="bg-yellow-200 px-1 rounded">' . htmlspecialchars($searchInput) . '</mark>',
                            $taskCategory
                        );
                        echo $highlighted;
                    } else {
                        echo $taskCategory;
                    }
                    ?>
                </td>
                <td class="table-cell py-2 px-4"><?php echo $createdAtFormatted; ?></td>
                <td class="table-cell py-2 px-4">
                    <div class="action-button flex gap-3">
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="doneTaskID" value="<?= htmlspecialchars($task['id']) ?>">
                            <?php if ($task['status'] == 'done'): ?>
                                <button type="submit" name="markAsDone" disabled
                                    class="flex cursor-not-allowed justify-center items-center px-2 py-2 rounded text-white border border-green-500 bg-green-500"
                                    title="Mark as Done">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php else: ?>
                                <button type="submit" name="markAsDone"
                                    class="flex cursor-pointer justify-center items-center px-2 py-2 rounded text-green-500 border border-green-500 hover:bg-green-500 hover:text-white transition-colors duration-200"
                                    title="Mark as Done">
                                    <i class="fas fa-check"></i>
                                </button>
                            <?php endif; ?>
                        </form>
                        <button type="button"
                            onclick="showEditModal('task', {id: '<?= htmlspecialchars($task['id']) ?>', name: '<?= htmlspecialchars($task['name']) ?>'})"
                            class="flex cursor-pointer justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                            onclick="showConfirmationDelete('task', '<?= htmlspecialchars($task['id']) ?>', '<?= htmlspecialchars($task['name']) ?>')"
                            class="flex cursor-pointer justify-center items-center px-2 py-2 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach;

        // Show search results info
        if (!empty($searchInput)) {
            $resultCount = count($tasks);
            ?>
            <tr class="bg-blue-50">
                <td colspan="6" class="text-center py-2 text-sm text-blue-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Found <?= $resultCount ?> result<?= $resultCount != 1 ? 's' : '' ?> for "<?= htmlspecialchars($searchInput) ?>"
                </td>
            </tr>
            <?php
        }
    }
}
?>