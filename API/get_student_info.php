<?php

// include '../debug_config.php';
include 'db_connect.php';
require 'authentication.php';

header('Content-Type: application/json'); 
$scholar_no = $_REQUEST['scholar_no'] ?? null;

if ($scholar_no) {
    $stmt = $db_conn->prepare("SELECT * FROM student_info WHERE scholar_no = ?");
    $stmt->bind_param("s", $scholar_no);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            //prevents sending sensitive data
            unset($row['device_id'], $row['token']);
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No student found with Scholar No.: ' . $scholar_no]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $db_conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Scholar No. is missing from the request.']);
}
?>
