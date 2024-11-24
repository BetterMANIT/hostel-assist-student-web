<?php
require 'db_connect.php';

function respond($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond('error', 'Invalid request method');
}

$headers = getallheaders();
$scholar_no = $_POST['scholar_no'] ?? $_GET['scholar_no'] ?? null;
$phone_no = $_POST['phone_no'] ?? $_GET['phone_no'] ?? null;
$device_id = $headers['device_id'] ?? null;
$token = $headers['token'] ?? null;

if (!$device_id || !$token || (!$scholar_no && !$phone_no)) {
    respond('error', 'Missing required parameters');
}

if ($scholar_no) {
    $stmt = $db_conn->prepare("SELECT * FROM student_info WHERE scholar_no = ? AND device_id = ? AND token = ?");
    $stmt->bind_param('sss', $scholar_no, $device_id, $token);
} else {
    $stmt = $db_conn->prepare("SELECT * FROM admin_info WHERE phone_no = ? AND device_id = ? AND token = ?");
    $stmt->bind_param('sss', $phone_no, $device_id, $token);
}

if (!$stmt) {
    respond('error', 'Failed to prepare statement: ' . $db_conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows <= 0) {
    $stmt->close();
    $db_conn->close();
    respond('error', 'Invalid credentials');
}

?>
