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
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Handle student registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $scholarNumber = $_POST['scholar_number'];
    $phone = $_POST['phone'];
    $hostelNo = $_POST['hostel_name'];
    $roomNo = $_POST['room_no'];
    $guardianNo = $_POST['guardian_no'];
    $guardianName = $_POST['guardian_name'];

    // Insert student information into the database
    $stmt = $pdo->prepare("INSERT INTO student_info (name, scholar_no, phone, hostel_name, room_no, guardian_no, guardian_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $scholarNumber, $phone, $hostelNo, $roomNo, $guardianNo, $guardianName]);

    // Set cookies for the user details
    setcookie("name", $name, time() + (86400 * 30), "/"); // 30 days
    setcookie("scholar_no", $scholarNumber, time() + (86400 * 30), "/");
    setcookie("phone", $phone, time() + (86400 * 30), "/");
    setcookie("hostel_name", $hostelNo, time() + (86400 * 30), "/");
    setcookie("room_no", $roomNo, time() + (86400 * 30), "/");

    // Set session variables
    $_SESSION['logged_in'] = true;
    $_SESSION['name'] = $name;
    $_SESSION['scholar_no'] = $scholarNumber;
    $_SESSION['phone'] = $phone;
    $_SESSION['hostel_name'] = $hostelNo;
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
    <title>Scholar Lookup</title>
    <style>
         body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
                'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
                sans-serif;
            background-color: #050a30;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 250px;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 100px;
            height: 100px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .scholar-input {
            width: 100%;
            padding: 5px;
            border: 1px solid black;
            border-radius: 5px;
        }
        .submit_button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-top: 5px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .submit_button:hover {
            background-color: #45a049;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }
        .otp-section {
            display: none; 
            margin-top: 5px; 
        }
    </style>
    <script>
        let scholarNumber; // Declare scholarNumber here for global access

        function showModal(name, phone) {
            const modal = document.getElementById('myModal');
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalPhone').textContent = phone;
            modal.style.display = "block";
        }

        function confirmDetails() {
            alert("OTP sent to the number.");
            document.getElementById('otpSection').style.display = 'block';
            document.getElementById('submit').style.display = 'none';
            document.getElementById('myModal').style.display = "none";
        }

        function declineDetails() {
            alert("Please visit administration.");
            document.getElementById('myModal').style.display = "none";
        }

        function handleSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(event.target); // Get form data
            scholarNumber = formData.get('scholar_number'); // Store scholar number

            fetch('check_scholar.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const name = doc.getElementById('name')?.textContent; // Use optional chaining
                const phone = doc.getElementById('phone')?.textContent;

                if (name && phone) {
                    showModal(name, phone);
                } else {
                    alert("Invalid scholar number.");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function verifyOTP(event) {
            event.preventDefault();

            const enteredOTP = document.getElementById('otpInput').value;

            // Simulating OTP verification
            if (enteredOTP === "1234") {
                alert("User verified!");
                document.getElementById('updateModal').style.display = "block"; // Show update modal
                document.getElementById('otpSection').style.display = 'none'; // Hide OTP section
            } else {
                alert("Invalid OTP. Please try again.");
            }
        }

        function updateDetails(event) {
            event.preventDefault(); // Prevent default form submission

            const hostelNo = document.getElementById('hostel_no').value;
            const roomNo = document.getElementById('room_no').value;
            const guardianNo = document.getElementById('guardian_no').value;
            const guardianName = document.getElementById('guardian_name').value;

            // Send the update to the server
            fetch('update_scholar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    scholarNumber: scholarNumber,
                    hostelNo: hostelNo,
                    roomNo: roomNo,
                    guardianNo: guardianNo,
                    guardianName: guardianName
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Details updated successfully.");
                    document.getElementById('updateModal').style.display = "none";
                } else {
                    alert("Error updating details.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div class="container">
<<<<<<< HEAD
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/en/4/4f/Maulana_Azad_National_Institute_of_Technology_Logo.png" alt="Logo">
            <h1>MANIT Assist</h1>
        </div>

        <form method="post" onsubmit="handleSubmit(event)">
            <div class="scholar-no" id="scholarSection">
                <label for="scholar-number" class="form-label">Scholar Number:</label>
                <input type="text" class="scholar-input" name="scholar_number" id="scholarnum" required>

                <div id="otpSection" class="otp-section">
                    <label for="otp-entry" class="form-label">OTP:</label>
                    <input type="text" id="otpInput" class="scholar-input">
                    <button class="submit_button" onclick="verifyOTP(event)">Verify</button>
                </div>

                <button id="submit" class="submit_button">Continue</button>
            </div>
=======
        <h1>Account Registration</h1>
        <form method="POST" action="account.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="scholar_number" placeholder="Scholar Number" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="text" name="hostel_name" placeholder="Hostel Number" required>
            <input type="text" name="room_no" placeholder="Room Number" required>
            <input type="tel" name="guardian_no" placeholder="Guardian Phone Number" required>
            <input type="text" name="guardian_name" placeholder="Guardian Name" required>
            <button type="submit">Register</button>
>>>>>>> 7209c181bab8ce330704d659e1cc1c5045076d64
        </form>
    </div>

    <!-- Modal for displaying scholar details -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('myModal').style.display='none'" style="float:right;cursor:pointer;font-size:30px;">&times;</span>
            <p><b>Name: </b><span id="modalName"></span></p>
            <p><b>Phone no: </b><span id="modalPhone"></span></p>
            <button class="submit_button" onclick="confirmDetails()">Correct</button>
            <button class="submit_button" onclick="declineDetails()">Not Correct</button>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <h2>Update Your Details</h2>
            <form id="updateForm" onsubmit="updateDetails(event)">
                <label for="hostelNo" class="form-label">Hostel No:</label>
                <input type="text" id="hostel_no" class="scholar-input" required>

                <label for="roomNo" class="form-label">Room No:</label>
                <input type="text" id="room_no" class="scholar-input" required>

                <label for="guardianNo" class="form-label">Guardian No:</label>
                <input type="text" id="guardian_no" class="scholar-input" required>

                <label for="guardianName" class="form-label">Guardian Name:</label>
                <input type="text" id="guardian_name" class="scholar-input" required>

                <button type="submit" class="submit_button">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
