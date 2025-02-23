<?php 

include '../../debug_config.php'; 
include '../db_connect.php';
require '../authentication.php';

header('Content-Type: application/json');

if (!isset($_GET['table_name']) || !isset($_GET['fromDate']) || !isset($_GET['toDate'])) {
    echo json_encode(["status" => "error", "message" => "Error: table_name, fromDate, and toDate are mandatory parameters"]);
    exit;
}

$table_name = $_GET['table_name'];
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

// Validate table name to prevent SQL injection
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
    echo json_encode(["status" => "error", "message" => "Invalid table name."]);
    exit;
}

// Validate date format (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
    echo json_encode(["status" => "error", "message" => "Invalid date format. Use YYYY-MM-DD"]);
    exit;
}

// Check if 'purpose' parameter is provided
$purpose = isset($_GET['purpose']) ? $_GET['purpose'] : null;

// Prepare SQL query to fetch entries
$sql = "SELECT * FROM `$table_name` WHERE DATE(`open_time`) BETWEEN ? AND ?";
if ($purpose !== null) {
    $sql .= " AND `purpose` = ?";
}

$stmt = $db_conn->prepare($sql);
if ($purpose !== null) {
    $stmt->bind_param('sss', $fromDate, $toDate, $purpose);
} else {
    $stmt->bind_param('ss', $fromDate, $toDate);
}

$stmt->execute();
$result = $stmt->get_result();

$entries = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $entries[] = $row;
    }
    $result->free();
}

// Fetch total entries count
$totalEntriesQuery = "SELECT COUNT(*) AS total_entries FROM `$table_name` WHERE DATE(`open_time`) BETWEEN ? AND ?";
$totalStmt = $db_conn->prepare($totalEntriesQuery);
$totalStmt->bind_param('ss', $fromDate, $toDate);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalEntries = $totalResult->fetch_assoc()['total_entries'];
$totalStmt->close();

// Fetch count of students who have not returned (out_students)
$outStudentsQuery = "SELECT COUNT(*) AS out_students FROM `$table_name` WHERE DATE(`open_time`) BETWEEN ? AND ? AND `close_time` IS NULL";
$outStmt = $db_conn->prepare($outStudentsQuery);
$outStmt->bind_param('ss', $fromDate, $toDate);
$outStmt->execute();
$outResult = $outStmt->get_result();
$outStudents = $outResult->fetch_assoc()['out_students'];
$outStmt->close();

// Fetch count of students who have returned (came_back_students)
$cameBackQuery = "SELECT COUNT(*) AS came_back_students FROM `$table_name` WHERE DATE(`open_time`) BETWEEN ? AND ? AND `close_time` IS NOT NULL";
$cameBackStmt = $db_conn->prepare($cameBackQuery);
$cameBackStmt->bind_param('ss', $fromDate, $toDate);
$cameBackStmt->execute();
$cameBackResult = $cameBackStmt->get_result();
$cameBackStudents = $cameBackResult->fetch_assoc()['came_back_students'];
$cameBackStmt->close();

$db_conn->close();

// Prepare response
$response = [
    "status" => "success",
    "statistics" => [
        "total_entries" => $totalEntries,
        "out_students" => $outStudents,
        "came_back_students" => $cameBackStudents
    ],
    "data" => $entries
];

echo json_encode($response);
?>
