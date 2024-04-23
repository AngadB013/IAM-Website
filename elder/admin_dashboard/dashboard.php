<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAM System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f7ff;
        }

        header {
            background-color: #3cb371;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .search-container {
            display: flex;
            align-items: center;
        }

        .search-container input[type="text"] {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .search-container .search-icon {
            color: #3cb371;
            margin-right: 10px;
            cursor: pointer;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-left: 10px;
        }

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
    </style>
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
            <img src="logo1.png" alt="Logo" class="logo">
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
        <div class="card">
            <h3>Visitor access</h3>
        </div>
        <div class="card">
            <h3>User Authentication</h3>
        </div>
        <div class="card">
            <h3>Authorisation</h3>
        </div>
        <div class="card">
            <h3>Caregiver access</h3>
        </div>
        <div class="card">
            <h3>Threat Monitoring and Response</h3>
        </div>
    </div>
</body>
</html>