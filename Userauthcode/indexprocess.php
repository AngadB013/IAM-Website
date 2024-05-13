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
    <h1>User Authentication</h1>
    <?php
    if (isset($_POST["email"]) && isset($_POST["pword"])) {
        $email = $_POST["email"];
        $pword = $_POST["pword"];

    

    require_once ("settings.php");


    $dbConnect = @mysqli_connect($host, $user, $pswd,
    $dbnm)
    or die("<p>Unable to connect to the database server.</p>"
    . "<p>Error code " . mysqli_connect_errno()
    . ": " . mysqli_connect_error() . "</p>");
    echo "<p>Successfully connected to the database server.</p>";

    $email = mysqli_real_escape_string($dbConnect, $email);
    $pword = mysqli_real_escape_string($dbConnect, $pword);

    $sqlString = "SELECT user_email, password FROM userAuth WHERE user_email = '$email' AND password = '$pword'";

    $queryResult = @mysqli_query($dbConnect, $sqlString)
        or die("<p>Unable to execute query.</p>"
            . "<p>Error code " . mysqli_errno($dbConnect)
            . ": " . mysqli_error($dbConnect)) . "</p>";
    echo "<p>Successfully executed query.</p>";

    $rowCount = mysqli_num_rows($queryResult);

    if ($rowCount > 0) {
        // Login successful, retrieve user email
        $row = mysqli_fetch_assoc($queryResult);
        $userEmail = $row['user_email'];

        // Generate authentication code (replace with your actual code generation logic)
        $authCode = generateAuthCode(); // Function to generate authentication code

        // Store authCode and userEmail in session for use in authKey.php
        $_SESSION['auth_code'] = $authCode;
        $_SESSION['user_email'] = $userEmail;

        // Send email with authentication code
        $subject = 'Authentication Code';
        $message = 'Your authentication code is: ' . $authCode;
        $headers = 'From: userauth@agecare.com'; // Replace with a valid email address

        // Call the custom sendDummyMail function to save email details to a text file
        sendDummyMail($userEmail, $subject, $message, $headers);

        // Redirect to authKey.php (or any other destination)
        header("Location: authKey.php");
        exit();
    } else {
        echo "<p>Invalid credentials</p>";
        header("Location: index.php"); // Redirect back to login page
        exit();
    }

    mysqli_free_result($queryResult);
    mysqli_close($dbConnect);

} else {
    echo "<p>Enter both email address and password</p>";
}

function generateAuthCode() {
    return mt_rand(1000, 9999); // Generate a random 4-digit code
}

// Define a custom mail function for testing (saves email content to a text file)
function sendDummyMail($to, $subject, $message, $headers = '') {
    // Generate a unique filename for the email text file (e.g., using timestamp)
    $timestamp = date('Y-m-d_H-i-s');
    $filename = "emails/email_$timestamp.txt";

    // Construct the email content
    $emailContent = "To: $to\n";
    $emailContent .= "Subject: $subject\n";
    $emailContent .= "Headers: $headers\n\n";
    $emailContent .= "Message:\n$message\n";

    // Save the email content to a text file
    $fileSaved = file_put_contents($filename, $emailContent);

    if ($fileSaved !== false) {
        echo "<p>Dummy email saved to file: $filename</p>";
        return true; // Email "sent" successfully (saved to file)
    } else {
        echo "<p>Failed to save dummy email to file.</p>";
        return false; // Email "sending" failed
    }
}
    ?>
    


</body>
</html>