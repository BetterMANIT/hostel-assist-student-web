<?php
include '../debug_config.php';
include_once 'db_connect.php';
header('Content-Type: application/json'); 

function getDbConnection() {
    global $db_conn;  
    return $db_conn;
}

function getHostelNameByScholarNo($scholar_no) {
    $db_conn = getDbConnection();
    $query = "SELECT hostel_name FROM student_info WHERE scholar_no = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param('s', $scholar_no);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row ? $row['hostel_name'] : null;
}

function findHostelTables($hostel_name) {
    $db_conn = getDbConnection();
    
    // Check if the connection was successful
    if ($db_conn->connect_error) {
        returnResponse('error', "Connection failed: " . $db_conn->connect_error);
        return [];
    }

    // Construct the query directly with the wildcard
    $like_hostel_name = $db_conn->real_escape_string($hostel_name) . '%';
    $query = "SHOW TABLES LIKE '$like_hostel_name'"; // Use single quotes to enclose the variable

    // Prepare the query
    $stmt = $db_conn->prepare($query);
    
    // Check if the prepare was successful
    if (!$stmt) {
        returnResponse('error', "Prepare failed: " . $db_conn->error);
        return [];
    }

    // Execute the statement
    if (!$stmt->execute()) {
        returnResponse('error', "Execution failed: " . $stmt->error);
        return [];
    }

    // Fetch the result
    $result = $stmt->get_result();
    $tables = [];
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }

    return $tables;
}

function fetchStudentDataFromTables($tables, $scholar_no) {
    $db_conn = getDbConnection();
    $studentData = [];
    
    foreach ($tables as $table) {
        $query = "SELECT * FROM `$table` WHERE scholar_no = ?";
        $stmt = $db_conn->prepare($query);
        $stmt->bind_param('s', $scholar_no);
        $stmt->execute();

        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        if (!empty($rows)) {
            $studentData[$table] = $rows;
        }
    }
    
    return $studentData;
}

function returnResponse($status, $dataOrMessage) {
    if ($status === 'success') {
        echo json_encode(['status' => 'success', 'data' => $dataOrMessage], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['status' => 'error', 'message' => $dataOrMessage], JSON_PRETTY_PRINT);
    }
}

function handleRequest() {
    $scholar_no = $_GET['scholar_no'] ?? null;

    if (!$scholar_no) {
        returnResponse('error', 'No scholar number provided.');
        return;
    }

    $hostel_name = getHostelNameByScholarNo($scholar_no);

    if (!$hostel_name) {
        returnResponse('error', 'Hostel name not found for this scholar number.');
        return;
    }

    $tables = findHostelTables($hostel_name);

    if (empty($tables)) {
        returnResponse('error', 'No tables found with the hostel name prefix.');
        return;
    }

    $studentData = fetchStudentDataFromTables($tables, $scholar_no);

    if (empty($studentData)) {
        returnResponse('error', 'No data found in any hostel-related tables.');
        return;
    }
    returnResponse('success', $studentData);
}

handleRequest();
$db_conn->close();
