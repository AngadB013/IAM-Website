<?php
$host = "localhost";
$user = "root";
$pswd = "";
$dbnm = "userauth";

$dbConnect = mysqli_connect($host, $user, $pswd,
$dbnm);

if (!$dbConnect) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
    phpinfo(); 
}

mysqli_close($dbConnect);
?>