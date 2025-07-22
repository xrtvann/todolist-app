<?php
// Import settings controller
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

<main class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold mb-2">Settings</h1>
            <p class="text-gray-600">Manage your account, preferences, and data</p>
        </div>
        <div class="text-sm text-gray-500">
            <i class="fas fa-user-circle mr-2"></i>
            <?= htmlspecialchars($displayName) ?>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Password Security -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-lock text-red-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Password & Security</h2>
                    <p class="text-sm text-gray-600">Change your password to keep your account secure</p>
                </div>
            </div>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            minlength="6" required>
                        <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            minlength="6" required>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" name="change_password"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-key mr-2"></i>
                        Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-database text-green-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Data Management</h2>
                    <p class="text-sm text-gray-600">Export, backup, or manage your data</p>
                </div>
            </div>

            <!-- Data Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Export & Backup -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-download text-white text-sm"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-blue-900">Export & Backup</h3>
                    </div>
                    <p class="text-xs text-blue-700 mb-3">Download all your categories and tasks as a backup file</p>
                    <button onclick="exportUserData()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Export All Data (JSON)
                    </button>
                </div>

                <!-- Data Cleanup - More Prominent -->
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-broom text-white text-sm"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-yellow-900">Data Cleanup</h3>
                    </div>
                    <p class="text-xs text-yellow-700 mb-3">Remove completed tasks to keep your workspace clean</p>
                    <button onclick="clearCompletedTasks()"
                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                        <i class="fas fa-broom mr-2"></i>
                        Clear
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tools text-white text-sm"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Common data management tasks</p>
                    <div class="space-y-2">
                        <a href="?page=category"
                            class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-folder-plus mr-2"></i>
                            Add Category
                        </a>
                        <a href="?page=task"
                            class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Task
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-red-900">Danger Zone</h2>
                    <p class="text-sm text-red-600">Irreversible and destructive actions</p>
                </div>
            </div>

            <div class="space-y-4">
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-red-200 rounded-lg">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-sm font-medium text-gray-900">Delete All Tasks</h3>
                        <p class="text-sm text-gray-600">Permanently delete all your tasks (categories remain)</p>
                    </div>
                    <button onclick="deleteAllTasks()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Delete All Tasks
                    </button>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border border-red-200 rounded-lg">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-sm font-medium text-gray-900">Delete All Data</h3>
                        <p class="text-sm text-gray-600">Permanently delete all categories and tasks</p>
                    </div>
                    <button onclick="deleteAllData()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Delete All Data
                    </button>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border-2 border-red-300 rounded-lg bg-red-50">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="text-sm font-medium text-red-900">Delete Account</h3>
                        <p class="text-sm text-red-700">Permanently delete your account and all associated data</p>
                    </div>
                    <button onclick="deleteAccount()"
                        class="inline-flex items-center px-4 py-2 bg-red-800 text-white text-sm font-medium rounded-lg hover:bg-red-900 transition-colors duration-200">
                        <i class="fas fa-user-times mr-2"></i>
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function exportUserData() {
        Swal.fire({
            title: 'Export Your Data',
            text: 'This will download all your categories and tasks as a JSON backup file.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-download mr-2"></i>Export Data',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Preparing Export...',
                    text: 'Please wait while we prepare your data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'export_data';
                input.value = '1';

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                // Close loading after delay
                setTimeout(() => {
                    Swal.close();
                }, 2000);
            }
        });
    }

    function clearCompletedTasks() {
        Swal.fire({
            title: 'Clear Completed Tasks',
            text: 'This will permanently delete all tasks marked as completed. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#eab308',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-broom mr-2"></i>Clear Completed',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'clear_completed';
                input.value = '1';

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function deleteAllTasks() {
        Swal.fire({
            title: 'Delete All Tasks',
            text: 'This will permanently delete ALL your tasks. Categories will remain. This action cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete All Tasks',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Second confirmation
                Swal.fire({
                    title: 'Are you absolutely sure?',
                    text: 'Type "DELETE ALL TASKS" to confirm this action',
                    input: 'text',
                    inputPlaceholder: 'Type: DELETE ALL TASKS',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Delete All Tasks',
                    cancelButtonText: 'Cancel',
                    preConfirm: (text) => {
                        if (text !== 'DELETE ALL TASKS') {
                            Swal.showValidationMessage('Please type exactly: DELETE ALL TASKS');
                        }
                        return text;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.href;

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_all_tasks';
                        input.value = '1';

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
    }

    function deleteAllData() {
        Swal.fire({
            title: 'Delete All Data',
            text: 'This will permanently delete ALL your categories and tasks. This action cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete Everything',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Second confirmation
                Swal.fire({
                    title: 'Are you absolutely sure?',
                    text: 'Type "DELETE ALL DATA" to confirm this action',
                    input: 'text',
                    inputPlaceholder: 'Type: DELETE ALL DATA',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Delete All Data',
                    cancelButtonText: 'Cancel',
                    preConfirm: (text) => {
                        if (text !== 'DELETE ALL DATA') {
                            Swal.showValidationMessage('Please type exactly: DELETE ALL DATA');
                        }
                        return text;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.href;

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_all_data';
                        input.value = '1';

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
    }

    function deleteAccount() {
        Swal.fire({
            title: 'Delete Account',
            text: 'This will permanently delete your account and ALL associated data. This action cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Delete My Account',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Second confirmation
                Swal.fire({
                    title: 'Final Confirmation',
                    text: 'Type "DELETE MY ACCOUNT" to permanently delete your account',
                    input: 'text',
                    inputPlaceholder: 'Type: DELETE MY ACCOUNT',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Delete Account Forever',
                    cancelButtonText: 'Cancel',
                    preConfirm: (text) => {
                        if (text !== 'DELETE MY ACCOUNT') {
                            Swal.showValidationMessage('Please type exactly: DELETE MY ACCOUNT');
                        }
                        return text;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window.location.href;

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_account';
                        input.value = '1';

                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
    }

    // Password confirmation validation
    document.getElementById('confirm_password').addEventListener('input', function () {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = this.value;

        if (newPassword !== confirmPassword) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    document.getElementById('new_password').addEventListener('input', function () {
        const confirmPassword = document.getElementById('confirm_password');
        if (confirmPassword.value && this.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });
</script>