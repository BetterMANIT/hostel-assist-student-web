<?php
// We have created this file, so that automatically a table with dmY + hostel no. is created. Daily Created table name example : 27092024H10C
// So that admins have list day wise & hostel wise.

include '../debug_config.php';
include 'db_connect.php';

// Fetch distinct hostel names
$query = "SELECT DISTINCT hostel_name FROM student_info";
$result = $db_conn->query($query);

if($result === FALSE){
    echo json_encode(['status' => 'error', 'message' =>'Error executing query: '. $db_conn->error]);
}else{
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $filteredData = array_filter(array_map(function($row) {
        return array_filter($row, function($value) {
            return !is_null($value); // Only keep non-null values
        });
    }, $data), function($row) {
        return !empty($row); // Keep only non-empty rows
    });
    echo json_encode(['status' => 'success', 'data' => $filteredData]);
}
$db_conn->close(); 
?>
