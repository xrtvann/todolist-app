<?php

require_once '../config/database.php';
$connection = connectDatabase();

// Function fetching all data from the database
function read($query) {
    global $connection;

    $result = mysqli_query($connection, mysqli_real_escape_string($connection, $query));
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Function to store data in the database
function insert($table, $data) {
    global $connection;

    $columns = implode(', ', array_keys($data));
    $values = [];

    foreach($data as $value) {
        $values[] = "'". mysqli_real_escape_string($connection, $value) ."'";    
    }
    $values = implode(', ', $values);

    $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return $result;
}

