<?php
$host = 'localhost'; // Change if necessary
$db = 'hostel'; // Change to your database name
$user = 'root'; // Change to your database username
$pass = ''; // Change to your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch student details
if (isset($_POST['scholarNumber'])) {
    $scholarNumber = $_POST['scholarNumber'];
    $stmt = $pdo->prepare("SELECT name, phone, status FROM student_info WHERE scholar_no = :scholar_no");
    $stmt->execute(['scholar_no' => $scholarNumber]);
    $result = $stmt->fetch();

    if ($result) {
        echo '<div id="name">' . $result['name'] . '</div>';
        echo '<div id="phone">' . $result['phone'] . '</div>';
        echo '<div id="status">' . $result['status'] . '</div>';
    } else {
        echo '<div id="name"></div>';
        echo '<div id="phone"></div>';
        echo '<div id="status"></div>';
    }
}

// Update scholar details
if (isset($_POST['hostel_no'])) 
{
    $scholarNumber = $_POST['scholarNumber'];
    $hostelNo = $_POST['hostel_no'];
    $roomNo = $_POST['room_no'];
    $guardianNo = $_POST['guardian_no'];
    $guardianName = $_POST['guardian_name'];

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
?>
