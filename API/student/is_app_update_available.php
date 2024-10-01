<?php
// Set header to return JSON response
header('Content-Type: application/json');

// Latest version code and APK download link (can be fetched from a database)
$latest_version_code = 5; // Example: the latest version code of the app
$apk_download_link = "https://www.apkmirror.com/apk/sud-inc/dr-driving-2/dr-driving-2-1-70-release/dr-driving-1-70-android-apk-download/download/?key=042e21660ea70cbf193e0179b987f5eb3450c54a&forcebaseapk=true"; // Replace with actual APK link

// Check if version_code is provided in the POST request
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
