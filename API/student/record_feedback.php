<?php
include '../db_connect.php';

header('Content-Type: application/json');

$table_creation_query = "
CREATE TABLE IF NOT EXISTS feedback (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    scholar_no BIGINT(11) NOT NULL,
    name VARCHAR(100) NOT NULL,
    comments TEXT NOT NULL,
    stars INT(1) NOT NULL CHECK (stars >= 1 AND stars <= 5),
    version_code VARCHAR(10) NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($db_conn->query($table_creation_query) === FALSE) {
    echo json_encode(['status' => 'error', 'message' => 'Error creating table: ' . $db_conn->error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET") {
    $scholar_no = $db_conn->real_escape_string($_REQUEST['scholar_no']);
    $name = $db_conn->real_escape_string($_REQUEST['name']);
    $comments = $db_conn->real_escape_string($_REQUEST['comments']);
    $stars = intval($_REQUEST['stars']);
    $version_code = $db_conn->real_escape_string($_REQUEST['version_code']);

    $sql = "INSERT INTO feedback (scholar_no, name, comments, stars, version_code) 
            VALUES ('$scholar_no', '$name', '$comments', '$stars', '$version_code')";

    if ($db_conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $db_conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$db_conn->close();
