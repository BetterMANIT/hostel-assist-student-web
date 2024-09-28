<?php
include '../debug_config.php';
include 'db_connect.php';

// Set the default time zone to IST for exit time
date_default_timezone_set('Asia/Kolkata');

$scholar_no = $_REQUEST['scholar_no'] ?? null;
$name = $_REQUEST['name'] ?? null;
$room_no = $_REQUEST['room_no'] ?? null;
$photo_url = $_REQUEST['photo_url'] ?? null;
$phone_no = $_REQUEST['phone_no'] ?? null;
$section = $_REQUEST['section'] ?? null;
$hostel_name = $_REQUEST['hostel_name'] ?? null; 

// Check required fields
if (empty($scholar_no) || empty($name) || empty($hostel_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Scholar No, Name, and Hostel No are required.']);
    exit;
}

// Create table name using the current date and hostel name
$date = date('dmY'); // Format date as ddmmyyyy
$table_name = $date . $hostel_name;

// Prepare the insert query without entry_time
$insert_query = "INSERT INTO `$table_name` (scholar_no, name, room_no, photo_url, phone_no, section, open_time) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";

// Get current time in IST for exit_time
$exit_time = date('Y-m-d H:i:s'); // Format for MySQL DATETIME

// Update the entry_exit_table_name for the scholar
if (updateEntryExitTableName($db_conn, $scholar_no, $table_name)) {
    if ($stmt = $db_conn->prepare($insert_query)) {
        $stmt->bind_param("sssssss", $scholar_no, $name, $room_no, $photo_url, $phone_no, $section, $exit_time);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Record added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding record: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $db_conn->error]);
    }
}

function updateEntryExitTableName($db_conn, $scholar_no, $table_name) {
    $update_query = "UPDATE student_info SET entry_exit_table_name = ? WHERE scholar_no = ?";
    
    if ($stmt = $db_conn->prepare($update_query)) {
        $stmt->bind_param("ss", $table_name, $scholar_no);
        if ($stmt->execute()) {
            return true;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating entry_exit_table_name: ' . $stmt->error]);
            return false; 
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $db_conn->error]);
        return false; 
    }
}

$db_conn->close();
