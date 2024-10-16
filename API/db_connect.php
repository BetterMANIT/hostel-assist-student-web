<?php
include '../debug_config.php';

// Hardcoded values for database connection
$host = '4.186.57.254';
$username = 'root';
$password = 'Happysingh@happy3';
$dbname = 'hostel_assist';

$db_conn = new mysqli($host, $username, $password, $dbname);

if ($db_conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $db_conn->connect_error]);
    exit;
} else {
    echo json_encode(['status' => 'success', 'message' => 'Connection successful']);
}
