<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <link rel="stylesheet" href="style2.css">
    </head>
<body>
<?php
if (isset($_POST["auth"])) {
    $userEnteredAuth = $_POST["auth"];

    // Retrieve the stored authentication code from the session
    if (isset($_SESSION["auth_code"])) {
        $storedAuthCode = $_SESSION["auth_code"];

        // Check if the user-entered authentication code matches the stored one
        if ($userEnteredAuth == $storedAuthCode) {
            // Authentication successful, redirect to success.php
            header("Location: success.php");
            exit();
        } else {
            // Invalid authentication code, redirect back to authKey.php
            echo "<p>Invalid Code</p>";
            header("Location: authKey.php");
            exit();
        }
    } else {
        // Session auth_code not set (authentication code missing), redirect back to authKey.php
        echo "<p>Authentication code missing.</p>";
        header("Location: authKey.php");
        exit();
    }
} else {
    // No authentication code submitted, display message
    echo "<p>Enter the Authentication code.</p>";
}
?>
</body>
</html>