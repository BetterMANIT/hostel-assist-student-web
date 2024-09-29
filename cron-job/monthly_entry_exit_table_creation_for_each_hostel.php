<?php
// We have created this file, so that automatically a table with dmY + hostel no. is created. Daily Created table name example : 27092024H10C
// So that admins have list day wise & hostel wise.

echo "Started creating tables...<br>";

include '../debug_config.php';
include '../API/db_connect.php';

// Check database connection
if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error);
}

// Fetch distinct hostel names
$query = "SELECT DISTINCT hostel_name FROM student_info";
echo "query";
$result = $db_conn->query($query);

// Check for query execution error
if ($result === FALSE) {
    echo "Error executing query: " . $db_conn->error . "<br>";
} else {
    if ($result->num_rows > 0) {
        echo "Hostel names found: " . $result->num_rows . "<br>";
        while ($row = $result->fetch_assoc()) {
            $hostel_name = $row['hostel_name'];

            $date = date('mY'); 
            $table_name = $date . $hostel_name;

            echo "creating table : ".$table_name;

            $create_table_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                scholar_no VARCHAR(11),
                name VARCHAR(100) NOT NULL,
                room_no VARCHAR(10),
                photo_url VARCHAR(255),
                phone_no VARCHAR(15),
                section VARCHAR(50),
                open_time DATETIME,
                close_time DATETIME
            )";            

            // Debug print for the table creation query
            echo "Creating table with query: $create_table_query<br>";

            // Execute the create table query
            if ($db_conn->query($create_table_query) === TRUE) {
                echo "Table `$table_name` created successfully or already exists.<br>";
            } else {
                echo "Error creating table `$table_name`: " . $db_conn->error . "<br>";
            }
        }
    } else {
        echo "No hostel numbers found.<br>";
    }
}

// Close the database connection
$db_conn->close(); 
?>
