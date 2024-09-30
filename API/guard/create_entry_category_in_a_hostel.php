<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
    
    // Collect parameters using $_REQUEST
    $constant_table_name = $_REQUEST['constant_table_name'];
    $variable_table_name_suffix = $_REQUEST['variable_table_name_suffix'];
    $hostel_name = $_REQUEST['hostel_name'];
    $created_by = $_REQUEST['created_by'];  
    
    // Validate the input to ensure no empty fields
    if (empty($constant_table_name) || empty($variable_table_name_suffix) || empty($hostel_name) || empty($created_by)) {
        $response = ['status' => 'error', 'message' => 'constant_tables_name, variable_table_name_suffix, hostel_name, created_by are the params that are required.'];
        echo json_encode($response);
        exit();
    }

    // SQL query to insert data into hostel_with_categories table
    $sql = "INSERT INTO hostel_with_categories (constant_table_name, variable_table_name_suffix, hostel_name, created_by, is_locked)
            VALUES (?, ?, ?, ?, FALSE)";

    // Prepare and bind
    if ($stmt = $db_conn->prepare($sql)) {
        $stmt->bind_param("ssss", $constant_table_name, $variable_table_name_suffix, $hostel_name, $created_by);

        // Execute the statement
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Record added successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error adding record: ' . $stmt->error];
        }

        // Close the statement
        $stmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Database preparation failed: ' . $db_conn->error];
    }

    // Close the database connection
    $db_conn->close();

    echo json_encode($response);
}
?>
