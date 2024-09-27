<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php'; // Ensure this file contains your database connection logic

if (!isset($_GET['table_name'])) {
    echo json_encode(["status" => "error", "message" => "Error: table_name is a mandatory parameter"]);
    exit;
}

$table_name = $_GET['table_name'];

// Validate the table name (basic validation to prevent SQL injection)
if (!preg_match('/^[a-zA-Z0-9_]+$/', $table_name)) {
    echo json_encode(["status" => "error", "message" => "Invalid table name.: " . $db_conn->error]);
    exit;
}

// Prepare the SQL statement
$sql = "SELECT * FROM `$table_name`";

// Execute the query
$result = $db_conn->query($sql);

$response = [];

if ($result) {
    // Fetch all entries and store in response array
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    // Free result set
    $result->free();
    
    // Return the response as JSON
    echo json_encode(["status" => "success", "data" => $response]);
} else {
    echo json_encode(["status" => "error", "message" => "Error fetching entries: " . $db_conn->error]);
}

// Close the database connection
$db_conn->close();
?>
