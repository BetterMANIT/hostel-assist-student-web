
<?php
// We have created this file, so that automatically a table with dmY + hostel no. is created. Daily Created table name example : 27092024H10C
// So that admins have list day wise & hostel wise.

echo "started creating";

include '/debug_config.php';
include 'db_connect.php';

$query = "SELECT DISTINCT hostel_name FROM students_info";
$result = $db_conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hostel_name = $row['hostel_name'];

        $date = date('dmY'); 
        $table_name = $date . $hostel_name;
        $create_table_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
            scholar_no VARCHAR(11) PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            room_no VARCHAR(10),
            photo_url VARCHAR(255),
            phone_no VARCHAR(15),
            section VARCHAR(50))";

        if ($db_conn->query($create_table_query) === TRUE) {
            echo "Table `$table_name` created successfully or already exists.<br>";
        } else {
            echo "Error creating table `$table_name`: " . $db_conn->error . "<br>";
        }
    }
} else {
    echo "No hostel numbers found or query execution failed.";
}
$db_conn->close();
