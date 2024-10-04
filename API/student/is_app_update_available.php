<?php
header('Content-Type: application/json');

$latest_version_code = 5; 
$apk_download_link = "https://example.com/latest_app.apk";

if (isset($_POST['version_code'])) {
    $current_version_code = (int)$_POST['version_code'];

    // Check if an update is available
    if ($current_version_code < $latest_version_code) {
        $response = [
            'status' => 'success',
            'is_update_available' => true,
            'apk_download_link' => $apk_download_link
        ];
    } else {
        $response = [
            'status' => 'success',
            'is_update_available' => false,
            'apk_download_link' => ''
        ];
    }
} else {
    // If version_code is not provided in the request
    $response = [
        'status' => 'error',
        'message' => 'version_code is required'
    ];
}

// Return the response as JSON
echo json_encode($response);
?>
