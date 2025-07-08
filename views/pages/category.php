<?php
// Template dasar halaman Category
?>
<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-4">Category</h1>
        </div>
    </div>

    <div class="container mt-4 bg-white rounded shadow py-4 px-4">
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
            <button class="flex items-center gap-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-blue-300 cursor-pointer">
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
                    <tr class="table-row border-b-1 border-slate-200">
                        <td class="table-cell py-2 px-4">1</td>
                        <td class="table-cell py-2 px-4">Work</td>
                        <td class="table-cell py-2 px-4">2023-01-01</td>
                        <td class="table-cell py-2 px-4">
                            <div class="action-button flex gap-3">
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white"><i class="fas fa-edit"></i></a>
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white"><i class="fas fa-trash"></i></a>
                            </div>

                        </td>
                    </tr>
                    <tr class="table-row">
                        <td class="table-cell py-2 px-4">1</td>
                        <td class="table-cell py-2 px-4">Work</td>
                        <td class="table-cell py-2 px-4">2023-01-01</td>
                        <td class="table-cell py-2 px-4">
                            <div class="action-button flex gap-3">
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-orange-500 border border-orange-500 hover:bg-orange-500 hover:text-white"><i class="fas fa-edit"></i></a>
                                <a href="#" class="flex justify-center items-center px-2 py-2 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white"><i class="fas fa-trash"></i></a>
                            </div>

                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>


</main>