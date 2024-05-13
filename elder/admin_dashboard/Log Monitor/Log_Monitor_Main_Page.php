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
    <script src="Log_Main_Page.js"></script>
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
    <div class="monitor_dashboard">
        <div class="metric_board">
            <div class="Log_Monitor_container" id="Log_Monitor_container_1">
                <div class="monitor_card" id="Warning counts">
                    <h3> Warning counts </h3>
                    <p class = "Metric_Count" id="Warning_Count">0</p>
                    <p id="Last_Update_Label_1"></p>

                </div>
                <div class="monitor_card" id="Error counts">
                    <h3> Error counts </h3>
                    <p class = "Metric_Count" id="Error_Count">0</p>
                    <p id="Last_Update_Label_2"></p>
                </div>
                <div class="monitor_card" id="CPU Performance">
                    <h3> CPU Performance </h3>
                    <p class = "Metric_Count" id="CPU_Performance">0</p>
                    <p id="Last_Update_Label_3"></p>
                </div>
            </div>
            <div class="Log_Monitor_container" id="Log_Monitor_container_2">
                <div class="monitor_card" id="Internet_Traffic">
                    <img src="internet traffic.png" alt="Internet Traffic" class="image" id="Internet_Traffic_Image">
                    <button class="btn" id="Internet_Traffic_Button"><a href="Internet_Traffic.php">Internet Traffic</button>
                </div>
                <div class="monitor_card" id="Log_Monitor">
                    <img src="Log Monitor.png" alt="Log Monitor" class="image" id="Log_Monitor_Image">
                    <button class="btn" id="Log_Monitor_Button"><a href="LogMonitor.php">Log Monitor</button>
                </div>
            </div>
        </div>
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
</body>
</html>