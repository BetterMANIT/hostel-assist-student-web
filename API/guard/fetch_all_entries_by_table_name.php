<?php 

include '../../debug_config.php'; 
include '../db_connect.php';

if (!isset($_GET['table_name'])) {
    echo json_encode(["status" => "error", "message" => "Error: table_name is a mandatory parameter"]);
    exit;
}

$table_name = $_GET['table_name'];

if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
    echo json_encode(["status" => "error", "message" => "Invalid table name.: " . $db_conn->error]);
    exit;
}

$sql = "SELECT * FROM `$table_name`";
$result = $db_conn->query($sql);

$response = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    $result->free();
    echo json_encode(["status" => "success", "data" => $response]);
} else {
    echo json_encode(["status" => "error", "message" => "Error fetching entries: " . $db_conn->error]);
}
$db_conn->close();
?>
