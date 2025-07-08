<?php
require_once '../config/database.php';
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
