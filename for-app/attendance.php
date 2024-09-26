<?php
// Database connection parameters
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

// Handle attendance updates
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $scholarNo = $_POST['scholar_no'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $action = $_POST['action']; // "exit" or "entry"

    if ($action === "exit") {
        // Insert attendance record for exit
        $stmt = $pdo->prepare("INSERT INTO attendance (scholar_no, name, phone, status, out_stamp) VALUES (?, ?, ?, 'out', NOW())");
        $stmt->execute([$scholarNo, $name, $phone]);
        // Redirect after successful entry
        header("Location: home.php?message=exited");
        exit;
    } elseif ($action === "entry") {
        // Check the current status
        $stmt = $pdo->prepare("SELECT status FROM attendance WHERE scholar_no = ? ORDER BY out_stamp DESC LIMIT 1");
        $stmt->execute([$scholarNo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['status'] === 'out') {
            // Update to in
            $stmt = $pdo->prepare("UPDATE attendance SET status = 'in', in_stamp = NOW() WHERE scholar_no = ? AND status = 'out'");
            $stmt->execute([$scholarNo]);
            // Redirect after successful entry
            header("Location: home.php?message=returned");
            exit;
        } else {
            // Redirect with error message (optional)
            header("Location: home.php?message=not_out");
            exit;
        }
    }
}
?>
