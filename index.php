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
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
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

        .logo h1 .top{
            margin-left: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .logo h1 .bottom{
            margin-left: 40px;
            font-size: 24px;
            font-weight: bold;
        }

        .form-group {
            
        }

        p{
            text-align: center;
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

        .otp-input {
            width: 50%;
            padding: 5px;
            border: 1px solid black;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .submit_button{
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

        .verify{
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            margin-left: 20px;
            font-size: 15px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .submit_button:hover {
            background-color: #45a049;
        }

        .or {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            float: left;
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
        }

        .choice{
            float: left;
            display: block;
            background-color: #04AA6D;
            color: white;
            text-align: center;
            padding: 10px 13px;
            text-decoration: none;
            font-size: 10px;
        }
        .otp-section {
            display: none; 
            margin-top: 5px; 
        }
    </style>
    <script>
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

            const formData = new FormData(event.target);
            fetch('check_scholar.php', {
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
                    showModal(name, phone);
                } else {
                    alert("Invalid scholar number.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div class="container">

        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/en/4/4f/Maulana_Azad_National_Institute_of_Technology_Logo.png" alt="Leaf Icon">
            <h1>
                <span class="top">MANIT</span> 
                <span class="bottom">Assist</span>
            </h1>
        </div>


        <form action="login.php" method="post" onsubmit="return handleSubmit(event)">

            <div class="scholar-no">
                <label for="scholar-number" class="form-label">Scholar Number:</label>
                <input type="text" class="scholar-input" name="scholarNumber">

                <div id="otpSection" class="otp-section">
                    <label for="otp-entry" class="form-label">OTP:</label>
                    <input type="text" class="otp-input">
                    <button class="verify">Verify</button>
                </div>

                <button id="submit" class="submit_button" name="submit">Continue</button>
            </div>

        </form>
        </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span onclick="document.getElementById('myModal').style.display='none'" style="float:right;cursor:pointer;font-size:30px;background-color:'red';">&times;</span>
            <p>Name: <span id="modalName"></span></p>
            <p>Phone: <span id="modalPhone"></span></p>
            <button class="choice" onclick="confirmDetails()">Correct</button>
            <button class="choice" onclick="declineDetails()">Not Correct</button>
        </div>
    </div>

   
</body>
</html>
