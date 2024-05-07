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
    ?>
    <div class="formcontents">
    <form action = 'indexprocess.php' method = 'post'>
       <table>
       <tr>
          <td><label for="email">EMAIL:</label><br></td>
       </tr>

       <tr>
          <td><input type="text" id="email" name="email"><br></td>
       </tr>

       <tr>
          <td><label for="pword">PASSWORD:</label><br></td>
       </tr>

       <tr>
          <td><input type="password" id="pword" name="pword"><br></td>
       </tr>

       <tr>
       <td>
         <input type="submit" value="Submit">
         <input type="reset" value="Reset">
      </td>
      </tr>
       
       </table>




    </form>
    </div>

    <?php
    require_once ("settings.php");
//database connector
    $dbConnect = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("<p>Unable to connect to the database server.</p>"
    . "<p>Error code " . mysqli_connect_errno()
    . ": " . mysqli_connect_error() . "</p>");

    //table creation query
       $sqlUserAuth = "CREATE TABLE IF NOT EXISTS userAuth (
         user_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
         user_email VARCHAR(50) NOT NULL,
         password VARCHAR(50) NOT NULL,
         authKey INT(4) NOT NULL
     );";

//table creation execute
       $queryResultuserAuth = @mysqli_query($dbConnect, $sqlUserAuth)
       or die("<p>Unable to create UserAuth Table.</p>"
        . "<p>Error code " . mysqli_errno($dbConnect)
        . ": " . mysqli_error($dbConnect) . "</p>");

        $sqlInsertintouserAuth = "INSERT INTO userAuth (user_id, user_email, password, authKey)
        VALUES (1, 'admin@gmail.com', 'admin1', 1234)
        ON DUPLICATE KEY UPDATE user_id = user_id;";

        $queryResultInsert1 = @mysqli_query($dbConnect, $sqlInsertintouserAuth);

        mysqli_close($dbConnect);
    ?>

</body>
</html>