<?php
// Template dasar halaman Category
?>
<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-3xl font-bold mb-4">Category</h1>
            <p>Organize your tasks into categories for better management.</p>
        </div>
    </div>

    <table class="table w-full bg-white rounded shadow-sm">
        <thead class="table-header-group">
            <tr class="table-row">
                <th class="table-cell py-2 px-4 text-left">#</th>
                <th class="table-cell py-2 px-4 text-left">Name</th>
                <th class="table-cell py-2 px-4 text-left">Created At</th>
                <th class="table-cell py-2 px-4 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="table-row-group">
            <tr class="table-row">
                <td class="table-cell py-2 px-4">1</td>
                <td class="table-cell py-2 px-4">Work</td>
                <td class="table-cell py-2 px-4">2023-01-01</td>
                <td class="table-cell py-2 px-4">
                    <a href="#" class="text-orange-500"><i class="fas fa-edit"></i></a>
                    <a href="#" class="text-red-500"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <tr class="table-row">
                <td class="table-cell py-2 px-4">1</td>
                <td class="table-cell py-2 px-4">Work</td>
                <td class="table-cell py-2 px-4">2023-01-01</td>
                <td class="table-cell py-2 px-4">
                    <a href="#" class="text-orange-500"><i class="fas fa-edit"></i></a>
                    <a href="#" class="text-red-500"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
</main>