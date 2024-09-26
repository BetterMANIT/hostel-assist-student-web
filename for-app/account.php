<?php
session_start(); // Start the session
$host = 'localhost';
$db = 'hostel_assist'; 
$user = 'root'; 
$pass = 'Happysingh@happy3'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Handle student registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $scholarNumber = $_POST['scholar_number'];
    $phone = $_POST['phone'];
    $hostelNo = $_POST['hostel_no'];
    $roomNo = $_POST['room_no'];
    $guardianNo = $_POST['guardian_no'];
    $guardianName = $_POST['guardian_name'];

    // Insert student information into the database
    $stmt = $pdo->prepare("INSERT INTO student_info (name, scholar_no, phone, hostel_no, room_no, guardian_no, guardian_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $scholarNumber, $phone, $hostelNo, $roomNo, $guardianNo, $guardianName]);

    // Set cookies for the user details
    setcookie("name", $name, time() + (86400 * 30), "/"); // 30 days
    setcookie("scholar_no", $scholarNumber, time() + (86400 * 30), "/");
    setcookie("phone", $phone, time() + (86400 * 30), "/");
    setcookie("hostel_no", $hostelNo, time() + (86400 * 30), "/");
    setcookie("room_no", $roomNo, time() + (86400 * 30), "/");

    // Set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['name'] = $name;
    $_SESSION['scholar_no'] = $scholarNumber;
    $_SESSION['phone'] = $phone;
    $_SESSION['hostel_no'] = $hostelNo;
    $_SESSION['room_no'] = $roomNo;

    // Redirect to home.php
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration</title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        input[type="text"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Account Registration</h1>
        <form method="POST" action="account.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="scholar_number" placeholder="Scholar Number" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="text" name="hostel_no" placeholder="Hostel Number" required>
            <input type="text" name="room_no" placeholder="Room Number" required>
            <input type="tel" name="guardian_no" placeholder="Guardian Phone Number" required>
            <input type="text" name="guardian_name" placeholder="Guardian Name" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
