<?php
include '../db_connect.php'; 
header('Content-Type: application/json');

if (!isset($_POST['scholar_no']) || empty($_POST['scholar_no'])) {
    echo json_encode(['success' => false, 'message' => 'Scholar number is required.']);
    exit;
}

$scholar_no = $_POST['scholar_no'];

$target_dir = dirname(__FILE__) . '/student/photos/';
$photo_file = $target_dir . $scholar_no . '.png';

$allowed_formats = ['png', 'jpg', 'jpeg', 'gif'];
$found_image = false;

foreach ($allowed_formats as $format) {
    $file_path = $target_dir . $scholar_no . '.' . $format;
    if (file_exists($file_path)) {
        $photo_file = $file_path; 
        $found_image = true;
        break; 
    }
}

if (!$found_image) {
    echo json_encode(['success' => false, 'message' => 'Photo not found.']);
    exit;
}

$extension = strtolower(pathinfo($photo_file, PATHINFO_EXTENSION));
switch ($extension) {
    case 'png':
        header('Content-Type: image/png');
        break;
    case 'jpg':
    case 'jpeg':
        header('Content-Type: image/jpeg');
        break;
    case 'gif':
        header('Content-Type: image/gif');
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Unsupported image format.']);
        exit;
}
readfile($photo_file);
exit;
