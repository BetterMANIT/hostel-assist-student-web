<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANIT Assist</title>
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
            <p><strong>Name:</strong> John Doe</p>
            <p><strong>Scholar No:</strong> 123456</p>
            <p><strong>Hostel No:</strong> 101</p>
            <p><strong>Date:</strong> 2024-09-24</p>
        </div>
    </div>
    
    <div class="button-container">
        <button class="button exit">Exit to Class</button>
        <button class="button back">Back to Hostel</button>
    </div>

    <script src="script.js"></script>
</body>
</html>
