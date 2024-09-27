<?php
// TODO : Create an .env fke
$host = '4.186.57.254';
$username = 'root';
$password = 'Happysingh@happy3';
$dbname = 'hostel_assist';

$db_conn = new mysqli($host, $username, $password, $dbname);
if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error); 
}
