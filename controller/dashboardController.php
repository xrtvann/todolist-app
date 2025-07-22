<?php

require_once __DIR__ . '/../utility/databaseUtility.php';
$connection = connectDatabase();

function getDashboardStats()
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [
            'totalCategories' => 0,
            'totalTasks' => 0,
            'pendingTasks' => 0,
            'completedTasks' => 0,
            'completionRate' => 0
        ];
    }

    // Get total categories
    $categoriesQuery = "SELECT COUNT(*) as total FROM category WHERE user_id = '$userId'";
    $categoriesResult = read($categoriesQuery);
    $totalCategories = $categoriesResult[0]['total'];

    // Get total tasks
    $tasksQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId'";
    $tasksResult = read($tasksQuery);
    $totalTasks = $tasksResult[0]['total'];

    // Get pending tasks
    $pendingQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND status = 'pending'";
    $pendingResult = read($pendingQuery);
    $pendingTasks = $pendingResult[0]['total'];

    // Get completed tasks
    $completedQuery = "SELECT COUNT(*) as total FROM task WHERE user_id = '$userId' AND status = 'done'";
    $completedResult = read($completedQuery);
    $completedTasks = $completedResult[0]['total'];

    // Calculate completion rate
    $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

    return [
        'totalCategories' => $totalCategories,
        'totalTasks' => $totalTasks,
        'pendingTasks' => $pendingTasks,
        'completedTasks' => $completedTasks,
        'completionRate' => $completionRate
    ];
}

function getRecentTasks($limit = 5)
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [];
    }

    $query = "SELECT task.*, category.name AS category_name 
              FROM task 
              INNER JOIN category ON task.category_id = category.id 
              WHERE task.user_id = '$userId' 
              ORDER BY task.created_at DESC 
              LIMIT $limit";

    return read($query);
}

function getCategoryProgress()
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [];
    }

    $query = "SELECT 
                c.id,
                c.name,
                COUNT(t.id) as total_tasks,
                SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) as completed_tasks,
                CASE 
                    WHEN COUNT(t.id) > 0 THEN 
                        ROUND((SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) / COUNT(t.id)) * 100, 1)
                    ELSE 0 
                END as completion_percentage
              FROM category c
              LEFT JOIN task t ON c.id = t.category_id AND t.user_id = '$userId'
              WHERE c.user_id = '$userId'
              GROUP BY c.id, c.name
              ORDER BY c.name";

    return read($query);
}

function getRecentCompletedTasks($limit = 5)
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [];
    }

    $query = "SELECT task.*, category.name AS category_name 
              FROM task 
              INNER JOIN category ON task.category_id = category.id 
              WHERE task.user_id = '$userId' AND task.status = 'done'
              ORDER BY task.created_at DESC 
              LIMIT $limit";

    return read($query);
}

function getTodayStats()
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [
            'tasksCreatedToday' => 0,
            'tasksCompletedToday' => 0
        ];
    }

    $today = date('Y-m-d');

    // Tasks created today
    $createdTodayQuery = "SELECT COUNT(*) as total FROM task 
                          WHERE user_id = '$userId' AND DATE(created_at) = '$today'";
    $createdTodayResult = read($createdTodayQuery);
    $tasksCreatedToday = $createdTodayResult[0]['total'];

    // Tasks completed today (assuming you track completion date, or use created_at for now)
    $completedTodayQuery = "SELECT COUNT(*) as total FROM task 
                            WHERE user_id = '$userId' AND status = 'done' AND DATE(created_at) = '$today'";
    $completedTodayResult = read($completedTodayQuery);
    $tasksCompletedToday = $completedTodayResult[0]['total'];

    return [
        'tasksCreatedToday' => $tasksCreatedToday,
        'tasksCompletedToday' => $tasksCompletedToday
    ];
}
