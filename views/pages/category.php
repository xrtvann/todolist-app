<?php
// import necessary files
require_once './../controller/categoryController.php';
require_once './../utility/databaseUtility.php';

// check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// generate a unique ID for the new category
$id = generateCategoryID();

// fetch all categories from the database
$categories = read("SELECT name, created_at FROM category ORDER BY created_at DESC");
?>
<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-2">Category</h1>
            <p>Manage your categories effectively</p>
        </div>
    </div>

    <div class="container mt-6 bg-white rounded shadow py-6 px-6">
        <div class="top-table mb-5 flex justify-between items-center">
            <div class="search-box flex">
                <form action="" method="post" class="relative">
                    <!-- Search input -->
                    <i class="fa fa-search absolute text-gray-400 top-2 left-2"></i>
                    <input type="text" placeholder="Search" class="border border-gray-300 py-1 px-8 rounded focus:border-gray-500 focus:outline focus:outline-gray-50">
                </form>

                <button class="filter-button flex items-center gap-2 ml-4 px-3 py-1 bg-gray-100 text-black rounded hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                    <i class="fa fa-filter"></i>
                    <span>Filter</span>
                </button>

            </div>

            <div class="add-button">
                <button class="flex items-center gap-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer" onclick="showModal('add', 'category')">
                    <i class="fa fa-plus"></i>
                    <span>Add New</span>
                </button>
            </div>
        </div>
        <div class="table-content">
            <table class="w-full">
                <thead class="table-header-group bg-slate-100 text-sm text-semibold text-gray-700">
                    <tr class="table-row">
                        <th class="table-cell py-2 px-4 text-left">No</th>
                        <th class="table-cell py-2 px-4 text-left">Name</th>
                        <th class="table-cell py-2 px-4 text-left">Created At</th>
                        <th class="table-cell py-2 px-4 text-left"></th>
                    </tr>
                </thead>
                <tbody class="table-row-group">
                    <?php $i = 1; ?>
                    <?php foreach($categories as $category) : ?>
                    <tr class="table-row border-b-1 border-slate-200">
                        <td class="table-cell py-2 px-4"><?php echo $i++; ?></td>
                        <td class="table-cell py-2 px-4"><?php echo $category['name']; ?></td>
                        <td class="table-cell py-2 px-4"><?php echo $category['created_at']; ?></td>
                        <td class="table-cell py-2 px-4">
                            <div class="action-button flex gap-3">
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white"><i class="fas fa-edit"></i></a>
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white"><i class="fas fa-trash"></i></a>
                            </div>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center hidden" id="add-category-modal">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="modal relative w-2xl rounded-md">
            <div class="modal-title bg-slate-100 px-4 py-2.5 flex justify-center items-center rounded-t-md">
                <h2 class="text-xl font-semibold">Add New Category</h2>
            </div>
            <div class="modal-content bg-white p-6 rounded-b-md">

                <form action="" method="post">
                    <div class="input mb-15">
                        <div class="mb-4">
                            <label for="categoryID" class="block text-gray-700 text-sm font-semibold mb-2">ID</label>
                            <input type="text" id="categoryID" name="categoryID" class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full" required readonly value="<?php echo $id; ?>">
                        </div>
                        <div class="mb-4">
                            <label for="categoryName" class="block text-gray-700 text-sm font-semibold mb-2">Name</label>
                            <input type="text" id="categoryName" name="categoryName" class="border border-gray-300 py-2 px-3 rounded focus:border-gray-500 focus:outline focus:outline-gray-50 w-full" required>
                        </div>
                    </div>


                    <div class="action-button flex justify-end gap-2">
                        <button type="button" onclick="closeModal('add', 'category')" class="px-4 py-2 bg-slate-500 text-white rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                            <i class="fa fa-close me-1.5"></i> Close
                        </button>
                        <button type="submit" name="saveCategory" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 cursor-pointer">
                            <i class="fa fa-save me-1.5"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</main>