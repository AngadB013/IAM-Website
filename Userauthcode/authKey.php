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
    <form action = 'authProcess.php' method = 'post'>
       <table>
       <tr>
          <td><label for="auth">Authentication Key:</label><br></td>
       </tr>

       <tr>
          <td><input type="password" id="auth" name="auth"><br></td>
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

</body>
</html>