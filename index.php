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

        .logo h1 .top, .logo h1 .bottom {
            margin-left: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .scholar-input, .otp-input {
            width: 100%;
            padding: 5px;
            border: 1px solid black;
            border-radius: 5px;
        }

        .submit_button, .verify {
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

        .submit_button:hover, .verify:hover {
            background-color: #45a049;
        }

        .modal, .update-modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.4); 
            padding-top: 60px; 
        }

        .modal-content, .update-modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 400px; /* Max width for larger screens */
            margin: 0; /* Remove margins */
        }

        .modal-content p, .update-modal-content label {
            text-align: left;
        }

        .choice {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            margin-left: 20px;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .update-modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.4); 
        }

        .update-modal-content {
            background-color: #fff;
            padding: 20px;
            align-items: center;
            border: 1px solid #888;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            margin: 0; /* No margins */
            display: flex;
            flex-direction: column; /* Column layout for labels and inputs */
            justify-content: center; /* Center content vertically */
        }

        .update-modal-content h2 {
            text-align: center; /* Center the header text */
            margin-bottom: 20px; /* Space below the header */
            font-size: 20px; /* Adjust the font size */
            color: #333; /* Optional: Change the color of the header */
        }

        .update-modal-content label {
            display: block; /* Block display for labels */
            margin-bottom: 5px; /* Spacing for labels */
            font-weight: bold; /* Bold labels */
        }

        .update-modal-content input {
            width: 70%; /* Full width for inputs */
            padding: 5px; /* Padding for inputs */
            border: 1px solid black; /* Border for inputs */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 10px; /* Spacing for inputs */
        }

        .update-modal-content button {
            background-color: #4CAF50; /* Button color */
            width: 150px;
            color: white; /* Text color */
            padding: 10px 20px; /* Button padding */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            font-size: 15px; /* Font size */
            font-weight: bold; /* Bold text */
            transition: background-color 0.3s ease; /* Transition effect */
        }

        .update-modal-content button:hover {
            background-color: #45a049; /* Darker button color on hover */
        }


        @media (max-width: 768px) {
            .update-modal-content {
                width: 100%; /* Fill entire width on mobile */
                height: 100%; /* Fill entire height on mobile */
                margin: 0; /* No margins */
                border-radius: 0; /* No rounded corners */
                padding: 20px; /* Padding inside the modal */
            }
        }

        .modal, .update-modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.4); 
            padding: 0; /* Remove any padding */
            margin: 0; /* Remove any margin */
        }

        .modal-content, .update-modal-content {
            background-color: #fefefe;
            margin: auto; /* Center modal */
            padding: 20px; /* Padding inside the modal */
            border: 1px solid #888;
            width: 90%; /* Adjust width as needed */
            max-width: 500px; /* Maximum width of the modal */
            border-radius: 5px; /* Optional: rounded corners */
            position: relative; /* Positioning context for centering */
            top: 50%; /* Position from the top */
            transform: translateY(-50%); /* Center vertically */
        }

    </style>
    <script>
        let scholarNumber;

        function showModal(name, phone) {
            const modal = document.getElementById('myModal');
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalPhone').textContent = phone;
            modal.style.display = "block";
        }

        function confirmDetails() {
            alert("OTP sent to the number.");
            document.getElementById('otpSection').style.display = 'block'; // Show OTP section
            document.getElementById('submit').style.display = 'none';
            document.getElementById('myModal').style.display = "none";
        }

        function declineDetails() {
            alert("Please visit administration.");
            document.getElementById('myModal').style.display = "none";
        }

        function handleSubmit(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            fetch('scholar_system.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const name = doc.getElementById('name').textContent;
                const phone = doc.getElementById('phone').textContent;

                if (name && phone) {
                    scholarNumber = formData.get('scholarNumber');
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

                fetch(`scholar_system.php`, {
                    method: 'POST',
                    body: new URLSearchParams({ scholarNumber: scholarNumber }),
                })
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const status = doc.getElementById('status').textContent;

                    if (status === "") {
                        document.getElementById('updateModal').style.display = "block";
                        document.getElementById('updateScholarNumber').value = scholarNumber; // Set the scholar number in the update form
                    } else {
                        alert("User already verified!");
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert("Invalid OTP. Please try again.");
            }
        }

        function updateScholarInfo(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            fetch('scholar_system.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                alert("Details updated successfully!"); // Show success alert
                document.getElementById('updateModal').style.display = "none"; // Close modal
            })
            .catch(error => console.error('Error:', error));
        }

    </script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/en/4/4f/Maulana_Azad_National_Institute_of_Technology_Logo.png" alt="Logo">
            <h1>
                <span class="top">MANIT</span>
                <span class="bottom">Assist</span>
            </h1>
        </div>

        <form method="post" onsubmit="return handleSubmit(event)">
            <div class="scholar-no" id="scholarSection">
                <label for="scholar-number" class="form-label">Scholar Number:</label>
                <input type="text" class="scholar-input" name="scholarNumber" id="scholarnum">

                <div id="otpSection" class="otp-section" style="display: none;"> <!-- Initially hidden -->
                    <label for="otp-entry" class="form-label">OTP:</label>
                    <input type="text" id="otpInput" class="otp-input">
                    <button class="verify" onclick="verifyOTP(event)">Verify</button>
                </div>

                <button id="submit" class="submit_button" name="submit">Continue</button>
            </div>
        </form>
    </div>


    <!-- Modal for details -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('myModal').style.display='none'" style="float:right;cursor:pointer;font-size:30px;">&times;</span>
            <p><b>Name: </b><span id="modalName"></span></p>
            <p><b>Phone no: </b><span id="modalPhone"></span></p>
            <button class="choice" onclick="confirmDetails()">Correct</button>
            <button class="choice" onclick="declineDetails()">Not Correct</button>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="update-modal">
        <div class="update-modal-content">
            <h2>Update Your Details</h2> <!-- Header for the modal -->
            <label for="hostel_no" class="form-label">Hostel No:</label>
            <input type="text" name="hostel_no" required>

            <label for="room_no" class="form-label">Room No:</label>
            <input type="text" name="room_no" required>

            <label for="guardian_no" class="form-label">Guardian No:</label>
            <input type="text" name="guardian_no" required>

            <label for="guardian_name" class="form-label">Guardian Name:</label>
            <input type="text" name="guardian_name" required>

            <input type="hidden" name="scholarNumber" id="updateScholarNumber">
            <button type="submit">Update</button>
        </div>
    </div>
</body>
</html>
