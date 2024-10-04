<?php
// We have created this file, so that automatically a table with dmY + hostel no. is created. Daily Created table name example : 27092024H10C
// So that admins have list day wise & hostel wise.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../debug_config.php'; 
include '../db_connect.php';

// Fetch distinct hostel names
$query = "SELECT DISTINCT hostel_name FROM student_info";
$result = $db_conn->query($query);

if($result === FALSE){
    echo json_encode(['status' => 'error', 'message' =>'Error executing query: '. $db_conn->error]);
}else{
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $hostelNames = array_filter(array_map(function($row) {
        return $row['hostel_name']; 
    }, $data), function($name) {
        return !is_null($name);
    });
    
    echo json_encode(['status' => 'success', 'data' => array_values($hostelNames)]); 
}
$db_conn->close(); 
?>
