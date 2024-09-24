<?php
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
        $scholarNumber = $_POST['scholarNumber'];

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare("SELECT name, phone FROM 10d WHERE scholar_no = :number");
        $stmt->execute(['number' => $scholarNumber]);

        // Fetch the scholar details
        $scholar = $stmt->fetch(PDO::FETCH_ASSOC);

        // Output the response
        if ($scholar) {
            echo "<div id='name'>" . htmlspecialchars($scholar["name"]) . "</div>";
            echo "<div id='phone'>" . htmlspecialchars($scholar["phone"]) . "</div>";
        } else {
            echo "<div id='name'></div>";
            echo "<div id='phone'></div>";
        }
    }
} catch (PDOException $e) {
    // Handle error (optional: log error message)
    error_log($e->getMessage());
    echo "<div id='name'></div>";
    echo "<div id='phone'></div>";
}
?>
