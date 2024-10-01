<?php

include '../../debug_config.php';
include '../db_connect.php';
header('Content-Type: application/json');

function parse_variable_table_name($variable_table_name) {
    $today = date("d");
    $month = date("m");
    $year = date("Y");
    $twoDigitYear = date("y");

    $variable_table_name = str_replace("{dd}", $today, $variable_table_name);
    $variable_table_name = str_replace("{mm}", $month, $variable_table_name);
    $variable_table_name = str_replace("{yyyy}", $year, $variable_table_name);
    $variable_table_name = str_replace("{yy}", $twoDigitYear, $variable_table_name);

    return $variable_table_name;
}

// Handle incoming request using $_REQUEST (works for both GET and POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$hostel_name = $_REQUEST['hostel_name'] ?? null;

if (empty($hostel_name)) {
    echo json_encode(['status' => 'error', 'message' => 'hostel_name parameter is required']);
    exit();
}

$sql = "SELECT id, purpose, constant_table_name, variable_table_name_suffix, hostel_name 
        FROM hostel_with_purposes 
        WHERE hostel_name = ? AND is_locked = FALSE";

$stmt = $db_conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database preparation failed: ' . $db_conn->error]);
    exit();
}

$stmt->bind_param("s", $hostel_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'No records found']);
    exit();
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $parsed_variable_table_name = parse_variable_table_name($row['variable_table_name_suffix']);
    $table_name = $row['hostel_name'] . $row['constant_table_name'] . $parsed_variable_table_name;

    $data[] = [
        'id' => $row['id'],
        'table_name' => $table_name,
        'hostel_name' => $row['hostel_name'], 
        'purpose' => $row['purpose']
    ];
}

$stmt->close();
$db_conn->close();

echo json_encode(['status' => 'success', 'data' => $data]);
