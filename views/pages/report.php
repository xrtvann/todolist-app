<?php
// Import report controller
require_once './../controller/reportController.php';

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get report data
$stats = getReportStats();
$categoryBreakdown = getCategoryBreakdown();
$monthlyTrend = getMonthlyTrend();
$topCategories = getTopCategories();
$recentActivity = getRecentActivity();

// Get user info for welcome message
$currentFullName = getCurrentFullName();
$currentUsername = getCurrentUsername();
$displayName = $currentFullName ? $currentFullName : $currentUsername;
?>

<main class="p-6">
    <div class="flex justify-between items-start mb-6">
        <div class="title">
            <h1 class="text-2xl font-semibold mb-2">Reports & Analytics</h1>
            <p>Comprehensive overview of your productivity and task management performance.</p>
        </div>

        <!-- Export Buttons -->
        <div class="flex gap-3">
            <button onclick="exportPDF()"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                <i class="fas fa-file-pdf mr-2"></i>
                Export PDF
            </button>
            <button onclick="exportExcel()"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-file-excel mr-2"></i>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total<br>Tasks</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['totalTasks'] ?></p>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completion<br>Rate</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['completionRate'] ?>%</p>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This<br>Month</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['tasksThisMonth'] ?></p>
                </div>
            </div>
        </div>

        <!-- Average per Category -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-bar text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg per<br>Category</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['averageTasksPerCategory'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($stats['totalTasks'] == 0): ?>
        <!-- No Data State -->
        <div class="bg-white rounded shadow py-12 px-6 mb-8 text-center">
            <i class="fas fa-chart-pie text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Data Available Yet</h3>
            <p class="text-gray-600 mb-6">Start creating categories and tasks to see your productivity reports and
                analytics.</p>
            <div class="flex justify-center gap-4">
                <a href="?page=category"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-folder-plus mr-2"></i>
                    Create Category
                </a>
                <a href="?page=task"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Create Task
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Category Breakdown -->
            <div class="bg-white rounded shadow py-6 px-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Performance</h3>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($categoryBreakdown)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No categories found</p>
                            <a href="?page=category" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Create your
                                first category</a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($categoryBreakdown as $category): ?>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($category['category_name']) ?></h4>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">
                                                <?= $category['completed_tasks'] ?>/<?= $category['total_tasks'] ?> completed
                                            </p>
                                            <p class="text-xs text-gray-500"><?= $category['completion_percentage'] ?? 0 ?>% done
                                            </p>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            style="width: <?= $category['completion_percentage'] ?? 0 ?>%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>Completed: <?= $category['completed_tasks'] ?></span>
                                        <span>Pending: <?= $category['pending_tasks'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-white rounded shadow py-6 px-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend (Last 6 Months)</h3>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($monthlyTrend)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No data available yet</p>
                            <p class="text-xs text-gray-400">Start creating tasks to see trends</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($monthlyTrend as $month): ?>
                                <?php $monthlyRate = $month['tasks_created'] > 0 ? round(($month['tasks_completed'] / $month['tasks_created']) * 100, 1) : 0; ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($month['month_name']) ?>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            <?= $month['tasks_completed'] ?>/<?= $month['tasks_created'] ?> completed
                                            (<?= $monthlyRate ?>%)
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900"><?= $month['tasks_created'] ?></p>
                                        <p class="text-xs text-gray-500">tasks created</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Top Performing Categories & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Categories -->
            <div class="bg-white rounded shadow py-6 px-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Categories</h3>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($topCategories)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-trophy text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No completed categories yet</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($topCategories as $index => $category): ?>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 mr-3">
                                        <?php if ($index === 0): ?>
                                            <i class="fas fa-trophy text-yellow-500 text-lg"></i>
                                        <?php elseif ($index === 1): ?>
                                            <i class="fas fa-medal text-gray-400 text-lg"></i>
                                        <?php elseif ($index === 2): ?>
                                            <i class="fas fa-award text-orange-600 text-lg"></i>
                                        <?php else: ?>
                                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-bold text-blue-600"><?= $index + 1 ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($category['category_name']) ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?= $category['completion_percentage'] ?>% complete • <?= $category['total_tasks'] ?>
                                            tasks
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded shadow py-6 px-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($recentActivity)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-history text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No recent activity</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($recentActivity as $activity): ?>
                                <?php
                                $createdAt = new DateTime($activity['created_at']);
                                $timeAgo = $createdAt->format('M j, H:i');
                                ?>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 mr-3">
                                        <i class="fas fa-plus-circle text-blue-500"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Created:
                                            <?= htmlspecialchars($activity['task_name']) ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?= htmlspecialchars($activity['category_name']) ?> • <?= $timeAgo ?>
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $activity['status'] === 'done' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' ?>">
                                        <?= ucfirst($activity['status']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script>
    function exportPDF() {
        // Show loading alert
        Swal.fire({
            title: 'Generating PDF...',
            text: 'Please wait while we prepare your report',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Create form and submit for PDF export
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'export_pdf';
        input.value = '1';

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    function exportExcel() {
        // Show loading alert
        Swal.fire({
            title: 'Generating Excel...',
            text: 'Please wait while we prepare your report',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Create form and submit for Excel export
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'export_excel';
        input.value = '1';

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>