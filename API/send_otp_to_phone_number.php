<?php

function generateOTP() {
    // Set the desired length of the OTP
    $otpLength = 6;

    // Generate a random string of numbers
    $otp = "";
    for ($i = 0; $i < $otpLength; $i++) {
        $otp .= rand(0, 9);
    }

    return $otp;
}

// Check if the phone number is provided
if (!isset($_REQUEST['phone_number']) || empty($_REQUEST['phone_number'])) {
    // Set the content type to application/json
    header('Content-Type: application/json');
    
    // Return an error message as a JSON object
    echo json_encode(['error' => 'Phone number is required.']);
    exit;
}

// Generate the OTP
$otp = generateOTP();

// Set the content type to application/json
header('Content-Type: application/json');

// Return the OTP and phone number as a JSON object
echo json_encode(['otp' => $otp, 'phone_number' => $_REQUEST['phone_number']]);
