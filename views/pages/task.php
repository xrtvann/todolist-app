<?php
// import necessary files
require_once './../controller/taskController.php';
require_once './../utility/databaseUtility.php';

// check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// generate a unique ID for the new task
$newID = generateTaskID();
// fetch tasks with pagination
$pagination = pagination('task', 10);
$start = $pagination['start'];
$currentPage = $pagination['currentPage'];
$amountOfPage = $pagination['amountOfPage'];
$amountOfData = $pagination['amountOfData'];
$dataPerPage = $pagination['dataPerPage'];
$tasks = show($dataPerPage, $start);

// Get user's categories for dropdown
$userId = getCurrentUserId();
$query = "SELECT * FROM category WHERE user_id = '$userId' ORDER BY name";
$result = read($query);
$categories = $result;
?>

<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-2">Tasks</h1>
            <p>Organize your tasks Effectively</p>
        </div>
    </div>

    <div class="container mt-6 bg-white rounded shadow py-6 px-6">
        <div class="top-table mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="search-box flex">
                <form action="" method="post" class="relative">
                    <!-- Search input -->
                    <i class="fa fa-search absolute text-gray-400 top-2 left-2"></i>
                    <input data-search="livesearch" data-page="task" id="searchInputTask" name="searchInputTask"
                        type="text" placeholder="Search"
                        class="max-w-[180px] md:max-w-full border border-gray-300 py-1 px-8 rounded focus:border-gray-500 focus:outline focus:outline-gray-50">
                </form>

                <button
                    class="filter-button flex items-center gap-2 ml-4 px-3 py-1 bg-gray-100 text-black rounded hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                    <i class="fa fa-filter"></i>
                    <span>Filter</span>
                </button>

            </div>

            <div class="add-button">
                <button
                    class="flex items-center gap-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer"
                    onclick="showModal('add', 'task')">
                    <i class="fa fa-plus"></i>
                    <span>Add New</span>
                </button>
            </div>
        </div>
        <div class="table-content" id="table-container">
            <div
                class="max-h-[293px] max-w-[280px] lg:max-w-full overflow-x-auto border border-gray-200 rounded-sm shadow-sm">
                <table class="w-full min-w-max">
                    <thead
                        class="table-header-group bg-slate-100 text-sm text-semibold text-gray-700 sticky top-0 z-10">
                        <tr class="table-row">
                            <th class="table-cell py-2 px-4 text-left">No</th>
                            <th class="table-cell py-2 px-4 text-left">Task</th>
                            <th class="table-cell py-2 px-4 text-left">Status</th>
                            <th class="table-cell py-2 px-4 text-left">Category</th>
                            <th class="table-cell py-2 px-4 text-left">Created At</th>
                            <th class="table-cell py-2 px-4 text-left"></th>
                        </tr>
                    </thead>
                    <tbody class="table-row-group" id="table-body">
                        <?php
                        $startNumber = ($pagination['currentPage'] - 1) * $pagination['dataPerPage'] + 1;
                        $i = $startNumber;
                        ?>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-tasks text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium">No tasks found</p>
                                        <p class="text-sm">Start by creating your first task</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <!-- ✅ Tambah hover effect pada row -->
                                <tr class="table-row border-b border-slate-200 hover:bg-gray-50 transition-colors duration-200">
                                    <?php
                                    $createdAt = new DateTime($task['created_at']);
                                    $createdAtFormatted = $createdAt->format('d M Y H:i');
                                    ?>
                                    <td class="table-cell py-2 px-4"><?php echo $i++; ?></td>
                                    <td class="table-cell py-2 px-4"><?php echo $task['name']; ?></td>
                                    <?php if ($task['status'] === 'pending'): ?>
                                        <td class="table-cell py-2 px-4">
                                            <p
                                                class="flex bg-orange-100 px-2 rounded-full justify-center w-25 text-orange-600 font-semibold">
                                                <?php echo $task['status']; ?>
                                            </p>
                                        </td>
                                    <?php elseif ($task['status'] === 'done'): ?>
                                        <td class="table-cell py-2 px-4">
                                            <p
                                                class="flex bg-green-100 px-2 rounded-full justify-center w-25 text-green-600 font-semibold">
                                                <?php echo $task['status']; ?>
                                            </p>
                                        </td>
                                    <?php endif; ?>
                                    <td class="table-cell py-2 px-4"><?php echo $task['category_name']; ?></td>
                                    <td class="table-cell py-2 px-4"><?php echo $createdAtFormatted; ?></td>
                                    <td class="table-cell py-2 px-4">
                                        <div class="action-button flex gap-3">
                                            <form action="" method="post" style="display:inline;">
                                                <input type="hidden" name="doneTaskID"
                                                    value="<?= htmlspecialchars($task['id']) ?>">
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
                                                onclick="showEditModal('task', {id: '<?= htmlspecialchars($task['id']) ?>', name: '<?= htmlspecialchars($task['name']) ?>', category_id: '<?= htmlspecialchars($task['category_id']) ?>', category_name: '<?= htmlspecialchars($task['category_name']) ?>'})"
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
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($tasks) && $pagination['amountOfData'] > 0): ?>
            <div class="pagination-wrapper mt-6 py-4">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4">
                    <!-- Info pagination -->
                    <?php
                    if ($pagination['amountOfData'] > 0) {
                        $startRecord = ($pagination['currentPage'] - 1) * $pagination['dataPerPage'] + 1;
                        $endRecord = min($pagination['currentPage'] * $pagination['dataPerPage'], $pagination['amountOfData']);
                    } else {
                        $startRecord = 0;
                        $endRecord = 0;
                    }
                    ?>
                    <div class="pagination-info text-sm text-gray-700">
                        Showing <span class="font-medium"><?= $startRecord ?></span> to <span
                            class="font-medium"><?= $endRecord ?></span> of <span
                            class="font-medium"><?= $pagination['amountOfData'] ?></span> results
                    </div>

                    <!-- ✅ Pagination controls -->
                    <div class="pagination-controls flex items-center space-x-2">
                        <!-- ✅ Previous button -->
                        <?php if ($currentPage == 1): ?>
                            <a
                                class="pagination-btn flex items-center px-3 py-2 text-sm font-medium cursor-not-allowed text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-chevron-left mr-2"></i>
                                Previous
                            </a>
                        <?php else: ?>
                            <a href="?page=task&p=<?= previousButton($currentPage) ?>"
                                class="pagination-btn flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-chevron-left mr-2"></i>
                                Previous
                            </a>
                        <?php endif; ?>

                        <!-- ✅ Page numbers -->
                        <div class="pagination-numbers flex items-center space-x-1">






                            <?php for ($i = 1; $i <= $amountOfPage; $i++): ?>
                                <?php if ($i == $currentPage): ?>
                                    <a href="?page=task&p=<?= $i ?>"
                                        class="pagination-number px-3 py-2 text-sm font-medium text-white bg-green-600 border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                        <?= $i ?>
                                    </a>
                                <?php else: ?>
                                    <a href="?page=task&p=<?= $i ?>"
                                        class="pagination-number px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>



                        </div>

                        <!-- ✅ Next button -->
                        <?php if ($currentPage == $amountOfPage): ?>
                            <a
                                class="pagination-btn flex items-center px-3 py-2 text-sm font-medium cursor-not-allowed text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                Next
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="?page=task&p=<?= nextButton($currentPage, $amountOfPage) ?>"
                                class="pagination-btn flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                Next
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="fixed inset-0 z-50 items-center justify-center flex hidden" id="add-task-modal">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="modal relative w-full max-w-[350px] lg:max-w-2xl mx-auto max-h-screen rounded-md">
            <div class="modal-title bg-slate-100 px-4 py-2.5 flex justify-center items-center rounded-t-md">
                <h2 class="text-xl font-semibold">Add New Task</h2>
            </div>
            <div class="modal-content bg-white p-6 rounded-b-md">
                <form action="" method="post">
                    <div class="input mb-20">
                        <div class="mb-4">
                            <label for="taskID" class="block text-gray-700 text-sm font-semibold mb-2">ID</label>
                            <input type="text" id="taskID" name="taskID"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required readonly value="<?php echo $newID; ?>">
                        </div>
                        <div class="mb-4">
                            <label for="taskName" class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" id="taskName" name="taskName"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="taskCategory"
                                class="block text-gray-700 text-sm font-semibold mb-2">Category</label>
                            <div class="relative" id="dropdown-wrapper">
                                <button type="button" name="dropdownSelected" id="dropdownBtn"
                                    class="w-full border border-gray-300 py-2 px-3 rounded text-left bg-white flex justify-between items-center">
                                    <span id="dropdownSelected">Select Category</span>
                                    <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul id="dropdownList"
                                    class="absolute z-50 w-full bg-white border border-gray-300 rounded mt-1 max-h-30 overflow-y-auto hidden">
                                    <?php foreach ($categories as $category): ?>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                            data-value="<?= htmlspecialchars($category['id']) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <!-- Hidden input for PHP -->
                                <input type="hidden" name="taskCategory" id="taskCategory" required>
                            </div>
                        </div>
                    </div>

                    <div class="action-button flex justify-end gap-2">
                        <button type="button" onclick="closeModal('add', 'task')"
                            class="px-4 py-2 bg-slate-500 text-white rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                            <i class="fa fa-close me-1.5"></i> Close
                        </button>
                        <button type="submit" name="saveTask"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer">
                            <i class="fa fa-save me-1.5"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 items-center justify-center flex hidden" id="update-task-modal">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="modal relative w-full max-w-[350px] lg:max-w-2xl mx-auto max-h-screen rounded-md">
            <div class="modal-title bg-slate-100 px-4 py-2.5 flex justify-center items-center rounded-t-md">
                <h2 class="text-xl font-semibold">Update Task</h2>
            </div>
            <div class="modal-content bg-white p-6 rounded-b-md">
                <form action="" method="post">
                    <div class="input mb-20">
                        <div class="mb-4">
                            <label for="updateTaskID" class="block text-gray-700 text-sm font-semibold mb-2">ID</label>
                            <input type="text" id="updateTaskID" name="updateTaskID"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required readonly value="<?php echo $newID; ?>">
                        </div>
                        <div class="mb-4">
                            <label for="updateTaskName"
                                class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" id="updateTaskName" name="updateTaskName"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required>
                        </div>
                        <div class="mb-4">
                            <label for="updateTaskCategory"
                                class="block text-gray-700 text-sm font-semibold mb-2">Category</label>
                            <div class="relative" id="update-dropdown-wrapper">
                                <button type="button" name="dropdownSelected" id="updateDropdownBtn"
                                    class="w-full border border-gray-300 py-2 px-3 rounded text-left bg-white flex justify-between items-center">
                                    <span id="updateDropdownSelected">Select Category</span>
                                    <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul id="updateDropdownList"
                                    class="absolute z-50 w-full bg-white border border-gray-300 rounded mt-1 max-h-30 overflow-y-auto hidden">
                                    <?php foreach ($categories as $category): ?>
                                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                            data-value="<?= htmlspecialchars($category['id']) ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <!-- Hidden input for PHP -->
                                <input type="hidden" name="updateTaskCategory" id="updateTaskCategory" required>
                            </div>
                        </div>
                    </div>

                    <div class="action-button flex justify-end gap-2">
                        <button type="button" onclick="closeModal('update', 'task')"
                            class="px-4 py-2 bg-slate-500 text-white rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                            <i class="fa fa-close me-1.5"></i> Close
                        </button>
                        <button type="submit" name="saveChangesTask"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer">
                            <i class="fa fa-save me-1.5"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>