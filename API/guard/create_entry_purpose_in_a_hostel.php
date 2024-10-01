<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
    
    // Collect parameters using $_REQUEST
    $purpose = $_REQUEST['purpose'];
    $constant_table_name = $_REQUEST['constant_table_name'];
    $variable_table_name_suffix = $_REQUEST['variable_table_name_suffix'];
    $hostel_name = $_REQUEST['hostel_name'];
    $created_by = $_REQUEST['created_by'];  
    $purpose = $_REQUEST['purpose'];  


    if (empty($constant_table_name)) {
        $response = ['status' => 'error', 'message' => 'constant_table_name is required.'];
        echo json_encode($response);
        exit();
    }
    
    if (empty($variable_table_name_suffix)) {
        $response = ['status' => 'error', 'message' => 'variable_table_name_suffix is required.'];
        echo json_encode($response);
        exit();
    }
    
    if (empty($hostel_name)) {
        $response = ['status' => 'error', 'message' => 'hostel_name is required.'];
        echo json_encode($response);
        exit();
    }
    
    if (empty($created_by)) {
        $response = ['status' => 'error', 'message' => 'created_by is required.'];
        echo json_encode($response);
        exit();
    }
    
    if (empty($purpose)) {
        $response = ['status' => 'error', 'message' => 'purpose is required.'];
        echo json_encode($response);
        exit();
    }
    

    $sql = "INSERT INTO hostel_with_purposes (purpose, constant_table_name, variable_table_name_suffix, hostel_name, created_by, is_locked)
            VALUES (?, ?, ?, ?, ?, ?, FALSE)";

    // Prepare and bind
    if ($stmt = $db_conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $purpose,$constant_table_name, $variable_table_name_suffix, $hostel_name, $created_by);

        // Execute the statement
        if ($stmt->execute()) {
            $response = ['status' => 'success', 'message' => 'Record added successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error adding record: ' . $stmt->error];
        }

        $stmt->close();
    } else {
        $response = ['status' => 'error', 'message' => 'Database preparation failed: ' . $db_conn->error];
    }
    $db_conn->close();

    echo json_encode($response);
}
?>
