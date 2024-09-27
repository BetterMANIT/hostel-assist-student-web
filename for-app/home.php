<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MANIT Assist</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #050a30;
        }

        .navbar {
            background-color: white;
            width: 100%;
            text-align: center;
            padding: 10px 0;
        }

        .brand {
            font-weight: bold;
            font-size: 24px;
            color: black;
        }

        .student-info {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .student-image {
            width: 130px;
            height: 150px;
            border-radius: 20%;
            margin-right: 20px;
        }

        .student-data {
            display: flex;
            flex-direction: column;
            color: white;
        }

        .button-container {
            margin: 20px 0;
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            color: white;
        }

        .exit {
            background-color: red;
        }

        .back {
            background-color: green;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1 class="brand">MANIT Assist</h1>
    </nav>
    
    <div class="student-info">
        <img src="https://i.pinimg.com/736x/02/fd/42/02fd42a0f060470753495c81d50caf40.jpg" alt="Student Image" class="student-image">
        <div class="student-data">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p><strong>Scholar No:</strong> <?php echo htmlspecialchars($_SESSION['scholar_no']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone']); ?></p>
            <p><strong>Hostel No:</strong> <?php echo htmlspecialchars($_SESSION['hostel_no']); ?></p>
            <p><strong>Room No:</strong> <?php echo htmlspecialchars($_SESSION['room_no']); ?></p>
        </div>
    </div>

    <div class="button-container">
        <form method="post" action="attendance.php">
            <input type="hidden" name="scholar_no" value="<?php echo htmlspecialchars($_SESSION['scholar_no']); ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>">
            <button type="submit" class="button exit" name="action" value="exit">Exit to Class</button>
            <button type="submit" class="button back" name="action" value="entry">Back to Hostel</button>
        </form>
    </div>

    <?php
    // Display alert messages based on the query string
    if (isset($_GET['message'])) {
        if ($_GET['message'] == 'exited') {
            echo "<div style='color: white;'>You have exited for classes.</div>";
        } elseif ($_GET['message'] == 'returned') {
            echo "<div style='color: white;'>Welcome back to the hostel!</div>";
        } elseif ($_GET['message'] == 'not_out') {
            echo "<div style='color: white;'>You were not out yet!</div>";
        }
    }
    ?>
</body>
</html>
