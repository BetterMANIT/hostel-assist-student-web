<?php
include '../../debug_config.php';
header('Content-Type: application/json'); 


if (!isset($_REQUEST['scholar_no']) || empty($_REQUEST['scholar_no'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Scholar number is required.']);
    exit;
}
$scholar_no = $_REQUEST['scholar_no'];
$target_dir = "../../photos/";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $scholar_no .".png";
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo json_encode(["success" => true, "message" => "File uploaded successfully", "file" => $target_file]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload file"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file was uploaded"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

