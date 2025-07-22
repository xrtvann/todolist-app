<?php
// Import database utility
require_once '../utility/databaseUtility.php';

// Check if session is not started, then start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connection = connectDatabase();

/**
 * Get comprehensive statistics for reports
 */
function getReportStats()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [
            'totalCategories' => 0,
            'totalTasks' => 0,
            'completedTasks' => 0,
            'pendingTasks' => 0,
            'completionRate' => 0,
            'tasksThisMonth' => 0,
            'tasksCompletedThisMonth' => 0,
            'averageTasksPerCategory' => 0
        ];
    }

    // Get total categories
    $categoriesQuery = "SELECT COUNT(*) as total FROM category WHERE user_id = '$userId'";
    $categoriesResult = read($categoriesQuery);
    $totalCategories = $categoriesResult[0]['total'] ?? 0;

    // Get total tasks
    $tasksQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId'";
    $tasksResult = read($tasksQuery);
    $totalTasks = $tasksResult[0]['total'] ?? 0;

    // Get completed tasks
    $completedQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND status = 'done'";
    $completedResult = read($completedQuery);
    $completedTasks = $completedResult[0]['total'] ?? 0;

    // Get pending tasks
    $pendingTasks = $totalTasks - $completedTasks;

    // Calculate completion rate
    $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

    // Get tasks created this month
    $thisMonthQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    $thisMonthResult = read($thisMonthQuery);
    $tasksThisMonth = $thisMonthResult[0]['total'] ?? 0;

    // Get tasks completed this month
    $completedThisMonthQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND status = 'done' AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    $completedThisMonthResult = read($completedThisMonthQuery);
    $tasksCompletedThisMonth = $completedThisMonthResult[0]['total'] ?? 0;

    // Calculate average tasks per category
    $averageTasksPerCategory = $totalCategories > 0 ? round($totalTasks / $totalCategories, 1) : 0;

    return [
        'totalCategories' => $totalCategories,
        'totalTasks' => $totalTasks,
        'completedTasks' => $completedTasks,
        'pendingTasks' => $pendingTasks,
        'completionRate' => $completionRate,
        'tasksThisMonth' => $tasksThisMonth,
        'tasksCompletedThisMonth' => $tasksCompletedThisMonth,
        'averageTasksPerCategory' => $averageTasksPerCategory
    ];
}

/**
 * Get category-wise task breakdown
 */
function getCategoryBreakdown()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [];
    }

    $query = "SELECT 
                c.id as category_id,
                c.name as category_name,
                COUNT(t.id) as total_tasks,
                SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) as completed_tasks,
                SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
                CASE 
                    WHEN COUNT(t.id) > 0 THEN ROUND((SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 1)
                    ELSE 0 
                END as completion_percentage
              FROM category c
              LEFT JOIN task t ON c.id = t.category_id AND t.user_id = '$userId'
              WHERE c.user_id = '$userId'
              GROUP BY c.id, c.name
              ORDER BY total_tasks DESC";

    return read($query);
}

/**
 * Get monthly task creation trend (last 6 months)
 */
function getMonthlyTrend()
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [];
    }

    $query = "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                DATE_FORMAT(created_at, '%M %Y') as month_name,
                COUNT(*) as tasks_created,
                SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as tasks_completed
              FROM task 
              WHERE user_id = '$userId' 
                AND created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
              GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%M %Y')
              ORDER BY month DESC";

    return read($query);
}

/**
 * Get top performing categories (by completion rate)
 */
function getTopCategories($limit = 5)
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [];
    }

    $query = "SELECT 
                c.id as category_id,
                c.name as category_name,
                COUNT(t.id) as total_tasks,
                SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) as completed_tasks,
                CASE 
                    WHEN COUNT(t.id) > 0 THEN ROUND((SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 1)
                    ELSE 0 
                END as completion_percentage
              FROM category c
              LEFT JOIN task t ON c.id = t.category_id AND t.user_id = '$userId'
              WHERE c.user_id = '$userId' 
              GROUP BY c.id, c.name
              HAVING total_tasks > 0
              ORDER BY completion_percentage DESC, total_tasks DESC
              LIMIT $limit";

    return read($query);
}

/**
 * Get recent activity (last 10 actions)
 */
function getRecentActivity($limit = 10)
{
    global $connection;
    $userId = getCurrentUserId();

    if (!$userId) {
        return [];
    }

    $query = "SELECT 
                t.name as task_name,
                t.status,
                c.name as category_name,
                t.created_at,
                'task_created' as action_type
              FROM task t
              LEFT JOIN category c ON t.category_id = c.id
              WHERE t.user_id = '$userId'
              ORDER BY t.created_at DESC
              LIMIT $limit";

    return read($query);
}

/**
 * Export report data to PDF
 */
