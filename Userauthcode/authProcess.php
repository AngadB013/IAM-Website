<?php
session_start();
?>
<?php
if (isset($_POST["auth"])){
    $auth = $_POST["auth"];
   
    require_once ("settings.php");

    $dbConnect = @mysqli_connect($host, $user, $pswd,
    $dbnm)
    or die("<p>Unable to connect to the database server.</p>"
    . "<p>Error code " . mysqli_connect_errno()
    . ": " . mysqli_connect_error() . "</p>");
    echo "<p>Successfully connected to the database server.</p>";

    $auth = mysqli_real_escape_string($dbConnect, $auth);

    $sqlString = "SELECT authKey FROM userAuth WHERE authKey = '$auth' ";

    $queryResult = @mysqli_query($dbConnect, $sqlString)
        or die("<p>Unable to execute query.</p>"
            . "<p>Error code " . mysqli_errno($dbConnect)
            . ": " . mysqli_error($dbConnect)) . "</p>";
    echo "<p>Successfully executed query.</p>";

    $rowCount = mysqli_num_rows($queryResult);

   if ($rowCount > 0){
    $row = mysqli_fetch_assoc($queryResult);
    $authcode = $row['authKey'];

    $_SESSION['authKey'] = $authcode;
	$authcode = $_SESSION["authKey"];

    header("Location: success.php");
    exit();
   
   }else{
        echo "<p>Invalid Code</p>";
		header("location:authKey.php");
   }

}else {
    echo "<p>Enter the Authentication code.</p>";
}
?>