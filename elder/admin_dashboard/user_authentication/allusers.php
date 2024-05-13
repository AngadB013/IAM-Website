<?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: ../login/itlogin.php");
        exit();
    }

    // Establish connection
    $conn = mysqli_connect("localhost", "root", "", "aged_care_db");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to fetch user data
    $sql = "SELECT name, email, position, department FROM carestaff";
    $result = mysqli_query($conn, $sql);

    // Get total number of users
    $total_users = mysqli_num_rows($result);

    // Close connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="../navbar.css"/>
    <link rel="stylesheet" href="../leftbar.css"/>
</head>
<style>
/* Styles for the table-like structure */
.user-table {
        margin: 20px 50px; /* Add some margin and move the table right */
        border-collapse: collapse; /* Collapse table borders */
        width: calc(100% - 450px); /* Adjust width to fit the empty space */
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

    /* Styles for the container */
    .container {
        margin-left: 250px; /* Adjust left margin */
    }

    /* Styles for the heading */
    .heading {
        font-size: 24px;
        margin-bottom: 10px;
        margin-top: 50px;
        display:flex;
        justify-content: space-between; /* Align items to the left and right */
        align-items: center; /* Align items vertically */
    }

    /* Styles for the total users */
    .total-users {
        font-size: 14px;
        color: #888;
        margin-bottom: 10px;
    }

    /* Styles for the search bar */
    .search-bar1 {
        margin-left: auto; /* Push to the furthest right */
    }

    .search-bar1 input[type="text"] {
        padding: 8px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Styles for the line */
    .line {
        width: calc(100% - 100px); /* Adjust width */
        margin-top: 20px;
        border-bottom: 4px solid #ddd;
        margin-left: 50px; /* Adjust left margin */
    }

    .sub-text{
        color:grey;
        font-size: 12px;
    }
</style>
<body>
    <header>
        <div class="logo-container">
            <img src="../logo1.png" alt="Logo" class="logo">
            <h1>IAM System</h1>
        </div>
        <div class="search-container">
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

    <script>
        document.getElementById("userDropdown").addEventListener("click", function() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });
    </script>

        <!-- Dashboard cards container -->
    <div class="dashboard">
        <div class="card">
            <a href="../dashboard.php"><h3>Home</h3></a>
        </div>
        <div class="card">
            <a href="#" ><h3>Visitor access</h3></a>
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
            <a href="Log_Monitor/Log_Monitor_Main_Page.php"><h3>Threat Monitoring and Response</h3></a>
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

   <!-- Container for user data -->
   <div class="container">
        <!-- Heading with total users -->
        <h2 class="heading">Users  
        <!-- Search bar -->
        <div class="search-bar1">
            <input type="text" placeholder="Search by name or email">
        </div></h2>
        | Total users: <?php echo ($total_users); ?>
        <p class="sub-text">Users listed here are currently registered within the company system.</p>
        
        
        <!-- Line -->
        <div class="line"></div>

        <!-- User data table -->
        <table class="user-table">
            <thead>
                <tr>
                    <th>No.</th> <!-- Number column -->
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Counter for numbering
                    $counter = 1;

                    // Fetch and display user data
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>"; // Number column
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["position"] . "</td>";
                            echo "<td>" . $row["department"] . "</td>";
                            echo "</tr>";
                            $counter++; // Increment counter
                        }
                    } else {
                        echo "<tr><td colspan='5'>No users found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>