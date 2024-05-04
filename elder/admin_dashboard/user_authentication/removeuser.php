<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login/itlogin.php");
    exit();
}

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "aged_care_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Remove user if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_user'])) {
    $user_id = $_POST['user_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM carestaff WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        $success_message = "User removed successfully.";
    } else {
        $error_message = "Error removing user: " . mysqli_error($conn);
    }
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
            margin-top: -30px;
            margin-bottom: 20px;
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

        /* Style for delete button */
        .delete-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #ff6666;
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
                    <li><a href="#">Access</a></li>
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
    <h2>Remove User</h2>
    <p class="sub-text">Once deleted, user account cannot be retrieved!!</p>
    <?php if (isset($success_message)) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table class="user-table">
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Action</th>
            </tr>
                <?php 
                $count = 1; // Initialize counter
                while ($row = mysqli_fetch_assoc($result)) : 
                ?>
                <tr>
                    <td><?php echo $count++; ?></td> <!-- Display counter -->
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td>
                            <form method="post" onsubmit="return confirmDelete(<?php echo $row['id']; ?>)">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="remove_user" class="delete-button">Delete</button>
                        </form>
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

        function confirmDelete(userId) {
        return confirm(`Are you sure you want to delete the user with ID ${userId}?`);
        }
    </script>

</body>
</html>