function exportToPDF()
{
    require_once '../vendor/autoload.php';

    // Get report data
    $stats = getReportStats();
    $categoryBreakdown = getCategoryBreakdown();
    $monthlyTrend = getMonthlyTrend();
    $topCategories = getTopCategories();

    // Get user info
    $currentFullName = getCurrentFullName();
    $currentUsername = getCurrentUsername();
    $displayName = $currentFullName ? $currentFullName : $currentUsername;

    // Create new PDF instance
    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4',
        'margin_left' => 20,
        'margin_right' => 20,
        'margin_top' => 30,
        'margin_bottom' => 20,
    ]);

    // Set document properties
    $mpdf->SetTitle('Task Management Report');
    $mpdf->SetAuthor($displayName);
    $mpdf->SetSubject('Productivity Report');

    // Start building HTML content
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .header { text-align: center; margin-bottom: 30px; }
            .title { font-size: 24px; font-weight: bold; color: #333; }
            .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
            .stats-grid { display: table; width: 100%; margin-bottom: 30px; }
            .stat-card { display: table-cell; width: 25%; padding: 15px; text-align: center; border: 1px solid #ddd; }
            .stat-number { font-size: 28px; font-weight: bold; color: #2563eb; }
            .stat-label { font-size: 12px; color: #666; margin-top: 5px; }
            .section { margin-bottom: 30px; }
            .section-title { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 15px; border-bottom: 2px solid #2563eb; padding-bottom: 5px; }
            .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .table th, .table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            .table th { background-color: #f8f9fa; font-weight: bold; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        </style>
    </head>
    <body>';

    // Header
    $html .= '<div class="header">
        <div class="title">Task Management Report</div>
        <div class="subtitle">Generated for ' . htmlspecialchars($displayName) . ' on ' . date('F j, Y') . '</div>
    </div>';

    // Stats Cards
    $html .= '<div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">' . $stats['totalTasks'] . '</div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">' . $stats['completionRate'] . '%</div>
            <div class="stat-label">Completion Rate</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">' . $stats['tasksThisMonth'] . '</div>
            <div class="stat-label">Tasks This Month</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">' . $stats['averageTasksPerCategory'] . '</div>
            <div class="stat-label">Avg per Category</div>
        </div>
    </div>';

    // Category Breakdown Section
    $html .= '<div class="section">
        <div class="section-title">Category Performance</div>';

    if (!empty($categoryBreakdown)) {
        $html .= '<table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-center">Total Tasks</th>
                    <th class="text-center">Completed</th>
                    <th class="text-center">Pending</th>
                    <th class="text-center">Completion Rate</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($categoryBreakdown as $category) {
            $html .= '<tr>
                <td>' . htmlspecialchars($category['category_name']) . '</td>
                <td class="text-center">' . $category['total_tasks'] . '</td>
                <td class="text-center">' . $category['completed_tasks'] . '</td>
                <td class="text-center">' . $category['pending_tasks'] . '</td>
                <td class="text-center">' . ($category['completion_percentage'] ?? 0) . '%</td>
            </tr>';
        }

        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="text-center">No categories found.</p>';
    }

    $html .= '</div>';

    // Monthly Trend Section
    $html .= '<div class="section">
        <div class="section-title">Monthly Trend (Last 6 Months)</div>';

    if (!empty($monthlyTrend)) {
        $html .= '<table class="table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-center">Tasks Created</th>
                    <th class="text-center">Tasks Completed</th>
                    <th class="text-center">Completion Rate</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($monthlyTrend as $month) {
            $monthlyRate = $month['tasks_created'] > 0 ? round(($month['tasks_completed'] / $month['tasks_created']) * 100, 1) : 0;
            $html .= '<tr>
                <td>' . htmlspecialchars($month['month_name']) . '</td>
                <td class="text-center">' . $month['tasks_created'] . '</td>
                <td class="text-center">' . $month['tasks_completed'] . '</td>
                <td class="text-center">' . $monthlyRate . '%</td>
            </tr>';
        }

        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="text-center">No monthly data available.</p>';
    }

    $html .= '</div>';

    // Top Categories Section
    $html .= '<div class="section">
        <div class="section-title">Top Performing Categories</div>';

    if (!empty($topCategories)) {
        $html .= '<table class="table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Category</th>
                    <th class="text-center">Total Tasks</th>
                    <th class="text-center">Completion Rate</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($topCategories as $index => $category) {
            $html .= '<tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars($category['category_name']) . '</td>
                <td class="text-center">' . $category['total_tasks'] . '</td>
                <td class="text-center">' . ($category['completion_percentage'] ?? 0) . '%</td>
            </tr>';
        }

        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="text-center">No completed categories found.</p>';
    }

    $html .= '</div>';

    // Footer
    $html .= '<div style="margin-top: 50px; text-align: center; font-size: 10px; color: #666;">
        <p>Report generated on ' . date('F j, Y \a\t g:i A') . '</p>
        <p>Task Management System - Productivity Report</p>
    </div>';

    $html .= '</body></html>';

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Output PDF - check if preview mode
    $filename = 'Task_Report_' . date('Y-m-d_H-i-s') . '.pdf';
    $mode = isset($_POST['preview_mode']) && $_POST['preview_mode'] == '1' ? 'I' : 'D';
    $mpdf->Output($filename, $mode); // 'I' for inline preview, 'D' for download
}

/**
 * Export report data to Excel
 */
function exportToExcel()
{
    require_once '../vendor/autoload.php';

    // Get report data
    $stats = getReportStats();
    $categoryBreakdown = getCategoryBreakdown();
    $monthlyTrend = getMonthlyTrend();
    $topCategories = getTopCategories();

    // Get user info
    $currentFullName = getCurrentFullName();
    $currentUsername = getCurrentUsername();
    $displayName = $currentFullName ? $currentFullName : $currentUsername;

    // Create new Spreadsheet object
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Set document properties
    $spreadsheet->getProperties()
        ->setCreator($displayName)
        ->setLastModifiedBy($displayName)
        ->setTitle('Task Management Report')
        ->setSubject('Productivity Report')
        ->setDescription('Comprehensive task management and productivity report')
        ->setKeywords('tasks productivity report')
        ->setCategory('Reports');

    // === OVERVIEW SHEET ===
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Overview');

    // Header
    $sheet->setCellValue('A1', 'Task Management Report');
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getFont()->setSize(18)->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A2', 'Generated for: ' . $displayName);
    $sheet->mergeCells('A2:E2');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'Date: ' . date('F j, Y'));
    $sheet->mergeCells('A3:E3');
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Key Metrics
    $sheet->setCellValue('A5', 'Key Metrics');
    $sheet->getStyle('A5')->getFont()->setSize(14)->setBold(true);

    $sheet->setCellValue('A6', 'Metric');
    $sheet->setCellValue('B6', 'Value');
    $sheet->getStyle('A6:B6')->getFont()->setBold(true);
    $sheet->getStyle('A6:B6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');

    $metricsData = [
        ['Total Tasks', $stats['totalTasks']],
        ['Total Categories', $stats['totalCategories']],
        ['Completed Tasks', $stats['completedTasks']],
        ['Pending Tasks', $stats['pendingTasks']],
        ['Completion Rate', $stats['completionRate'] . '%'],
        ['Tasks This Month', $stats['tasksThisMonth']],
        ['Tasks Completed This Month', $stats['tasksCompletedThisMonth']],
        ['Average Tasks per Category', $stats['averageTasksPerCategory']]
    ];

    $row = 7;
    foreach ($metricsData as $metric) {
        $sheet->setCellValue('A' . $row, $metric[0]);
        $sheet->setCellValue('B' . $row, $metric[1]);
        $row++;
    }

    // Auto-fit columns
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);

    // === CATEGORY BREAKDOWN SHEET ===
    $categorySheet = $spreadsheet->createSheet();
    $categorySheet->setTitle('Category Breakdown');

    // Header
    $categorySheet->setCellValue('A1', 'Category Performance');
    $categorySheet->mergeCells('A1:E1');
    $categorySheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
    $categorySheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Column headers
    $categorySheet->setCellValue('A3', 'Category Name');
    $categorySheet->setCellValue('B3', 'Total Tasks');
    $categorySheet->setCellValue('C3', 'Completed Tasks');
    $categorySheet->setCellValue('D3', 'Pending Tasks');
    $categorySheet->setCellValue('E3', 'Completion Rate (%)');

    $categorySheet->getStyle('A3:E3')->getFont()->setBold(true);
    $categorySheet->getStyle('A3:E3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');

    // Category data
    if (!empty($categoryBreakdown)) {
        $row = 4;
        foreach ($categoryBreakdown as $category) {
            $categorySheet->setCellValue('A' . $row, $category['category_name']);
            $categorySheet->setCellValue('B' . $row, $category['total_tasks']);
            $categorySheet->setCellValue('C' . $row, $category['completed_tasks']);
            $categorySheet->setCellValue('D' . $row, $category['pending_tasks']);
            $categorySheet->setCellValue('E' . $row, $category['completion_percentage'] ?? 0);
            $row++;
        }
    }

    // Auto-fit columns
    foreach (range('A', 'E') as $col) {
        $categorySheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Set active sheet back to overview
    $spreadsheet->setActiveSheetIndex(0);

    // Create writer and output file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    // Check if preview mode (show HTML preview instead of downloading Excel)
    if (isset($_POST['preview_mode']) && $_POST['preview_mode'] == '1') {
        // Generate HTML preview of Excel content
        exportExcelPreview($stats, $categoryBreakdown, $monthlyTrend, $topCategories);
        return;
    }

    // Set headers for download
    $filename = 'Task_Report_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
}

/**
 * Generate HTML preview of Excel content
 */
function exportExcelPreview($stats, $categoryBreakdown, $monthlyTrend, $topCategories)
{
    // Get user info
    $currentFullName = getCurrentFullName();
    $currentUsername = getCurrentUsername();
    $displayName = $currentFullName ? $currentFullName : $currentUsername;

    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Excel Report Preview</title>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .preview-container { max-width: 1200px; margin: 0 auto; }
            .sheet-preview { background: white; margin-bottom: 30px; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .sheet-title { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #007bff; }
            .excel-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .excel-table th, .excel-table td { padding: 8px 12px; text-align: left; border: 1px solid #ddd; }
            .excel-table th { background-color: #e2e8f0; font-weight: bold; }
            .excel-table tr:nth-child(even) { background-color: #f8f9fa; }
            .header-row { font-size: 16px; font-weight: bold; text-align: center; background-color: #f0f0f0; }
            .metric-label { font-weight: bold; }
            .text-center { text-align: center; }
            .download-section { text-align: center; margin: 30px 0; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .download-btn { display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 0 10px; }
            .download-btn:hover { background: #218838; }
            .cancel-btn { display: inline-block; padding: 12px 24px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 0 10px; }
            .cancel-btn:hover { background: #545b62; }
        </style>
    </head>
    <body>
        <div class="preview-container">
            <h1 class="text-center">Excel Report Preview</h1>
            <p class="text-center">This preview shows what your Excel file will contain</p>
            
            <!-- Overview Sheet Preview -->
            <div class="sheet-preview">
                <div class="sheet-title">📊 Sheet 1: Overview</div>
                
                <table class="excel-table">
                    <tr class="header-row">
                        <td colspan="2">Task Management Report</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">Generated for: ' . htmlspecialchars($displayName) . '</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">Date: ' . date('F j, Y') . '</td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <th colspan="2">Key Metrics</th>
                    </tr>
                    <tr>
                        <th>Metric</th>
                        <th>Value</th>
                    </tr>';

    $metricsData = [
        ['Total Tasks', $stats['totalTasks']],
        ['Total Categories', $stats['totalCategories']],
        ['Completed Tasks', $stats['completedTasks']],
        ['Pending Tasks', $stats['pendingTasks']],
        ['Completion Rate', $stats['completionRate'] . '%'],
        ['Tasks This Month', $stats['tasksThisMonth']],
        ['Tasks Completed This Month', $stats['tasksCompletedThisMonth']],
        ['Average Tasks per Category', $stats['averageTasksPerCategory']]
    ];

    foreach ($metricsData as $metric) {
        echo '<tr>
            <td class="metric-label">' . htmlspecialchars($metric[0]) . '</td>
            <td>' . htmlspecialchars($metric[1]) . '</td>
        </tr>';
    }

    echo '</table>
            </div>
            
            <!-- Category Breakdown Sheet Preview -->
            <div class="sheet-preview">
                <div class="sheet-title">📁 Sheet 2: Category Breakdown</div>
                
                <table class="excel-table">
                    <tr class="header-row">
                        <td colspan="5">Category Performance</td>
                    </tr>
                    <tr><td colspan="5"></td></tr>
                    <tr>
                        <th>Category Name</th>
                        <th>Total Tasks</th>
                        <th>Completed Tasks</th>
                        <th>Pending Tasks</th>
                        <th>Completion Rate (%)</th>
                    </tr>';

    if (!empty($categoryBreakdown)) {
        foreach ($categoryBreakdown as $category) {
            echo '<tr>
                <td>' . htmlspecialchars($category['category_name']) . '</td>
                <td class="text-center">' . $category['total_tasks'] . '</td>
                <td class="text-center">' . $category['completed_tasks'] . '</td>
                <td class="text-center">' . $category['pending_tasks'] . '</td>
                <td class="text-center">' . ($category['completion_percentage'] ?? 0) . '</td>
            </tr>';
        }
    } else {
        echo '<tr><td colspan="5" class="text-center">No categories found</td></tr>';
    }

    echo '</table>
            </div>
            
            <!-- Download Section -->
            <div class="download-section">
                <h3>Ready to Download?</h3>
                <p>The Excel file will contain all the data shown above in a professional spreadsheet format with multiple sheets.</p>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="export_excel" value="1">
                    <button type="submit" class="download-btn">📥 Download Excel File</button>
                </form>
                <a href="?page=report" class="cancel-btn">❌ Cancel</a>
            </div>
        </div>
    </body>
    </html>';
}
?>