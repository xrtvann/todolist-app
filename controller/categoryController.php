<?php

require_once __DIR__ . '/../utility/databaseUtility.php';
$connection = connectDatabase();

// Function to generate a new category ID based on the last inserted ID
function generateCategoryID()
{
    global $connection;
    $query = "SELECT id FROM category ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    $lastID = mysqli_fetch_assoc($result);

    if ($lastID) {
        $lastNumber = (int) substr($lastID['id'], 6);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'CTRGY-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}



function show($limit = 10, $offset = 0)
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return [];
    }

    $query = "SELECT * FROM category WHERE user_id = '$userId' ORDER BY created_at DESC LIMIT $offset, $limit";
    $categories = read($query);
    return $categories;
}

function store()
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return 0;
    }

    if (isset($_POST['saveCategory'])) {
        $id = htmlspecialchars($_POST['categoryID']);
        $name = htmlspecialchars($_POST['categoryName']);

        $data = [
            'id' => $id,
            'name' => $name,
            'user_id' => $userId
        ];

        insert('category', $data);

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

    if (isset($_POST['saveChangesCategory'])) {
        $categoryID = htmlspecialchars($_POST['updateCategoryID']);
        $newCategoryName = htmlspecialchars($_POST['updateCategoryName']);

        if (empty($categoryID) || empty($newCategoryName)) {
            return 0;
        }

        // Check if category belongs to current user and if name already exists for this user
        $checkQuery = "SELECT id FROM category WHERE name = '{$newCategoryName}' AND user_id = '{$userId}' AND id != '{$categoryID}'";
        $checkExisting = read($checkQuery);

        if (!empty($checkExisting)) {
            return -1;
        }

        // Verify category belongs to current user before updating
        $ownershipQuery = "SELECT id FROM category WHERE id = '{$categoryID}' AND user_id = '{$userId}'";
        $ownershipCheck = read($ownershipQuery);

        if (empty($ownershipCheck)) {
            return 0; // Category doesn't belong to user
        }

        $data = [
            'name' => $newCategoryName
        ];

        $condition = "id = '{$categoryID}' AND user_id = '{$userId}'";

        $result = edit('category', $data, $condition);

        return $result;
    }
    return 0;
}

function destroy($categoryID)
{
    $userId = getCurrentUserId();
    if (!$userId) {
        return false;
    }

    // Check if category belongs to current user
    $checkID = read("SELECT id FROM category WHERE id = '$categoryID' AND user_id = '$userId'");

    if (empty($checkID)) {
        return false;
    }

    $result = delete('category', 'id', $categoryID);
    return $result;
}

function searchCategories($searchTerm, $dataPerPage = 10, $currentPage = 1)
{
    global $connection;
    $userId = getCurrentUserId();
    if (!$userId) {
        return [
            'categories' => [],
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

    $searchCondition = "(name LIKE '%{$escapedSearchTerm}%' OR id LIKE '%{$escapedSearchTerm}%') AND user_id = '{$userId}'";

    $countQuery = "SELECT COUNT(*) as total FROM category WHERE {$searchCondition}";
    $countResult = read($countQuery);
    $totalRows = (int) $countResult[0]['total'];

    $totalPages = ceil($totalRows / $dataPerPage);
    $currentPage = min($currentPage, max(1, $totalPages)); // Ensure valid page
    $offset = ($currentPage - 1) * $dataPerPage;

    $dataQuery = "SELECT * FROM category 
                  WHERE {$searchCondition} 
                  ORDER BY created_at DESC 
                  LIMIT {$dataPerPage} OFFSET {$offset}";

    $categories = read($dataQuery);

    return [
        'categories' => $categories,
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