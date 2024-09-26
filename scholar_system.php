<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$dsn = 'mysql:host=localhost;dbname=hostel;charset=utf8';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Check for scholar number
        if (isset($_POST['scholarNumber'])) {
            $scholarNumber = $_POST['scholarNumber'];

            // Prepare and execute the SQL query
            $stmt = $pdo->prepare("SELECT name, phone, status FROM student_info WHERE scholar_no = :number");
            $stmt->execute(['number' => $scholarNumber]);

            // Fetch the scholar details
            $scholar = $stmt->fetch(PDO::FETCH_ASSOC);

            // Output the response
            if ($scholar) {
                echo "<div id='name'>" . htmlspecialchars($scholar["name"]) . "</div>";
                echo "<div id='phone'>" . htmlspecialchars($scholar["phone"]) . "</div>";
                echo "<div id='status'>" . htmlspecialchars($scholar["status"]) . "</div>";
            } else {
                echo "<div id='name'></div>";
                echo "<div id='phone'></div>";
                echo "<div id='status'></div>";
            }
        }

        // Check for updating scholar information
        if (isset($_POST['hostel_no'])) {
            $scholarNumber = $_POST['scholarNumber'];
            $hostelNo = $_POST['hostel_no'];
            $roomNo = $_POST['room_no'];
            $guardianNo = $_POST['guardian_no'];
            $guardianName = $_POST['guardian_name'];

            // Update the scholar's details
            $updateStmt = $pdo->prepare("UPDATE student_info SET hostel_no = :hostel_no, room_no = :room_no, guardian_no = :guardian_no, guardian_name = :guardian_name, status = 'verified' WHERE scholar_no = :scholar_no");
            $updateStmt->execute([
                'hostel_no' => $hostelNo,
                'room_no' => $roomNo,
                'guardian_no' => $guardianNo,
                'guardian_name' => $guardianName,
                'scholar_no' => $scholarNumber
            ]);

            echo "Scholar information updated successfully.";
        }
    }
} catch (PDOException $e) {
    // Handle error (optional: log error message)
    error_log($e->getMessage());
    echo "<div id='name'></div>";
    echo "<div id='phone'></div>";
    echo "<div id='status'></div>";
}
?>
