<?php
require_once 'config.php';

// Initialize the session
session_start();

$updatefrequency = "";
$updatefrequency_err = "";
$updatefrequency_suc = "";

// Check session variable
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{

    // Check if update frequency is empty
    if(empty(trim($_POST["updatefrequency"]))){
      $updatefrequency_err = 'Please enter a new sensor update frequency.';
    }
    // Check if update frequency is valid
    else if(trim($_POST["updatefrequency"]) < 10 || trim($_POST["updatefrequency"]) > 3600)
    {
      $updatefrequency_err = 'Please enter a sensor update frequency between <strong>10 seconds and 60 minutes (3600 seconds)</strong>.';
    }
    else
    {
      $updatefrequency = trim($_POST["updatefrequency"]);
      $updatefrequency_suc = '<strong>Success!</strong> Sensor Update Frequency set to '.$updatefrequency.' seconds.';
    }

    $sql = "UPDATE Users SET UpdateFrequency = (?) WHERE UserName = (?)";

    if($stmt = $mysqli->prepare($sql))
    {
      $stmt->bind_param("ss", $param_updatefrequency, $param_username);

      $param_updatefrequency = $updatefrequency;
      $param_username = $_SESSION['username'];

      $stmt->execute();

      $stmt->close();
    }
}

// Close connection
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sensor Update Frequency</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        helpBlock2{ color: #5cb85c; }
    </style>
</head>
<center>
  <body>
    <!-- User Menu -->
    <p class="text-right">
      <strong><?php echo $_SESSION['username']; ?></strong>
      <a href="userhome.php" class="btn btn-default btn-sm">User Home</a>
      <a href="logout.php" class="btn btn-default btn-sm">Logout</a>
    </p>

    <p class="h1">HTW Monitor</p>

    <h3>Sensor Update Frequency</h3>
    <br>
    <p>Enter a new Update Frequency (in seconds):</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($updatefrequency_err)) ? 'has-error' : ''; ?>">
            <input type="text" name="updatefrequency" class="form-control" style='max-width:15%;' aria-describedby="helpBlock2" value="<?php echo $updatefrequency; ?> ">
            <span class="help-block"><?php echo $updatefrequency_err; ?></span>
            <span class="help-block" id="helpBlock2" style="color: #5cb85c;"><?php echo $updatefrequency_suc; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-default btn-sm" value="Set">
        </div>
    </form>

  </body>
</center>
</html>
