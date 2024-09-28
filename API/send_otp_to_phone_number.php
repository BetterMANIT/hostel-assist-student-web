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

// Generate the OTP
$otp = generateOTP();

// Set the content type to application/json
header('Content-Type: application/json');

// Return the OTP as a JSON object
echo json_encode(['otp' => $otp]);
