<?php
session_start();
$host = 'localhost';
$db = 'hostel'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Handle attendance logic here
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];
    $scholarNo = $_POST['scholar_no'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    if ($action === 'exit') {
        // Logic for exiting
        header("Location: home.php?message=exited");
        exit;
    } elseif ($action === 'entry') {
        // Logic for returning
        header("Location: home.php?message=returned");
        exit;
    } else {
        header("Location: home.php?message=not_out");
        exit;
    }
}
?>
