<?php

require_once __DIR__ . '/../utility/databaseUtility.php';
$connection = connectDatabase();
function generateCategoryID()
{
    global $connection;
    $query = "SELECT id FROM task ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    $lastID = mysqli_fetch_assoc($result);

    if ($lastID) {
        $lastNumber = (int) substr($lastID['id'], 6);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'TSK-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}


function show($limit = 10, $offset = 0)
{
    $query = "SELECT task.*, category.name AS category_name FROM task INNER JOIN category ON task.category_id = category.id ORDER BY task.created_at DESC LIMIT $limit, $offset";
    $tasks = read($query);
    return $tasks;
}

function store()
{

    global $connection;
    if (isset($_POST['saveTask'])) {
        $id = htmlspecialchars($_POST['TaskID']);
        $name = htmlspecialchars($_POST['TaskName']);
        $taskCategory = htmlspecialchars($_POST['taskCategory']);

        $data = [
            'id' => $id,
            'name' => $name,
            'category_id' => $taskCategory
        ];

        insert('task', $data);

        return mysqli_affected_rows($connection);

    }
}

function update()
{

    global $connection;

    if (isset($_POST['saveChangesCategory'])) {


        $categoryID = htmlspecialchars($_POST['updateCategoryID']);
        $newCategoryName = htmlspecialchars($_POST['updateCategoryName']);

        if (empty($categoryID) || empty($newCategoryName)) {
            return 0;
        }

        $checkQuery = "SELECT id FROM category WHERE name = '{$newCategoryName}' AND id != '{$categoryID}'";
        $checkExisting = read($checkQuery);

        if (!empty($checkExisting)) {
            return -1;
        }

        $data = [
            'name' => $newCategoryName
        ];

        $condition = "id = '{$categoryID}'";

        $result = edit('category', $data, $condition);

        return $result;
    }
    return 0;
}

function markDoneTask()
{
    global $connection;
    $taskId = htmlspecialchars($_POST['doneTaskID']);
    if (empty($taskId)) {
        return 0;
    }

    $escapedTaskId = mysqli_real_escape_string($connection, $taskId);
    $query = "UPDATE task SET status='done' WHERE id='{$escapedTaskId}'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        return 0; // Gagal update
    }

    return mysqli_affected_rows($connection); // Berhasil update
}

function destroy($categoryName)
{

    $checkCategory = read("SELECT name FROM category WHERE name = '$categoryName'");

    if ($checkCategory === 0) {
        return false;
    }

    $result = delete('category', 'name', $categoryName);
    return $result;
}

function searchCategories($searchTerm, $dataPerPage = 10, $currentPage = 1)
{
    global $connection;

    $searchTerm = trim($searchTerm);
    $dataPerPage = max(1, (int) $dataPerPage);
    $currentPage = max(1, (int) $currentPage);


    $escapedSearchTerm = mysqli_real_escape_string($connection, $searchTerm);

    $searchCondition = "task LIKE '%{$escapedSearchTerm}%' OR id LIKE '%{$escapedSearchTerm}%'";
    var_dump($searchCondition); // Debugging line to check the search condition

    $countQuery = "SELECT COUNT(*) as total FROM task WHERE {$searchCondition}";
    $countResult = read($countQuery);
    $totalRows = (int) $countResult[0]['total'];

    $totalPages = ceil($totalRows / $dataPerPage);
    $currentPage = min($currentPage, max(1, $totalPages)); // Ensure valid page
    $offset = ($currentPage - 1) * $dataPerPage;

    $dataQuery = "SELECT * FROM task 
                  WHERE {$searchCondition} 
                  ORDER BY created_at DESC 
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