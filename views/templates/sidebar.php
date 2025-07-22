<?php $currentPage = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>
<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 bg-black opacity-10 z-40 hidden lg:hidden transition-opacity duration-300"
    id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-lg transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col p-4"
    id="sidebar">
    <div class="brand flex items-center space-x-3 mb-8">
        <img src="https://img.icons8.com/fluency/96/000000/todo-list.png" alt="todo-list-icon" width="35">
        <h3 class="text-xl font-bold text-primary sidebar-text">Todolist App</h3>
    </div>

    <nav class="flex flex-col justify-between flex-1">
        <ul class="space-y-4">
            <li>
                <a href="?page=dashboard"
                    class="flex items-center px-4 py-2 rounded hover:bg-primary hover:text-white font-medium <?php echo ($currentPage == 'dashboard') ? 'bg-primary text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-chart-line"></i></div>
                    <span class="sidebar-text ml-4">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="?page=task"
                    class="flex items-center px-4 py-2 rounded hover:bg-primary  hover:text-white text-gray-700 font-medium <?php echo ($currentPage == 'task') ? 'bg-primary text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-clipboard-list"></i></div>
                    <span class="sidebar-text ml-4">Tasks</span>
                </a>
            </li>
            <li>
                <a href="?page=category"
                    class="flex items-center px-4 py-2 rounded hover:bg-primary  hover:text-white text-gray-700 font-medium <?php echo ($currentPage == 'category') ? 'bg-primary text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-list"></i></div>
                    <span class="sidebar-text ml-4">Category</span>
                </a>
            </li>
            <li>
                <a href="?page=report"
                    class="flex items-center px-4 py-2 rounded hover:bg-primary  hover:text-white text-gray-700 font-medium <?php echo ($currentPage == 'report') ? 'bg-primary text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-file-lines"></i></div>
                    <span class="sidebar-text ml-4">Report</span>
                </a>
            </li>
            <li>
                <a href="?page=settings"
                    class="flex items-center px-4 py-2 rounded hover:bg-primary  hover:text-white text-gray-700 font-medium <?php echo ($currentPage == 'settings') ? 'bg-primary text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-gear"></i></div>
                    <span class="sidebar-text ml-4">Settings</span>
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="logout.php"
                    class="flex items-center px-4 py-2 rounded hover:bg-red-600  hover:text-white text-gray-700 font-medium <?php echo ($currentPage == 'logout') ? 'bg-red-600 text-white' : ''; ?>">
                    <div class="icon w-6 text-center"><i class="fa-solid fa-right-from-bracket"></i></div>
                    <span class="sidebar-text ml-4">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>