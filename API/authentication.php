<?php
require 'db_connect.php';

$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$scholar_no = $headers['scholar-no'] ?? $_POST['scholar_no'] ?? $_GET['scholar_no'] ?? null;
$phone_no = $_POST['phone_no'] ?? $_GET['phone_no'] ?? null;
$device_id = $headers['device-id'] ??  $_POST['device-id'] ?? null;
$token = $headers['token'] ??  $_POST['token'] ?? null;;

function respond($status, $message) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'headers' => array_change_key_case(getallheaders(), CASE_LOWER) // Include all received headers for debugging
    ]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond('error', 'Invalid request method');
}


// if (!$device_id || !$token || (!$scholar_no && !$phone_no)) {
//     respond('error', 'Missing required parameters');
// }

$missing = [];

if (empty($device_id)) {
    $missing[] = 'device-id';
}
if (empty($token)) {
    $missing[] = 'token';
}
if (empty($scholar_no) && empty($phone_no)) {
    $missing[] = 'scholar-no or phone-no';
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
