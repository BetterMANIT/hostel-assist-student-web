<?php
include '../debug_config.php';
require_once 'db_connect.php'; 

header('Content-Type: application/json'); 

$response = []; 

function sendErrorResponse($message, $db_conn = null) {
    if ($db_conn) {
        $errorResponse = ['status' => 'error', 'message' =>  $db_conn->error];
    }else{
        $errorResponse = ['status' => 'error', 'message' => $message];

    }
    echo json_encode($errorResponse);
    exit();
}

if (!isset($_REQUEST['scholar_no'])) {
    sendErrorResponse('Scholar number not provided.');
}

$scholar_no = $_REQUEST['scholar_no'];

$query = "SELECT entry_exit_table_name FROM student_info WHERE scholar_no = ?";
$stmt = $db_conn->prepare($query);

if (!$stmt) {
    sendErrorResponse('Error preparing statement.', $db_conn); // Send error with logging
}

$stmt->bind_param("s", $scholar_no);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $stmt->close();
    sendErrorResponse('Scholar number not found.');
}

$stmt->bind_result($entry_exit_table_name);
$stmt->fetch();
$stmt->close();

if (is_null($entry_exit_table_name)) {
    sendErrorResponse('Entry does not exist.');
}

$entry_exit_table_name = "`" . $db_conn->real_escape_string($entry_exit_table_name) . "`";

$currentISTTime = new DateTime("now", new DateTimeZone('Asia/Kolkata')); // Get current time in IST
$entryTimeFormatted = $currentISTTime->format('Y-m-d H:i:s'); // Format it for MySQL

$updateQuery = "UPDATE $entry_exit_table_name SET close_time = ? WHERE scholar_no = ? AND close_time IS NULL";
$updateStmt = $db_conn->prepare($updateQuery);

if (!$updateStmt) {
    sendErrorResponse('Error preparing statement for entry time update.', $db_conn); // Send error with logging
}

$updateStmt->bind_param("ss", $entryTimeFormatted, $scholar_no);

if ($updateStmt->execute()) {
    // Step 4: Reset entry_exit_table_name to NULL in student_info
    $nullifyQuery = "UPDATE student_info SET entry_exit_table_name = NULL WHERE scholar_no = ?";
    $nullifyStmt = $db_conn->prepare($nullifyQuery);

    if ($nullifyStmt) {
        $nullifyStmt->bind_param("s", $scholar_no);
        $nullifyStmt->execute();
        $nullifyStmt->close();

        $response['status'] = 'success';
        $response['close_time'] = $entryTimeFormatted;
        $response['message'] = 'entry time updated and entry_exit_table_name reset to NULL.';
    } else {
        sendErrorResponse('Entry time updated, but failed to reset entry_exit_table_name.', $db_conn); 
    }
} else {
    sendErrorResponse('Failed to update entry time.', $db_conn); 
}

$updateStmt->close();
$db_conn->close();

echo json_encode($response);