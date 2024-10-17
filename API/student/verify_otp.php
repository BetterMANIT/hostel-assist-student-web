<?php
require '../db_connect.php';
include '../../debug_config.php';

header('Content-Type: application/json');

// Validate required parameters
if (!isset($_POST['scholar_no']) || empty($_POST['scholar_no']) || 
    !isset($_POST['phone_no']) || empty($_POST['phone_no']) || 
    !isset($_POST['otp']) || empty($_POST['otp'])) {
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Scholar number, phone number, and OTP are required.'
        ]);
    exit;
}

$scholar_no = $_POST['scholar_no'];
$phone_no = $_POST['phone_no'];
$otp = $_POST['otp'];

// Fetch the most recent OTP for the phone number
$stmt = $db_conn->prepare("SELECT otp, expires_at FROM otp_table WHERE phone_no = ? ORDER BY id DESC LIMIT 1");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare the SQL statement: ' . $db_conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $phone_no);
$stmt->execute();
$stmt->bind_result($existing_otp, $expires_at);
$stmt->fetch();
$stmt->close();

$current_time = time();

// Check if OTP exists and is still valid
if (!$existing_otp || strtotime($expires_at) < $current_time) {
    echo json_encode([
        'status' => 'error',
        'message' => 'OTP is either incorrect or has expired.'
    ]);
    exit;
}

// Validate the OTP
if ($otp !== $existing_otp) {
    echo json_encode([
        'status' => 'error',
        'message' => 'OTP Invalid'
    ]);
    exit;
}

// Generate a new token
$token = bin2hex(random_bytes(16));

// Update the token in the students_info table
$stmt = $db_conn->prepare("UPDATE students_info SET token = ? WHERE scholar_no = ?");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to prepare the SQL statement: ' . $db_conn->error
    ]);
    exit;
}

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
        'message' => 'Failed to store token in the database: ' . $stmt->error
    ]);
}

$stmt->close();
$db_conn->close();
?>
