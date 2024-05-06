<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="Log_Monitor.css">
    <script src="./Log Monitor/Log_Monitor.js"></script>
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

    </div>
</body>
</html>