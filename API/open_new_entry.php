<?php

include '../debug_config.php';
include 'db_connect.php';

date_default_timezone_set('Asia/Kolkata');

$scholar_no = $_REQUEST['scholar_no'] ?? null;
$name = $_REQUEST['name'] ?? null;
$room_no = $_REQUEST['room_no'] ?? null;
$photo_url = $_REQUEST['photo_url'] ?? null;
$phone_no = $_REQUEST['phone_no'] ?? null;
$section = $_REQUEST['section'] ?? null;
$hostel_name = $_REQUEST['hostel_name'] ?? null; 
$table_name = $_REQUEST['table_name'] ?? null; 
$purpose = $_REQUEST['purpose'] ?? null;  
$errors = [];

if (empty($scholar_no)) {
    $errors[] = 'scholar_no';
}

if (empty($name)) {
    $errors[] = 'name';
}

if (empty($hostel_name)) {
    $errors[] = 'hostel_name';
}

if (empty($table_name)) {
    $errors[] = 'table_name';
}

if (empty($purpose)) {
    $errors[] = 'purpose';
}

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => implode(', ', $errors) . ' are required.']);
    exit;
}

$create_table_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scholar_no VARCHAR(11),
    name VARCHAR(100) NOT NULL,
    room_no VARCHAR(10),
    photo_url VARCHAR(255),
    phone_no VARCHAR(15),
    section VARCHAR(50),
    open_time DATETIME,
    close_time DATETIME,
    created_by VARCHAR(255),
    purpose VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($db_conn->query($create_table_query) === FALSE) {
    echo json_encode(['status' => 'error', 'message' => 'Error creating table: ' . $db_conn->error]);
    $db_conn->close();
    exit;
}


$insert_query = "INSERT INTO `$table_name` (scholar_no, name, room_no, photo_url, phone_no, section, open_time, purpose) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$exit_time = date('Y-m-d H:i:s');  

if (updateEntryExitTableName($db_conn, $scholar_no, $table_name)) {
    if ($stmt = $db_conn->prepare($insert_query)) {
        $stmt->bind_param("ssssssss", $scholar_no, $name, $room_no, $photo_url, $phone_no, $section, $exit_time, $purpose);
        
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
