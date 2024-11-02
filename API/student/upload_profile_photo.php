<?php
include '../../debug_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

if (!isset($_POST['scholar_no']) || empty($_POST['scholar_no'])) {
    echo json_encode(['status' => 'error', 'message' => 'Scholar number is required.']);
    exit;
}

// Trim whitespace from the scholar number
$scholar_no = trim($_POST['scholar_no']);
$target_dir = __DIR__ . "/photos/";

if (!is_dir($target_dir) && !mkdir($target_dir, 0755, true) && !is_dir($target_dir)) {
    echo json_encode(["success" => false, "message" => "Failed to create directory."]);
    exit;
}

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    // Get the file extension of the uploaded file
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION)); // Ensure lowercase for consistency

    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($file_extension, $allowed_types)) {
        echo json_encode(["success" => false, "message" => "Invalid file type. Allowed types: jpg, jpeg, png"]);
        exit;
    }

    // Set the target file path using scholar_no without any spaces and the original file extension
    $target_file = $target_dir . $scholar_no . "." . $file_extension;

    // Move uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo json_encode(["success" => true, "message" => "File uploaded successfully", "file" => "/photos/" . basename($target_file)]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to upload file"]);
    }
} else {
    $error_message = $_FILES['image']['error'] ? "Error code: " . $_FILES['image']['error'] : "No file was uploaded";
    echo json_encode(["success" => false, "message" => $error_message]);
}
