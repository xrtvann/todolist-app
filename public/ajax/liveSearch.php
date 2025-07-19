<?php

require_once "../../config/database.php";
require_once "../../utility/databaseUtility.php";
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
        $categories = show($pagination['start'], $dataPerPage);
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
                    <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                    <p class="text-lg font-medium">No categories found</p>
                    <?php if (!empty($searchInput)): ?>
                        <p class="text-sm">No results for "<?= htmlspecialchars($searchInput) ?>"</p>
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
        $tasks = show($pagination['start'], $dataPerPage);
    } else {
        // Search tasks
        $result = searchTask($searchInput, $dataPerPage, $currentPage);
        $tasks = $result['tasks'];
    }

    // Generate table rows - COPIED FROM views/pages/category.php
    if (empty($tasks)) {
        ?>
        <tr>
            <td colspan="6" class="text-center py-12 text-gray-500">
                <div class="flex flex-col items-center">
                    <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                    <p class="text-lg font-medium">No task found</p>
                    <?php if (!empty($searchInput)): ?>
                        <p class="text-sm">No results for "<?= htmlspecialchars($searchInput) ?>"</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php
    } else {
        // COPIED: Pagination calculation dari category.php
        $startNumber = 1; // Untuk search results mulai dari 1
        $i = $startNumber;

        foreach ($tasks as $task): ?>
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
                <td class="table-cell py-2 px-4"><?php echo $task['category_name']; ?></td>
                <td class="table-cell py-2 px-4"><?php echo $createdAtFormatted; ?></td>
                <td class="table-cell py-2 px-4">
                    <!-- COPIED: Action buttons dari views/pages/category.php -->
                    <div class="action-button flex gap-3">
                        <button type="button"
                            onclick="showEditModal('task', {id: '<?= htmlspecialchars($task['id'], ENT_QUOTES); ?>', name: '<?= htmlspecialchars($task['name'], ENT_QUOTES); ?>'})"
                            class="flex justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white transition-colors duration-200">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button"
                            onclick="showConfirmationDelete('task', '<?= htmlspecialchars($task['id'], ENT_QUOTES) ?>', '<?= htmlspecialchars($task['name'], ENT_QUOTES) ?>')"
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
?>