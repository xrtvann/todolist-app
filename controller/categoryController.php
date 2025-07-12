<?php
require_once '../config/database.php';
require_once '../utility/databaseUtility.php';
$connection = connectDatabase();

// Function to generate a new category ID based on the last inserted ID
function generateCategoryID()
{
    global $connection;
    $query = "SELECT id FROM category ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    $lastID =  mysqli_fetch_assoc($result);

    if ($lastID) {
        $lastNumber = (int) substr($lastID['id'], 6);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    return 'CTRGY-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
}

function store() {

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

function update() {

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

function destroy($categoryName) {

    $checkCategory = read("SELECT name FROM category WHERE name = '$categoryName'");

    if ($checkCategory === 0) {
        return false;
    }

    $result = delete('category', 'name', $categoryName);
    return $result;
}