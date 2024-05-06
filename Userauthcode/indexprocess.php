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
        // Assuming the login is successful
        $row = mysqli_fetch_assoc($queryResult);
        $userid = $row['user_id'];


        // Set the user ID in the session variable
       $_SESSION['user_id'] = $userid;
	   $userid = $_SESSION["user_id"];

       
	
	   $_SESSION["email"] = $email;//creates the session variable	
       $_SESSION["pword"] = $pword;//creates the session variable	
	   $email = $_SESSION["email"];//copies the value to a variable
	   $pword = $_SESSION["pword"];//copies the value to a variable

       header("Location: authKey.php");
        exit();
    } else{
        echo "<p>Invalid credentials</p>";
        sleep(2);
		header("location:index.php");
    }

    mysqli_free_result($queryResult);
    mysqli_close($dbConnect);

} else {
    echo "<p>Enter both email address and password</p>";
}
    ?>
    


</body>
</html>