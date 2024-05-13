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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="navbar.css"/>
</head>
<style>
     .dashboard {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        
        .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
            max-width: 800px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .card:hover {
            background-color: #f2f2f2;
        }
        
        .card a {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit text color */
        }
        
</style>
<body>

    <header>
        <div class="logo-container">
            <img src="logo1.png" alt="Logo" class="logo">
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

    <script>
        document.getElementById("userDropdown").addEventListener("click", function() {
            var dropdownMenu = document.getElementById("dropdownMenu");
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });
    </script>

    <!--Navbar ends-->
    <div class="dashboard">
        <div class="card">
            <a href="userauth.php"><h3>Visitor access</h3></a>
        </div>
        <div class="card">
            <a href="user_authentication/userauth.php"><h3>User Authentication</h3></a>
        </div>
        <div class="card">
            <a href="http://localhost/phpmyadmin/index.php?route=/server/privileges&db=aged_care_db&checkprivsdb=aged_care_db&viewing_mode=db"><h3>Authorisation</h3></a>
        </div>
        <div class="card">
            <a href="userauth.php"><h3>Caregiver access</h3></a>
        </div>
        <div class="card">
            <a href="Log_Monitor/Log_Monitor_Main_Page.php"><h3>Threat Monitoring and Response</h3></a>
        </div>
    </div>

</body>
</html>