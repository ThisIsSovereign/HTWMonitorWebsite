<?php
require_once 'config.php';

$username = "";
$password = "";
$username_err = "";
$password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter your username.';
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err))
    {
        $sql = "SELECT UserName, Password FROM Users WHERE UserName = ?";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("s", $param_username);

            $param_username = $username;

            if($stmt->execute())
            {
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1)
                {
                    // Bind result variables
                    $stmt->bind_result($username, $stored_password);
                    if($stmt->fetch())
                    {
                        if($password == $stored_password)
                        {
                            //Password is correct, start a new session with that username
                            session_start();
                            $_SESSION['username'] = $username;
                            header("location: userhome.php");
                        }
                        else
                        {
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                }
                else
                {
                    $username_err = 'No account found with that username.';
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        $stmt->close();
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
    </style>
</head>
<center>
<body>
  <p class="h1">HTW Monitor</p>

  <h3>Login</h3>
  <br>
  <p>Please enter your credentials to login.</p>

  <!-- Login Form -->
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <label>Username:<sup>*</sup></label>
          <input type="text" name="username"class="form-control" style='max-width:15%' value="<?php echo $username; ?>">
          <span class="help-block"><?php echo $username_err; ?></span>
      </div>

      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <label>Password:<sup>*</sup></label>
          <input type="password" name="password" class="form-control" style='max-width:15%'>
          <span class="help-block"><?php echo $password_err; ?></span>
      </div>

      <div class="form-group">
          <input type="submit" class="btn btn-primary btn-sm" value="Login">
      </div>

      <p>Don't have an account? <a href="register.php">Register now</a>.</p>
  </form>

</body>
<center>
</html>
