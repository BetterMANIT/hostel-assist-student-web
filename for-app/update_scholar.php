<?php
session_start(); // Start the session

$host = 'localhost';
$db = 'hostel'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle the update request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON data from the request
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validate the data
    if (!isset($data['scholarNumber'], $data['hostelNo'], $data['roomNo'], $data['guardianNo'], $data['guardianName'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    $scholarNumber = $data['scholarNumber'];
    $hostelNo = $data['hostelNo'];
    $roomNo = $data['roomNo'];
    $guardianNo = $data['guardianNo'];
    $guardianName = $data['guardianName'];

    // Prepare and execute the update query
    try {
        $stmt = $pdo->prepare("UPDATE student_info SET hostel_no = ?, room_no = ?, guardian_no = ?, guardian_name = ?, status = 'verified' WHERE scholar_no = ?");
        $stmt->execute([$hostelNo, $roomNo, $guardianNo, $guardianName, $scholarNumber]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Details updated successfully and status set to verified.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made or scholar number not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
