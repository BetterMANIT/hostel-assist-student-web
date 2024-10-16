<?php
require 'db_connect.php';

function generateOTP() {
    $otpLength = 6;
    $otp = "";

    for ($i = 0; $i < $otpLength; $i++) {
        $otp .= rand(0, 9);
    }

    return $otp;
}

header('Content-Type: application/json');

// Check if phone number is provided
if (!isset($_REQUEST['phone_no']) || empty($_REQUEST['phone_no'])) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number is required.']);
    exit;
}

$phone_no = $_REQUEST['phone_no'];

// Check if an OTP already exists and is valid
$stmt = $db_conn->prepare("SELECT otp, expires_at FROM otp_table WHERE phone_no = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("s", $phone_no);
$stmt->execute();
$stmt->bind_result($existing_otp, $expires_at);
$stmt->fetch();
$stmt->close();

$current_time = time();

if ($existing_otp && strtotime($expires_at) > $current_time) {
    echo json_encode(['status' => 'success', 'phone_no' => $phone_no, 'message' => 'OTP is still valid.']);
    exit;
}

// Generate a new OTP if no valid OTP exists
$otp = generateOTP();
$expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Insert the new OTP into the database
$stmt = $db_conn->prepare("INSERT INTO otp_table (phone_no, otp, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $phone_no, $otp, $expires_at);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'phone_no' => $phone_no,
        'expires_at' => $expires_at,
        'message' => 'New OTP generated.'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to store OTP in the database.']);
}

$stmt->close();
$db_conn->close();
?>
