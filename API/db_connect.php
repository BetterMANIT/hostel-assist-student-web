<?php
$host = '4.186.57.254';
$username = 'root';
$password = 'Happysingh@happy3';
$dbname = 'hostel_assist';

// Create connection
$db_conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($db_conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>