<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANIT Assist</title>
</head>
<body>
    <nav>
        <h1>MANIT Assist</h1>
    </nav>
    
    <div class="student-info">
        <img src="https://i.pinimg.com/736x/02/fd/42/02fd42a0f060470753495c81d50caf40.jpg" alt="Student Image" class="student-image">
        <div class="student-data">
            <p><strong>Name:</strong> <?php echo isset($_COOKIE['name']) ? htmlspecialchars($_COOKIE['name']) : 'N/A'; ?></p>
            <p><strong>Scholar No:</strong> <?php echo isset($_COOKIE['scholar_no']) ? htmlspecialchars($_COOKIE['scholar_no']) : 'N/A'; ?></p>
            <p><strong>Phone:</strong> <?php echo isset($_COOKIE['phone']) ? htmlspecialchars($_COOKIE['phone']) : 'N/A'; ?></p>
            <p><strong>Hostel No:</strong> <?php echo isset($_COOKIE['hostel_name']) ? htmlspecialchars($_COOKIE['hostel_name']) : 'N/A'; ?></p>
            <p><strong>Room No:</strong> <?php echo isset($_COOKIE['room_no']) ? htmlspecialchars($_COOKIE['room_no']) : 'N/A'; ?></p>
        </div>
    </div>

    <div class="button-container">
        <form method="post" action="attendance.php">
            <input type="hidden" name="scholar_no" value="<?php echo isset($_COOKIE['scholar_no']) ? htmlspecialchars($_COOKIE['scholar_no']) : ''; ?>">
            <input type="hidden" name="name" value="<?php echo isset($_COOKIE['name']) ? htmlspecialchars($_COOKIE['name']) : ''; ?>">
            <input type="hidden" name="phone" value="<?php echo isset($_COOKIE['phone']) ? htmlspecialchars($_COOKIE['phone']) : ''; ?>">
            <button type="submit" name="action" value="exit">Exit to Class</button>
            <button type="submit" name="action" value="entry">Back to Hostel</button>
        </form>
    </div>

    <?php
    // Display alert messages based on the query string
    if (isset($_GET['message'])) {
        if ($_GET['message'] == 'exited') {
            echo "<div>You have exited for classes.</div>";
        } elseif ($_GET['message'] == 'returned') {
            echo "<div>Welcome back to the hostel!</div>";
        } elseif ($_GET['message'] == 'not_out') {
            echo "<div>You were not out yet!</div>";
        }
    }
    ?>
</body>
</html>
