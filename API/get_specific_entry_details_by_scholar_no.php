<?php

include '../debug_config.php';
include 'db_connect.php';

header('Content-Type: application/json');

// Check if the scholar number is provided
if (!isset($_REQUEST['scholar_no']) || empty($_REQUEST['scholar_no'])) {
    echo json_encode(['status' => 'error', 'message' => 'Scholar number is required.']);
    exit;
}

$scholar_no = $_REQUEST['scholar_no'];

function getEntryExitTableName($scholar_no) {
    global $db_conn; 
    $stmt = $db_conn->prepare("SELECT entry_exit_table_name FROM student_info WHERE scholar_no = ?");
    
    $stmt->bind_param("s", $scholar_no);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();     
            $stmt->close();
            return $row['entry_exit_table_name']; 
        } else {
            $stmt->close();
            return null;
        }
    } else {
        $stmt->close();
        return null; 
    }  
}

$entry_exit_table_name = getEntryExitTableName($scholar_no);
if ($entry_exit_table_name == null) {
    echo json_encode(['status' => 'success', 'entry_exit_table_name' => null, 'message' => 'Student is currently in hostel.']);
    exit;
}

$scholar_no = $db_conn->real_escape_string($_REQUEST['scholar_no']);

// Prepare the SQL query to get open_time, close_time, and the highest indexed id
$sql = "SELECT * FROM `$entry_exit_table_name`
        WHERE scholar_no = '$scholar_no' 
        ORDER BY id DESC 
        LIMIT 1";

// Execute the query
$result = $db_conn->query($sql);

// Check for errors
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $db_conn->error]);
    exit;
}

$data = $result->fetch_assoc();
$db_conn->close();

if ($data) {
    // Format the open_time and close_time
    if (isset($data['open_time'])) {
        $data['open_time'] = date('H:i:s d-m-Y', strtotime($data['open_time']));
    }
    if (isset($data['close_time'])) {
        $data['close_time'] = date('H:i:s d-m-Y', strtotime($data['close_time']));
    }

    echo json_encode(['status' => 'success', 'entry_exit_table_name' => $entry_exit_table_name, 'data' => $data]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data found for the given scholar number.']);
}

?>
