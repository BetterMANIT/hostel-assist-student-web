<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Student Info</title>
</head>
<body>
    <h1>Search Student by Scholar No.</h1>

    <form action="get_student_info.php" method="POST">
        <label for="scholar_no">Enter Scholar No.:</label>
        <input type="text" id="scholar_no" name="scholar_no" required>
        <input type="submit" value="Get Info">
    </form>

    <?php
    include 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $scholar_no = $conn->real_escape_string($_POST['scholar_no']);

        // Query to fetch student details based on scholar_no
        $sql = "SELECT * FROM student_info WHERE scholar_no = '$scholar_no'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display the student's details
            $row = $result->fetch_assoc();
            echo "<h3>Student Details</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Scholar No.</th><td>{$row['scholar_no']}</td></tr>";
            echo "<tr><th>Name</th><td>{$row['name']}</td></tr>";
            echo "<tr><th>Room No.</th><td>{$row['room_no']}</td></tr>";
            echo "<tr><th>Photo URL</th><td><img src='{$row['photo_url']}' alt='Student Photo' width='100'></td></tr>";
            echo "<tr><th>Phone No.</th><td>{$row['phone_no']}</td></tr>";
            echo "<tr><th>Section</th><td>{$row['section']}</td></tr>";
            echo "<tr><th>Guardian's Phone No.</th><td>{$row['guardian_no']}</td></tr>";
            echo "</table>";
        } else {
            echo "No student found with Scholar No.: $scholar_no";
        }

        $conn->close();
    }
    ?>
</body>
</html>
