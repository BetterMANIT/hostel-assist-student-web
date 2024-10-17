<?php
require '../db_connect.php';
include '../../debug_config.php';

header('Content-Type: application/json');

if (!isset($_REQUEST['scholar_no']) || empty($_REQUEST['scholar_no']) || 
    !isset($_REQUEST['phone_no']) || empty($_REQUEST['phone_no']) || 
    !isset($_REQUEST['otp']) || empty($_REQUEST['otp'])) {
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Scholar number, phone number, and OTP are required.'
        ]);
    exit;
}

$scholar_no = $_REQUEST['scholar_no'];
$phone_no = $_REQUEST['phone_no'];
$otp = $_REQUEST['otp'];

$stmt = $db_conn->prepare("SELECT otp, expires_at FROM otp_table WHERE phone_no = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $phone_no);
$stmt->execute();
$stmt->bind_result($existing_otp, $expires_at);
$stmt->fetch();
$stmt->close();

$current_time = time();

if (!$existing_otp || strtotime($expires_at) < $current_time) {
    echo json_encode([
        'status' => 'error',
        'message' => 'OTP is either incorrect or has expired.'
        ]);
    exit;
}

if ($otp !== $existing_otp) {
    echo json_encode([
        'status' => 'error',
        'message' => 'OTP Invalid'
        ]);
    exit;
}

$token = bin2hex(random_bytes(16));

$stmt = $db_conn->prepare("UPDATE students_info SET token = ? WHERE scholar_no = ?");
$stmt->bind_param("ss", $token, $scholar_no);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'token' => $token,
        'message' => 'OTP verified successfully.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to store token in the database.'
        ]);
}

$stmt->close();
$db_conn->close();
?>
