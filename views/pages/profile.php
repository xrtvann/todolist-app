<?php
// Import settings controller for profile functions
require_once './../controller/settingsController.php';

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user settings
$userSettings = getUserSettings();

// Get current user info for display
$currentFullName = getCurrentFullName();
$currentUsername = getCurrentUsername();
$displayName = $currentFullName ? $currentFullName : $currentUsername;
?>

<main class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold mb-2">Profile</h1>
            <p class="text-gray-600">Manage your personal information and account details</p>
        </div>
        <div class="text-sm text-gray-500">
            <i class="fas fa-user-circle mr-2"></i>
            <?= htmlspecialchars($displayName) ?>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                    <p class="text-sm text-gray-600">Update your personal information and display name</p>
                </div>
            </div>

            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" name="username"
                            value="<?= htmlspecialchars($userSettings['username'] ?? '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            required>
                        <p class="text-xs text-gray-500 mt-1">This will be your unique identifier</p>
                    </div>
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name
                        </label>
                        <input type="text" id="full_name" name="full_name"
                            value="<?= htmlspecialchars($userSettings['full_name'] ?? '') ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">Your display name (optional)</p>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                            <span class="text-gray-600">Account created:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                <?= date('F j, Y', strtotime($userSettings['created_at'])) ?>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                            <span class="text-gray-600">Member since:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                <?php
                                $createdDate = new DateTime($userSettings['created_at']);
                                $now = new DateTime();
                                $interval = $createdDate->diff($now);

                                if ($interval->y > 0) {
                                    echo $interval->y . ' year' . ($interval->y > 1 ? 's' : '');
                                } elseif ($interval->m > 0) {
                                    echo $interval->m . ' month' . ($interval->m > 1 ? 's' : '');
                                } else {
                                    echo $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-4">
                        <button type="submit" name="update_profile"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Update Profile
                        </button>
                        <a href="?page=settings"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-cog mr-2"></i>
                            Account Settings
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>