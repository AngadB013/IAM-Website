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
    <link rel="stylesheet" href="Log_Monitor.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../leftbar.css">
    <script src="Log_Monitor.js">
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
    </script>
</head>
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
    <div class="filter_data_by_type_container">
        <button class="filter_Button" id="all_Log_Button">All logs</button>
        <button class="filter_Button" id="notice_Button">Notice</button>
        <button class="filter_Button" id="warn_Button">Warn</button>
        <button class="filter_Button" id="error_Button">Error</button>
    </div>
        <!-- Date range input fields -->
    <div class="filter_data_by_type_container">            
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate">
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate">
    </div>

    <div class="monitor_dashboard">
        <table id="logTable">
            <thead>
             <tr>
                    <th id="Timestamp">Timestamp</th>
                    <th id="Description">Description</th>
                </tr>
            </thead>
            <tbody id="logBody">

            </tbody>
        </table>
    </div>
            <!-- Left navbar -->
    <div class="navbar">
        <ul>
            <li>
                <a href="../dashboard.php"><h3>Home</h3></a>
            </li>
            <li>
                <a href="#" ><h3>Visitor access</h3></a>
            </li>
            <li>
                <a href="../user_authentication/userauth.php"><h3>User Authentication</h3></a>
            </li>
            <li>
                <a href="#"><h3>Authorisation</h3></a>
            </li>
            <li>
                <a href="#"><h3>Caregiver access</h3></a>
            </li>
            <li class="card active">
                <a href="../Log Monitor/Log_Monitor_Main_Page.php"><h3>Threat Monitoring and Response</h3></a>
            </li>
        </ul> 
    </div>
</div>
    </div>
</body>
</html>