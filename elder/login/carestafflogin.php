<?php
// Start session
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "", "103166497_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email, password, and pin are filled
    if (empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["pin"])) {
        echo "<script>alert('Please enter email, password, and PIN');</script>";
    } else {
        // Sanitize input data
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        // Serialize the array of PIN inputs into a string
        $pin = implode('', array_map('intval', $_POST['pin']));

        // Insert data into staff table
        $sql = "INSERT INTO staff (email, password, pin) VALUES ('$email', '$password', '$pin')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to dashboard or another page
            header("Location: ../insert.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Care Staff Login</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
        }

        .login-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px; /* Reduced padding */
            text-align: center;
            width: 400px; /* Increased width of the box */
        }

        .login-container img.logo {
            display: block;
            margin: 0 auto;
            width: 150px; /* Increased logo width */
            height: auto; /* Maintain aspect ratio */
            margin-top: -20px;
            margin-bottom: -20px;
        }

        .login-container h1 {
            margin-top: 0;
            margin-bottom: 20px; /* Reduced margin */
            font-size: 24px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 10px; /* Reduced margin */
        }

        .login-container label {
            font-size: 16px;
            margin-bottom: 6px;
            display: block;
            font-weight: bold; /* Make label input borders bold */
            text-align: left; /* Align label text to the left */
        }

        .login-container input[type="email"],
        .login-container input[type="password"],
        .login-container input[type="text"] {
            width: calc(100% - 20px); /* Reduced input width to accommodate border */
            padding: 10px;
            margin-bottom: 10px; /* Reduced margin */
            border: 2px solid #ccc; /* Set border to bold */
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-container .pin-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px; /* Reduced margin */
        }

        .login-container .pin-input {
            width: 50px;
            height: 50px;
            padding: 10px;
            margin: 0 5px;
            border: 2px solid #ccc;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            width: calc(100% - 20px); /* Reduced button width */
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container .message {
            margin-top: 20px;
            color: #007bff;
            font-size: 18px;
        }

        .login-container .contact-info {
            font-size: 14px;
            margin-top: 10px; /* Adjusted margin */
            text-align: center;
            color: #888; /* Gray color */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logo.jpg" alt="Logo" class="logo">
        <h1>Care Staff Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="pin">PIN (4 digits):</label><br>
            <div class="pin-container">
                <input type="text" class="pin-input" name="pin[]" maxlength="1" pattern="\d" required>
                <input type="text" class="pin-input" name="pin[]" maxlength="1" pattern="\d" required>
                <input type="text" class="pin-input" name="pin[]" maxlength="1" pattern="\d" required>
                <input type="text" class="pin-input" name="pin[]" maxlength="1" pattern="\d" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="contact-info">
            Please contact the IT department for any issues, including forgotten passwords, emails, or PINs, or for any other assistance. You can reach us at +61 XXX or via email at it@example.com.
        </div>
    </div>
</body>
</html>