<?php
// import necessary files
require_once './../controller/categoryController.php';
require_once './../utility/databaseUtility.php';

// check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// generate a unique ID for the new category
$newID = generateCategoryID();
// fetch categories with pagination
$pagination = pagination('category', 10);
$start = $pagination['start'];
$currentPage = $pagination['currentPage'];
$amountOfPage = $pagination['amountOfPage'];
$amountOfData = $pagination['amountOfData'];
$dataPerPage = $pagination['dataPerPage'];
$categories = show($dataPerPage, $start);

?>
<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-2">Category</h1>
            <p>Manage your categories effectively</p>
        </div>
    </div>

    <div class="container mt-6 bg-white rounded shadow py-6 px-6">
        <div class="top-table mb-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="search-box flex">
                <form action="" method="post" class="relative">
                    <!-- Search input -->
                    <i class="fa fa-search absolute text-gray-400 top-2 left-2"></i>
                    <input data-search="livesearch" data-page="category" id="searchInputCategory"
                        name="searchInputCategory" type="text" placeholder="Search"
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
                    onclick="showModal('add', 'category')">
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
                            <th class="table-cell py-2 px-4 text-left">Name</th>
                            <th class="table-cell py-2 px-4 text-left">Created At</th>
                            <th class="table-cell py-2 px-4 text-left"></th>
                        </tr>
                    </thead>
                    <tbody class="table-row-group" id="table-body">
                        <?php
                        $startNumber = ($pagination['currentPage'] - 1) * $pagination['dataPerPage'] + 1;
                        $i = $startNumber;
                        ?>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-12 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium">No categories found</p>
                                        <p class="text-sm">Start by creating your first category</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <!-- ✅ Tambah hover effect pada row -->
                                <tr class="table-row border-b border-slate-200 hover:bg-gray-50 transition-colors duration-200">
                                    <?php
                                    $createdAt = new DateTime($category['created_at']);
                                    $createdAtFormatted = $createdAt->format('d M Y H:i');
                                    ?>
                                    <td class="table-cell py-2 px-4"><?php echo $i++; ?></td>
                                    <td class="table-cell py-2 px-4"><?php echo $category['name']; ?></td>
                                    <td class="table-cell py-2 px-4"><?php echo $createdAtFormatted; ?></td>
                                    <td class="table-cell py-2 px-4">
                                        <div class="action-button flex gap-3">
                                            <button type="button"
                                                onclick="showEditModal('category', {id: '<?= htmlspecialchars($category['id']) ?>', name: '<?= htmlspecialchars($category['name']) ?>'})"
                                                class="flex cursor-pointer justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" name="deleteCategory"
                                                onclick="showConfirmationDelete('category', '<?= htmlspecialchars($category['id']) ?>','<?= htmlspecialchars($category['name']) ?>')"
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

        <?php if (!empty($categories) && $pagination['amountOfData'] > 0): ?>
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
                            <a href="?page=category&p=<?= previousButton($currentPage) ?>"
                                class="pagination-btn flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                <i class="fas fa-chevron-left mr-2"></i>
                                Previous
                            </a>
                        <?php endif; ?>

                        <!-- ✅ Page numbers -->
                        <div class="pagination-numbers flex items-center space-x-1">






                            <?php for ($i = 1; $i <= $amountOfPage; $i++): ?>
                                <?php if ($i == $currentPage): ?>
                                    <a href="?page=category&p=<?= $i ?>"
                                        class="pagination-number px-3 py-2 text-sm font-medium text-white bg-green-600 border border-gray-300 rounded-md hover:bg-gray-50 hover:text-gray-700 transition-colors duration-200">
                                        <?= $i ?>
                                    </a>
                                <?php else: ?>
                                    <a href="?page=category&p=<?= $i ?>"
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
                            <a href="?page=category&p=<?= nextButton($currentPage, $amountOfPage) ?>"
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

    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="add-category-modal">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="modal relative w-full max-w-[350px] lg:max-w-2xl mx-auto max-h-screen rounded-md">
            <div class="modal-title bg-slate-100 px-4 py-2.5 flex justify-center items-center rounded-t-md">
                <h2 class="text-xl font-semibold">Add New Category</h2>
            </div>
            <div class="modal-content bg-white p-6 rounded-b-md">

                <form action="" method="post">
                    <div class="input mb-15">
                        <div class="mb-4">
                            <label for="categoryID" class="block text-gray-700 text-sm font-semibold mb-2">ID</label>
                            <input type="text" id="categoryID" name="categoryID"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required readonly value="<?php echo $newID; ?>">
                        </div>
                        <div class="mb-4">
                            <label for="categoryName"
                                class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" id="categoryName" name="categoryName"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required>
                        </div>
                    </div>


                    <div class="action-button flex justify-end gap-2">
                        <button type="button" onclick="closeModal('add', 'category')"
                            class="px-4 py-2 bg-slate-500 text-white rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                            <i class="fa fa-close me-1.5"></i> Close
                        </button>
                        <button type="submit" name="saveCategory"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer">
                            <i class="fa fa-save me-1.5"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="update-category-modal">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="modal relative w-full max-w-[350px] lg:max-w-2xl mx-auto max-h-screen rounded-md">
            <div class="modal-title bg-slate-100 px-4 py-2.5 flex justify-center items-center rounded-t-md">
                <h2 class="text-xl font-semibold">Update Category</h2>
            </div>
            <div class="modal-content bg-white p-6 rounded-b-md">

                <form action="" method="post">
                    <div class="input mb-15">
                        <div class="mb-4">
                            <label for="updateCategoryID"
                                class="block text-gray-700 text-sm font-semibold mb-2">ID</label>
                            <input type="text" id="updateCategoryID" name="updateCategoryID"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required readonly>
                        </div>
                        <div class="mb-4">
                            <label for="updateCategoryName"
                                class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" id="updateCategoryName" name="updateCategoryName"
                                class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full"
                                required>
                        </div>
                    </div>

                    <input type="hidden" name="originalCategoryID" id="originalCategoryID">
                    <input type="hidden" name="originalCategoryName" id="originalCategoryName">


                    <div class="action-button flex justify-end gap-2">
                        <button type="button" onclick="closeModal('update', 'category')"
                            class="px-4 py-2 bg-slate-500 text-white rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                            <i class="fa fa-close me-1.5"></i> Close
                        </button>
                        <button type="submit" name="saveChangesCategory"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer">
                            <i class="fa fa-save me-1.5"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>