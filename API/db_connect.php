<?php
// Include Composer's autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Access the environment variables
$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Connect to the database
$db_conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($db_conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => "Connection failed: " . $db_conn->connect_error]);
    exit;
}

// Continue with the rest of your logic...
?>
