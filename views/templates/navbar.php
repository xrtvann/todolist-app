<?php
// Get current user info from secure session
$currentFullName = getCurrentFullName();
$currentUsername = getCurrentUsername();
$displayName = $currentFullName ? $currentFullName : $currentUsername;

$userInitials = '';
if ($displayName) {
    $nameParts = explode(' ', $displayName);
    $userInitials = strtoupper(substr($nameParts[0], 0, 1));
    if (count($nameParts) > 1) {
        $userInitials .= strtoupper(substr($nameParts[1], 0, 1));
    }
}
?>
<header class="navbar">
    <nav class="flex items-center justify-between bg-white p-4 shadow-sm">
        <div class="left-menu flex items-center">
            <div class="toggle-sidebar">
                <button id="toggleSidebar"
                    class="p-2 text-gray-500 hover:text-gray-800 rounded-md focus:outline-none transition-colors duration-200"
                    onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars text-xl transition-colors duration-200"></i>
                </button>
            </div>
        </div>


        <div class="user-info border-1 border-gray-300 rounded-md p-2 flex items-center space-x-2 cursor-pointer hover:bg-gray-100"
            id="userMenuButton" onclick="toggleUserMenu()">
            <div
                class="name-profile bg-grey-200 rounded-full border-1 border-gray-300 w-7 h-7 flex items-center justify-center">
                <p class="font-semibold text-sm" id="userInitials"><?= htmlspecialchars($userInitials) ?></p>
            </div>
            <p class="text-xs font-semibold text-black" id="userDisplayName"><?= htmlspecialchars($displayName) ?></p>
        </div>
    </nav>

    <div class="dropdown-menu bg-white w-42 absolute right-4 z-[1000px] top-20 border-1 border-gray-300 rounded-sm hidden"
        id="userMenuDropdown">
        <ul class="flex flex-col space-y-1 mt-2 ml-2">
            <li><a href="?page=profile" class="block p-2 text-sm font-semibold text-gray-700 hover:text-primary"><i
                        class="fa fa-user mr-2 w-4"></i>Profile</a></li>
            <li><a href="?page=settings" class="block p-2 text-sm font-semibold text-gray-700 hover:text-primary"><i
                        class="fa fa-gear mr-2 w-4"></i>Settings</a></li>
            <li><a href="logout.php" class="block p-2 text-sm font-semibold text-gray-700 hover:text-red-600"><i
                        class="fa fa-sign-out-alt mr-2 w-4"></i>Logout</a></li>
        </ul>
    </div>
</header>