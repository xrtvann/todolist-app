<header class="flex-1">
    <nav class="flex items-center justify-between bg-white p-4 shadow-sm">
        <div class="left-menu flex items-center">
            <div class="toggle-sidebar">
                <button id="toggleSidebar" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>


        <div class="user-info border-1 border-gray-300 rounded-md p-2 flex items-center space-x-2 cursor-pointer hover:bg-gray-100" id="userMenuButton" onclick="toggleUserMenu()">
            <div class="name-profile bg-grey-200 rounded-full border-1 border-gray-300 w-7 h-7 flex items-center justify-center">
                <p class="font-semibold text-sm">MI</p>
            </div>
            <p class="text-xs font-semibold text-black">Muhammad Irvan</p>
        </div>
    </nav>

    <div class="dropdown-menu bg-white w-42 absolute right-4 top-20 border-1 border-gray-300 rounded-sm hidden" id="userMenuDropdown">
        <ul class="flex flex-col space-y-1 mt-2 ml-2">
            <li><a href="#" class="block p-2 text-sm font-semibold text-gray-700 hover:text-primary"><i class="fa fa-user mr-2 w-4"></i>Profile</a></li>
            <li><a href="#" class="block p-2 text-sm font-semibold text-gray-700 hover:text-primary"><i class="fa fa-gear mr-2 w-4"></i>Settings</a></li>
            <li><a href="#" class="block p-2 text-sm font-semibold text-gray-700 hover:text-red-600"><i class="fa fa-sign-out-alt mr-2 w-4"></i>Logout</a></li>
        </ul>
    </div>
</header>