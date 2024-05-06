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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_MFA'])) {
    $user_id = $_POST['user_id'];
    $mfa_status = $_POST['MFA_status'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "UPDATE carestaff SET MFA = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "si", $mfa_status, $user_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "MFA status updated successfully.";

        // Retrieve user's email
        $user_email = '';
        $user_query = mysqli_query($conn, "SELECT email FROM carestaff WHERE id = $user_id");
        if ($user_query && mysqli_num_rows($user_query) > 0) {
            $user_data = mysqli_fetch_assoc($user_query);
            $user_email = $user_data['email'];
        }

        // Log the action to the database
        $action_description = "MFA status updated for user: (ID: " . $user_id . ", Email: " . $user_email . ") to " . $mfa_status;
        $action_time = date("Y-m-d H:i:s");
        $admin_email = $_SESSION['email'];

        $log_sql = "INSERT INTO log_entries (action_time, action_description, user_email) VALUES ('$action_time', '$action_description', '$admin_email')";

        if (mysqli_query($conn, $log_sql)) {
            $success_message .= " Action logged successfully.";
        } else {
            $error_message = "Error logging MFA status update: " . mysqli_error($conn);
        }

        // Log the action to text file
        $log_file = "log.txt";
        $log_entry = date("Y-m-d H:i:s") . " - " . $action_description . " by " . $_SESSION['email'] . "\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    } else {
        $error_message = "Error updating MFA status: " . mysqli_stmt_error($stmt);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Retrieve list of users
$sql = "SELECT * FROM carestaff";
$result = mysqli_query($conn, $sql);
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
                    <a href="../elder/index.html">Logout</a>
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
            <a href=""><h3>Threat Monitoring and Response</h3></a>
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
        <h2>Enable Multi-Factor Authentication</h2>
        <p class="sub-text">Once MFA is enabled, the user will receive a message!!</p>

        <!-- Display success/error messages -->
        <?php if (isset($success_message)) : ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post">
            <table class="user-table">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>MFA Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <select name="MFA_status">
                                <option value="Yes" <?php echo $row['MFA'] === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                <option value="No" <?php echo $row['MFA'] === 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="update_MFA">Update</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </form>
    </div>

    <script>
     document.getElementById("userDropdown").addEventListener("click", function() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    });
    </script>

</body>
</html>
