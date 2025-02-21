<?php
include '../debug_config.php';
include_once 'db_connect.php';
header('Content-Type: application/json'); 
require 'authentication.php';

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
function getAllHostelSuffixes($hostel_name) {
    $db_conn = getDbConnection();
    $query = "SELECT variable_table_name_suffix FROM hostel_with_purposes WHERE hostel_name = ?";
    $stmt = $db_conn->prepare($query);
    $stmt->bind_param('s', $hostel_name);
    $stmt->execute();

    $result = $stmt->get_result();
    $suffixes = [];
    while ($row = $result->fetch_assoc()) {
        $suffixes[] = $row['variable_table_name_suffix'];
    }

    return $suffixes;
}

function findHostelTables($hostel_name) {
    $db_conn = getDbConnection();
        $suffixes = getAllHostelSuffixes($hostel_name);

        if (empty($suffixes)) {
            returnResponse('error', "No table suffixes found for the given hostel.");
            return [];
        }

        $tables = [];
        foreach ($suffixes as $suffix) {
            $like_pattern = $db_conn->real_escape_string('%_' . $suffix);
            $query = "SHOW TABLES LIKE '$like_pattern'"; // Directly insert the string

            $result = $db_conn->query($query);
            if ($result) {
                while ($row = $result->fetch_array()) {
                    $tables[] = $row[0];
                }
            } else {
                returnResponse('error', "SQL error: " . $db_conn->error);
                return [];
            }
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
