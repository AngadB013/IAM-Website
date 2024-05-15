<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login/itlogin.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "aged_care_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Reset password if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password']) && $_POST['reset_password'] === 'true') {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Retrieve user's name
    $user_name = '';
    $user_query = mysqli_query($conn, "SELECT name FROM carestaff WHERE id = $user_id");
    if ($user_query && mysqli_num_rows($user_query) > 0) {
        $user_data = mysqli_fetch_assoc($user_query);
        $user_name = $user_data['name'];
    }

    // Prepare the SQL statement to prevent SQL injection
    $sql = "UPDATE carestaff SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Password reset successfully.";

        // Log the action to the database
        $action_description = "Password reset for user: " . $user_name . " (ID: " . $user_id . ")";
        $user_email = $_SESSION['email'];
        $action_time = date("Y-m-d H:i:s");

        $log_sql = "INSERT INTO log_entries (action_time, action_description, user_email) VALUES ('$action_time', '$action_description', '$user_email')";

        if (mysqli_query($conn, $log_sql)) {
            $success_message .= " Action logged successfully.";
        } else {
            $error_message = "Error logging password reset: " . mysqli_error($conn);
        }

        // Log the action to text file
        $log_file = "log.txt";
        $log_entry = date("Y-m-d H:i:s") . " - " . $action_description . " by " . $_SESSION['email'] . "\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    } else {
        $error_message = "Error resetting password: " . mysqli_stmt_error($stmt);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // Reset the success and error messages if the form is not submitted
    $success_message = $error_message = '';
}


// Retrieve list of users
$sql = "SELECT * FROM carestaff";
$result = mysqli_query($conn, $sql);

// Close connection
mysqli_close($conn);
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
    <link rel="stylesheet" href="../leftbar.css">
    
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
            margin-top: -30px;
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }

        /* Styles for the search bar */
        .search-bar1 {
            border: 1px solid #ccc;
            width: 200px;
            margin-left: auto;
            
        }

        .search-bar1 input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 4px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .sub-text{
            color:grey;
            font-size: 12px;
            margin-bottom:20px;
        }

        /* Styles for the table-like structure */
        .user-table {
            margin: 20px 30px; /* Add some margin and move the table right */
            border-collapse: collapse; /* Collapse table borders */
            width: calc(100% - 50px); /* Adjust width to fit the empty space */
            font-family: 'Roboto', sans-serif; /* Use Roboto font */
        }

        .user-table th,
        .user-table td {
            border: 1px solid #ddd; /* Add border */
            padding: 8px; /* Add padding */
            text-align: left; /* Align text left */
        }

        .user-table th {
            background-color: #f2f2f2; /* Add background color to header */
        }

        /* Styles for the reset password modal */
    #resetModal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        border-radius: 8px;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    }

    .modal-content h2 {
        margin-top: 0;
        color: #333;
    }

    .modal-content input[type="password"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .modal-content button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-content button:hover {
        background-color: #3e8e41;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Styles for the reset button */
    .reset-button {
        background-color: #4CAF50; /* Green color */
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .reset-button:hover {
        background-color: #3e8e41; /* Darker green on hover */
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

    <div class="container">
    <div class="search-bar1">
        <input type="text" placeholder="Search by name or email">
    </div>
    <h2>Reset User Password</h2>
    <p class="sub-text">Once passwored is reset, it cannot be retrieved!!</p>
    <?php if (isset($success_message)) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form id="resetForm" method="post">
        <input type="hidden" id="userId" name="user_id">
        <input type="hidden" id="password" name="new_password">
    </form>

    <table class="user-table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Position</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['position']; ?></td>
                <td>
                    <button onclick="openResetModal(<?php echo $row['id']; ?>)" class="reset-button">Reset Password</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div id="resetModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeResetModal()">&times;</span>
        <h2>Reset Password</h2>
        <input type="password" id="newPassword" placeholder="Enter new password">
        <button onclick="resetPassword()">Reset</button>
    </div>
</div>

<script>
     document.getElementById("userDropdown").addEventListener("click", function() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    });

    function openResetModal(userId) {
        document.getElementById('userId').value = userId;
        document.getElementById('resetModal').style.display = 'block';
    }

    function closeResetModal() {
        document.getElementById('resetModal').style.display = 'none';
    }

    function resetPassword() {
    var newPassword = document.getElementById('newPassword').value;
    var userId = document.getElementById('userId').value;
    if (newPassword.trim() !== '') {
        document.getElementById('password').value = newPassword;
        var form = document.getElementById('resetForm');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'reset_password';
        hiddenInput.value = 'true';
        form.appendChild(hiddenInput);
        form.submit();
        form.removeChild(hiddenInput); // Remove the hidden input after submission
    } else {
        alert('Please enter a new password.');
    }
    }

    // Add an event listener to the form submission
    document.getElementById('resetForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting normally
        var xhr = new XMLHttpRequest();
        xhr.open('POST', event.target.action, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert('%cPassword reset successfully.', 'color: green; font-weight: bold;');
                    closeResetModal();
                } else {
                    alert('%cError resetting password: ' + xhr.statusText, 'color: red; font-weight: bold;');
                }
            }
        };
        xhr.send(new FormData(event.target));
    });
</script>
</body>
</html>
