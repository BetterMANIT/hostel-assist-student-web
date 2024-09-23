<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANIT Assist</title>
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
        margin-bottom: 5px;
    }

    .otp-section{
        display: none;
    }
    .otp-input {
        width: 50%;
        padding: 5px;
        border: 1px solid black;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .button{
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
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
        font-size: 10px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .button:hover {
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
    </style>

    <script>
        function showAlert(name, phone) {
            if (name && phone) {
                alert(`Name: ${name}\nPhone: ${phone}`);
            } else {
                alert("Scholar number does not exist.");
            }
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

                <div class="otp-section">
                    <label for="otp-entry" class="form-label">OTP:</label>
                    <input type="text" class="otp-input">
                    <button class="verify">Verify</button>
                </div>

                <button class="button" name="submit">Continue</button>
            </div>

        </form>
    </div>

    <script>
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
                showAlert(name, phone);
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

