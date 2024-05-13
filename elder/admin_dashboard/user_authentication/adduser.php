<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login/itlogin.php");
    exit();
}

// Initialize variables for form fields
$name = $email = $department = $position = $phone_number = $password = $confirm_password = "";
$phone_number_error = "";

// Define arrays for dropdown options
$departments = array("Finance", "Medical", "Human Resources", "IT", "Operations");

// Validation and processing on form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $department = htmlspecialchars($_POST["department"]);
    $position = htmlspecialchars($_POST["position"]);
    $phone_number = htmlspecialchars($_POST["phone_number"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate phone number format
    if (!preg_match("/^\+\d{1,3}\d{9,}$/", $phone_number)) {
        $phone_number_error = "Please enter a valid phone number with country code.";
    }

    // Perform additional validation if needed

    // If all fields are valid, proceed to add user to the database
    if ($email && $password && $confirm_password && $password === $confirm_password && empty($phone_number_error)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Establish connection
        $conn = mysqli_connect("localhost", "root", "", "aged_care_db");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Insert user data into the carestaff table
        $sql = "INSERT INTO carestaff (name, email, department, position, phone_number, password)
                VALUES ('$name', '$email', '$department', '$position', '$phone_number', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            // Log the action to text file
            $log_file = "log.txt";
            $log_entry = date("Y-m-d H:i:s") . " - New user added: " . $name . " (" . $email . ") in department " . $department . " by " . $_SESSION['email'] . "\n";
            file_put_contents($log_file, $log_entry, FILE_APPEND);

            // Log the action to database
            $action_description = "New user added: " . $name . " (" . $email . ") in department " . $department;
            $user_email = $_SESSION['email'];

            $sql = "INSERT INTO log_entries (action_description, user_email) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "ss", $action_description, $user_email);
            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

            echo "User added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Close connection
        mysqli_close($conn);
    } else {
        echo "Please fill.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System - Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../navbar.css"/>
    <link rel="stylesheet" href="../leftbar.css"/>
    
</head>
<style>

    .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        form {
            display: grid;
            gap: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: auto;
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            margin-top: 5px;
        }

        .success-message {
            color: #28a745; /* Green color */
            font-size: 16px;
        }
</style>
<body>
    <header>
        <div class="logo-container">
            <img src="../logo1.png" alt="Logo" class="logo">
            <h1>IAM System</h1>
        </div>
        <div class="search-container">
                <!-- Notification icon -->
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search">
            </div>
            <div class="user-info">
                <div class="user-details" id="userDropdown">
                    <span class="user-circle"><?php echo getUserInitials($_SESSION['email']); ?></span>
                    <div class="user-email"><?php echo $_SESSION['email']; ?></div>
                </div>
                <div class="user-position">IT Admin</div>
                <!-- Dropdown menu -->
                <div class="dropdown" id="dropdownMenu">
                    <a href="index.html">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <?php
    function getUserInitials($email) {
        $name_parts = explode('@', $email);
        $initials = '';
        foreach (explode('.', $name_parts[0]) as $part) {
            $initials .= strtoupper($part[0]);
        }
        return $initials;
    }
    ?>

        <!-- Dashboard cards container -->
    <div class="dashboard">
        <div class="card">
            <a href="../dashboard.php"><h3>Home</h3></a>
        </div>
        <div class="card">
            <a href="" ><h3>Visitor access</h3></a>
        </div>
        <div class="card active">
            <a href="userauth.php"><h3>User Authentication</h3></a>
        </div>
        <div class="card">
            <a href=""><h3>Authorisation</h3></a>
        </div>
        <div class="card">
            <a href=""><h3>Caregiver access</h3></a>
        </div>
        <div class="card">
            <a href="../Log Monitor/Log_Monitor_Main_Page.php"><h3>Threat Monitoring and Response</h3></a>
        </div>
    </div>

    <!-- Left navbar -->
    <div class="navbar">
        <ul>
            <li><a href="allusers.php">Dashboard</a></li>
            <li>
                <a href="allusers.php">Users</a>
                <ul class="sub-menu">
                    <li><a href="adduser.php">Add Users</a></li>
                    <li><a href="removeuser.php">Remove Users</a></li>
                    <li><a href="resetuser.php">Reset Password</a></li>
                    <li><a href="mfa.php">MFA Access</a></li>
                </ul>
            </li>
            <li><a href="#">Settings</a></li>
            <!-- Add more main points here -->
        </ul>
    </div>

   <!-- Form for adding users -->
    <div class="container">
        <h2>Add User</h2>
            <!-- Success message -->
            <?php if (isset($success_message)) : ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
                <option value="Finance">Finance</option>
                <option value="Medical">Medical</option>
                <option value="Human Resources">Human Resources</option>
                <option value="Operations">Operations</option>
            </select>
            
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
            <span class="error-message"><?php echo $phone_number_error; ?></span>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <small>Please include at least one lowercase letter, one uppercase letter, one number, one special character (@$!%*?&), and ensure the password length is at least 7 characters.</small>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <input type="submit" value="Add User">

        </form>
    </div>

    <script>
        document.getElementById("userDropdown").addEventListener("click", function() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });

        function validatePassword() {
        var password = document.getElementById("password").value;
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/;
        if (!passwordPattern.test(password)) {
            alert("Please include at least one lowercase letter, one uppercase letter, one number, one special character (@$!%*?&), and ensure the password length is at least 7 characters.");
            return false;
        }
        return true;
        }
        
        function validateForm() {
            var isValid = true;
            isValid &= validatePassword();

            // Check if passwords match
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            if (password !== confirm_password) {
                alert("Passwords do not match.");
                return false; // Prevent form submission if passwords do not match
            }

            if (!isValid) {
                return false; // Prevent form submission if any other validation fails
            }
            
            return true; // Allow form submission if all validations pass
        }
        
        function showSuccessMessage() {
            alert("User account created successfully.");
        }

    </script>

</body>
</html>