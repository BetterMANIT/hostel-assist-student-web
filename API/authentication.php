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
$scholar_no = $_REQUEST['scholar_no'] ?? $_GET['scholar_no'] ?? null;
$phone_no = $_REQUEST['phone_no'] ?? $_GET['phone_no'] ?? null;
$device_id = $_REQUEST['device_id'] ??  $_POST['device_id'] ?? null;
$token = $_REQUEST['token'] ??  $_POST['token'] ?? null;;

// if (!$device_id || !$token || (!$scholar_no && !$phone_no)) {
//     respond('error', 'Missing required parameters');
// }

$missing = [];

if (empty($device_id)) {
    $missing[] = 'device_id';
}
if (empty($token)) {
    $missing[] = 'token';
}
if (empty($scholar_no) || empty($phone_no)) {
    $missing[] = 'scholar_no or phone_no';
}

if (!empty($missing)) {
    respond('error', 'Missing required parameters: ' . implode(', ', $missing));
}


// if ($scholar_no) {
//     $stmt = $db_conn->prepare("SELECT * FROM student_info WHERE scholar_no = ? AND device_id = ? AND token = ?");
//     $stmt->bind_param('sss', $scholar_no, $device_id, $token);
// } else {
//     $stmt = $db_conn->prepare("SELECT * FROM admin_info WHERE phone_no = ? AND device_id = ? AND token = ?");
//     $stmt->bind_param('sss', $phone_no, $device_id, $token);
// }

if ($scholar_no) {
    $stmt = $db_conn->prepare("SELECT * FROM student_info WHERE scholar_no = ? AND device_id = ? AND token = ?");
} else {
    $stmt = $db_conn->prepare("SELECT * FROM admin_info WHERE phone_no = ? AND device_id = ? AND token = ?");
}


if (!$stmt) {
    respond('error', 'Failed to prepare statement: ' . $db_conn->error);
}
if ($scholar_no) {
    $stmt->bind_param('sss', $scholar_no, $device_id, $token);
} else {
    $stmt->bind_param('sss', $phone_no, $device_id, $token);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows <= 0) {
    $stmt->close();
    $db_conn->close();
    respond('error', 'Invalid credentials');
}

?>
