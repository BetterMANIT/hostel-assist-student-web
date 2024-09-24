<?php
$host = '4.186.57.254';
$username = 'root';
$password = 'Happysingh@happy3';  // Default password for XAMPP/WAMP is empty
$dbname = 'hostel_assist'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
