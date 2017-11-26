<?php
require_once 'config.php';

$username = "";
$password = "";
$confirm_password = "";
$username_err = "";
$password_err = "";
$confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    }
    else
    {
        $sql = "SELECT UserName FROM Users WHERE UserName = ?";

        if($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("s", $param_username);

            $param_username = trim($_POST["username"]);

            if($stmt->execute())
            {
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1)
                {
                    $username_err = "This username is already taken.";
                }
                else
                {
                    $username = trim($_POST["username"]);
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password'])))
    {
        $password_err = "Please enter a password.";
    }
    elseif(strlen(trim($_POST['password'])) < 4)
    {
        $password_err = "Password must have atleast 6 characters.";
    }
    else
    {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = 'Please confirm password.';
    }
    else
    {
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password)
        {
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
    {
      $sql = "INSERT INTO Users (UserName, Password) VALUES (?, ?)";

      if($stmt = $mysqli->prepare($sql))
      {
        $stmt->bind_param("ss", $param_username, $param_password);

        $param_username = $username;
        $param_password = $password;

        if($stmt->execute())
        {
          // Redirect
          header("location: login.php");
        }
        else
        {
          echo "Something went wrong. Please try again later.";
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
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: #000000;}
    </style>
</head>
<center>
<body>
  <!-- User Menu -->
  <p class="text-right">
    <strong>Not Logged In</strong>
    <a href="login.php" class="btn btn-default btn-sm">Login</a>
  </p>

  <p class="h1">HTW Monitor</p>

  <h3>Registration</h3>
  <br>
  <p>Please complete this form to create an account.</p>

  <!-- Registration Form -->
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <label>Username:<sup>*</sup></label>
          <input type="text" name="username"class="form-control" style='max-width:15%' value="<?php echo $username; ?>">
          <span class="help-block"><?php echo $username_err; ?></span>
      </div>

      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <label>Password:<sup>*</sup></label>
          <input type="password" name="password" class="form-control" style='max-width:15%' value="<?php echo $password; ?>">
          <span class="help-block"><?php echo $password_err; ?></span>
      </div>

      <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <label>Confirm Password:<sup>*</sup></label>
          <input type="password" name="confirm_password" class="form-control" style='max-width:15%' value="<?php echo $confirm_password; ?>">
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
      </div>

      <div class="form-group">
          <input type="submit" class="btn btn-primary btn-sm" value="Register">
          <input type="reset" class="btn btn-default btn-sm" value="Reset">
      </div>

      <p>Already have an account? <a href="login.php">Login here</a>.</p>
  </form>

</body>
</center>
</html>
