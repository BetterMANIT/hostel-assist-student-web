<?php
include '../../debug_config.php';
include '../db_connect.php';
header('Content-Type: application/json'); 

if (!isset($_REQUEST['scholar_no']) || empty($_REQUEST['scholar_no'])) {
    echo json_encode(['status' => 'error', 'message' => 'Scholar number is required.']);
    exit;
}

$scholar_no = $_REQUEST['scholar_no'];
$target_dir = dirname(__FILE__) . '/photos/';
$photo_url = "http://{$_SERVER['HTTP_HOST']}/API/student/photos/{$scholar_no}.png"; // Build the URL dynamically

if (!is_dir($target_dir)) {
    if (!mkdir($target_dir, 0755, true) && !is_dir($target_dir)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create directory.']);
        exit;
    }
}

function updatePhotoUrl($db_conn, $scholar_no, $photo_url) {
    $stmt = $db_conn->prepare("UPDATE student_info SET photo_url = ? WHERE scholar_no = ?");
    $stmt->bind_param("ss", $photo_url, $scholar_no);

    if ($stmt->execute()) {
        $stmt->close();
        return ["success" => true, "message" => "Photo URL updated"];
    }

    $stmt->close();
    return ["success" => false, "message" => "Failed to update photo URL"];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(["success" => false, "message" => "No file was uploaded"]);
    exit;
}

if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "File upload error: " . $_FILES['image']['error']]);
    exit;
}

$target_file = $target_dir . $scholar_no . ".png";

if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    echo json_encode(["success" => false, "message" => "Failed to upload file"]);
    exit;
}

$update_result = updatePhotoUrl($db_conn, $scholar_no, $photo_url);

if ($update_result['success']) {
    echo json_encode(["success" => true, "message" => "File uploaded and " . $update_result['message'], "file" => $target_file]);
} else {
    echo json_encode(["success" => false, "message" => "File uploaded but " . $update_result['message']]);
}

$db_conn->close();
