<?php

function generateOTP() {
    // Set the desired length of the OTP
    $otpLength = 6;

    $otp = "";
    for ($i = 0; $i < $otpLength; $i++) {
        $otp .= rand(0, 9);
    }

    return $otp;
}
    header('Content-Type: application/json');

if (!isset($_REQUEST['phone_no']) || empty($_REQUEST['phone_no'])) {
    
    // Return an error message as a JSON object
    echo json_encode(['error' => 'Phone number is required.']);
    exit;
}

// Generate the OTP
$otp = generateOTP();

header('Content-Type: application/json');
echo json_encode(['otp' => $otp, 'phone_no' => $_REQUEST['phone_no']]);
