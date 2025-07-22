<?php
// Import dashboard controller
require_once './../controller/dashboardController.php';

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get dashboard data
$stats = getDashboardStats();
$recentTasks = getRecentTasks(5);
$categoryProgress = getCategoryProgress();
$recentCompleted = getRecentCompletedTasks(3);
$todayStats = getTodayStats();

// Get user info for welcome message
$currentFullName = getCurrentFullName();
$currentUsername = getCurrentUsername();
$displayName = $currentFullName ? $currentFullName : $currentUsername;
?>

<main class="p-6">
    <div class="flex justify-between items-start">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-2">Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($displayName) ?>! Here's your productivity overview.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6 mb-8">
        <!-- Total Categories Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total<br>Categories</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['totalCategories'] ?></p>
                </div>
            </div>
        </div>

        <!-- Total Tasks Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total<br>Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['totalTasks'] ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Tasks Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending<br>Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['pendingTasks'] ?></p>
                </div>
            </div>
        </div>

        <!-- Completion Rate Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completion<br>Rate</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['completionRate'] ?>%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded shadow py-6 px-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="?page=task"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Add New Task
            </a>
            <a href="?page=category"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-folder-plus mr-2"></i>
                Add New Category
            </a>
            <a href="?page=task"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>
                View All Tasks
            </a>
            <a href="?page=report"
                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                <i class="fas fa-chart-bar mr-2"></i>
                View Reports
            </a>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="bg-white rounded shadow py-6 px-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Activity</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tasks Created Today</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $todayStats['tasksCreatedToday'] ?></p>
                </div>
            </div>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tasks Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $todayStats['tasksCompletedToday'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Tasks -->
        <div class="bg-white rounded shadow py-6 px-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Tasks</h3>
                <a href="?page=task" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <?php if (empty($recentTasks)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-tasks text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No tasks created yet</p>
                        <a href="?page=task" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Create your first
                            task</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($recentTasks as $task): ?>
                            <?php
                            $createdAt = new DateTime($task['created_at']);
                            $timeAgo = $createdAt->format('M j, H:i');
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 mr-3">
                                        <?php if ($task['status'] === 'done'): ?>
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        <?php else: ?>
                                            <i class="fas fa-circle text-orange-500"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($task['name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($task['category_name']) ?> •
                                            <?= $timeAgo ?>
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $task['status'] === 'done' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' ?>">
                                    <?= ucfirst($task['status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Category Progress -->
        <div class="bg-white rounded shadow py-6 px-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Category Progress</h3>
                <a href="?page=category" class="text-sm text-blue-600 hover:text-blue-800">Manage Categories</a>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <?php if (empty($categoryProgress)): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No categories created yet</p>
                        <a href="?page=category" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Create your
                            first category</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($categoryProgress as $category): ?>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($category['name']) ?>
                                    </h4>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">
                                            <?= $category['completed_tasks'] ?>/<?= $category['total_tasks'] ?> tasks
                                        </p>
                                        <p class="text-xs text-gray-500"><?= $category['completion_percentage'] ?>% complete</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                        style="width: <?= $category['completion_percentage'] ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>