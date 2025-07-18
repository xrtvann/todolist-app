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
    $query = "SELECT * FROM category ORDER BY created_at DESC LIMIT $limit, $offset";
    $categories = read($query);
    return $categories;
}

function store()
{

    global $connection;
    if (isset($_POST['saveCategory'])) {
        $id = htmlspecialchars($_POST['categoryID']);
        $name = htmlspecialchars($_POST['categoryName']);

        $data = [
            'id' => $id,
            'name' => $name
        ];

        insert('category', $data);

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

function destroy($categoryID)
{

    $checkID = read("SELECT id FROM category WHERE id = '$categoryID'");

    if ($checkID === 0) {
        return false;
    }

    $result = delete('category', 'id', $categoryID);
    return $result;
}

function searchCategories($searchTerm, $dataPerPage = 10, $currentPage = 1)
{
    global $connection;

    $searchTerm = trim($searchTerm);
    $dataPerPage = max(1, (int) $dataPerPage);
    $currentPage = max(1, (int) $currentPage);


    $escapedSearchTerm = mysqli_real_escape_string($connection, $searchTerm);

 
    $searchCondition = "name LIKE '%{$escapedSearchTerm}%' OR id LIKE '%{$escapedSearchTerm}%'";

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