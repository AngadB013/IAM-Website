<?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: ../login/itlogin.php");
        exit();
    }
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
            <a href="userauth.php"><h3>Home</h3></a>
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
            <li><a href="../dashboard.php">Dashboard</a></li>
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
</body>
</html>