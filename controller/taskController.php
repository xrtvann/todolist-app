<?php

require_once __DIR__ . '/../utility/databaseUtility.php';
$connection = connectDatabase();
function generateTaskID()
{
    global $connection;
    $query = "SELECT id FROM task ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    $lastID = mysqli_fetch_assoc($result);

    if ($lastID) {
        $lastNumber = (int) substr($lastID['id'], 4);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'TSK-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}


function show($limit = 10, $offset = 0)
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
              LIMIT $offset, $limit";
    $tasks = read($query);
    return $tasks;
}

function store()
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return 0;
    }

    if (isset($_POST['saveTask'])) {
        $id = htmlspecialchars($_POST['taskID']);
        $name = htmlspecialchars($_POST['taskName']);
        $taskCategory = htmlspecialchars($_POST['taskCategory']);

        // Verify that the category belongs to the current user
        $categoryCheck = read("SELECT id FROM category WHERE id = '$taskCategory' AND user_id = '$userId'");
        if (empty($categoryCheck)) {
            return 0; // Category doesn't belong to user
        }

        $data = [
            'id' => $id,
            'name' => $name,
            'category_id' => $taskCategory,
            'user_id' => $userId
        ];

        insert('task', $data);

        return mysqli_affected_rows($connection);
    }
}

function update()
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return 0;
    }

    if (isset($_POST['saveChangesTask'])) {
        $taskID = htmlspecialchars($_POST['updateTaskID']);
        $newTaskName = htmlspecialchars($_POST['updateTaskName']);
        $newTaskCategory = htmlspecialchars($_POST['updateTaskCategory']);

        if (empty($taskID) || empty($newTaskName) || empty($newTaskCategory)) {
            return 0;
        }

        // Verify task belongs to current user
        $taskCheck = read("SELECT id FROM task WHERE id = '$taskID' AND user_id = '$userId'");
        if (empty($taskCheck)) {
            return 0; // Task doesn't belong to user
        }

        // Verify category belongs to current user
        $categoryCheck = read("SELECT id FROM category WHERE id = '$newTaskCategory' AND user_id = '$userId'");
        if (empty($categoryCheck)) {
            return 0; // Category doesn't belong to user
        }

        $data = [
            'name' => $newTaskName,
            'category_id' => $newTaskCategory
        ];

        $condition = "id = '{$taskID}' AND user_id = '{$userId}'";

        $result = edit('task', $data, $condition);

        return $result;
    }
    return 0;
}

function markDoneTask()
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return 0;
    }

    $taskId = htmlspecialchars($_POST['doneTaskID']);
    if (empty($taskId)) {
        return 0;
    }

    $escapedTaskId = mysqli_real_escape_string($connection, $taskId);
    $query = "UPDATE task SET status='done' WHERE id='{$escapedTaskId}' AND user_id='{$userId}'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return 0; // Gagal update
    }

    return mysqli_affected_rows($connection); // Berhasil update
}

function destroy($taskID)
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return false;
    }

    // Check if task belongs to current user
    $checkID = read("SELECT id FROM task WHERE id = '$taskID' AND user_id = '$userId'");

    if (empty($checkID)) {
        return false;
    }

    $result = delete('task', 'id', $taskID);
    return $result;
}

function searchTask($searchTerm, $dataPerPage = 10, $currentPage = 1)
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return [
            'tasks' => [],
            'pagination' => [
                'totalRows' => 0,
                'totalPages' => 0,
                'currentPage' => 1,
                'offset' => 0,
                'dataPerPage' => $dataPerPage,
                'amountOfData' => 0,
                'amountOfPage' => 0
            ],
            'searchTerm' => $searchTerm
        ];
    }

    $searchTerm = trim($searchTerm);
    $dataPerPage = max(1, (int) $dataPerPage);
    $currentPage = max(1, (int) $currentPage);

    $escapedSearchTerm = mysqli_real_escape_string($connection, $searchTerm);

    $searchCondition = "(task.name LIKE '%{$escapedSearchTerm}%' 
                        OR category.name LIKE '%{$escapedSearchTerm}%') 
                        AND task.user_id = '{$userId}'";

    $countQuery = "SELECT COUNT(*) as total FROM task INNER JOIN category ON task.category_id = category.id WHERE {$searchCondition}";
    $countResult = read($countQuery);
    $totalRows = (int) $countResult[0]['total'];

    $totalPages = ceil($totalRows / $dataPerPage);
    $currentPage = min($currentPage, max(1, $totalPages)); // Ensure valid page
    $offset = ($currentPage - 1) * $dataPerPage;

    $dataQuery = "SELECT task.*,
     category.name AS category_name 
    FROM task 
    INNER JOIN category ON task.category_id = category.id 
                  WHERE {$searchCondition} 
                  ORDER BY task.created_at DESC 
                  LIMIT {$dataPerPage} OFFSET {$offset}";

    $tasks = read($dataQuery);

    // ✅ RETURN: Structured data sama seperti showWithPagination
    return [
        'tasks' => $tasks,
        'pagination' => [
            'totalRows' => $totalRows,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'offset' => $offset,
            'dataPerPage' => $dataPerPage,
            'amountOfData' => $totalRows, // Untuk compatibility dengan existing code
            'amountOfPage' => $totalPages
        ],
        'searchTerm' => $searchTerm
    ];
}