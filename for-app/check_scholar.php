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

// Check if scholar number is set
if (isset($_POST['scholar_number'])) {
    $scholarNumber = $_POST['scholar_number'];

    // Prepare and execute the query to check for the scholar number
    $stmt = $pdo->prepare("SELECT name, phone FROM student_info WHERE scholar_no = ?");
    $stmt->execute([$scholarNumber]);
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Return the data as HTML
        echo "<div id='name'>" . htmlspecialchars($result['name']) . "</div>";
        echo "<div id='phone'>" . htmlspecialchars($result['phone']) . "</div>";
    } else {
        // Return an empty response if no user is found
        echo "";
    }
} else {
    echo "Scholar number not provided.";
}
?>
