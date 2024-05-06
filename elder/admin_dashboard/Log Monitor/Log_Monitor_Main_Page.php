<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="Log_Monitor.css">
    <script src="Log_Monitor.js"></script>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: ../login/itlogin.php");
        exit();
    }
    ?>
    <header>
        <div class="logo-container">
            <img src="#" alt="Logo" class="logo">
            <h1>IAM System</h1>
        </div>
        <div class="search-container">
            <input type="text" placeholder="Search">
            <i class="fas fa-search search-icon"></i>
            <div class="user-info">
                <div><?php echo $_SESSION['email']; ?></div>
                <div>IT Admin</div>
            </div>
        </div>
    </header>
    <div class="dashboard">
        <div class="Log_Monitor_container" id="Log_Monitor_container_1">
            <div class="card" id="Warning counts">
                <h3> Warning counts </h3>
                <p class="Update_Label">Last update 8 hours ago</p>
            </div>
            <div class="card" id="Error counts">
                <h3> Error counts </h3>
                <p class="Update_Label">Last update 8 hours ago</p>
            </div>
            <div class="card" id="CPU Performance">
                <h3> CPU Performance </h3>
                <p class="Update_Label">Last update 8 hours ago</p>
            </div>
        </div>
        <div class="Log_Monitor_container" id="Log_Monitor_container_2">
            <div class="card" id="Internet_Trafic">
                <img src="#" alt="Internet Traffic" class="image" id="Internet_Traffic_Image">
                <button class="btn" id="Internet_Traffic_Button"><a href="Internet_Traffic.php">Internet Traffic</button>
            </div>
            <div class="card" id="Log_Monitor">
                <img src="#" alt="Log Monitor" class="image" id="Log_Monitor_Image">
                <button class="btn" id="Log_Monitor_Button"><a href="Log_Monitor.php">Log Monitor</button>
            </div>
        </div>
    </div>
</body>
</html>