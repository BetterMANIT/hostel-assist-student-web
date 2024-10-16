<?php
include '../debug_config.php';

$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

echo json_encode([
    'DB_HOST' => $host,
    'DB_USERNAME' => $username,
    'DB_PASSWORD' => $password,
    'DB_NAME' => $dbname
]);

$db_conn = new mysqli($host, $username, $password, $dbname);

if ($db_conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $db_conn->connect_error]);
    exit;
} else {
    echo json_encode(['status' => 'success', 'message' => 'Connection successful']);
}
