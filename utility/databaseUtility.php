<?php

require_once '../config/database.php';
$connection = connectDatabase();

// Function fetching all data from the database
function read($query) {
    global $connection;

    $result = mysqli_query($connection, $query);
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

function edit($table, $data, $condition) {
    global $connection;
    
    $setParts = [];
    foreach($data as $column => $value) {
        $escapedColumn = mysqli_real_escape_string($connection, $column);
        $escapedValue = mysqli_real_escape_string($connection, $value);
        $setParts[] = "{$escapedColumn} = '{$escapedValue}'";
    }

    $set = implode(', ', $setParts);
    $query = "UPDATE {$table} SET {$set} WHERE {$condition}";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return mysqli_affected_rows($connection);
}

function delete($table, $field, $value) {
    global $connection;

    $escapedField = mysqli_real_escape_string($connection, $field);
    $escapedValue = mysqli_real_escape_string($connection, $value);

    $query = "DELETE FROM {$table} WHERE {$escapedField} = '{$escapedValue}'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
    return mysqli_affected_rows($connection);
}
