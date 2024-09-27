<?php
include '/debug_config.php';
include 'db_connect.php';

$scholar_no = $_REQUEST['scholar_no'] ?? null;
$name = $_REQUEST['name'] ?? null;
$room_no = $_REQUEST['room_no'] ?? null;
$photo_url = $_REQUEST['photo_url'] ?? null;
$phone_no = $_REQUEST['phone_no'] ?? null;
$section = $_REQUEST['section'] ?? null;
$guardian_no = $_REQUEST['guardian_no'] ?? null;
$hostel_no = $_REQUEST['hostel_no'] ?? null; // Hostel No parameter

if (empty($scholar_no) || empty($name) || empty($hostel_no)) {
    echo json_encode(['status' => 'error', 'message' => 'Scholar No, Name, and Hostel No are required.']);
    exit;
}

// Generate the table name using the current date and hostel number
$date = date('dmY'); // Format date as ddmmyyyy
$table_name = $date . $hostel_no;

// SQL query to insert data into the specified table
$insert_query = "INSERT INTO `$table_name` (scholar_no, name, room_no, photo_url, phone_no, section, guardian_no) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";

// Prepare statement
if ($stmt = $db_conn->prepare($insert_query)) {
    // Bind parameters
    $stmt->bind_param("sssssss", $scholar_no, $name, $room_no, $photo_url, $phone_no, $section, $guardian_no);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Record added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding record: ' . $stmt->error]);
    }

    // Close statement
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $db_conn->error]);
}

// Close the connection
$db_conn->close();
