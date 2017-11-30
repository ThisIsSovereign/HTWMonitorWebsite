<?php
// Initialize the session
session_start();

// Check session variable
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: #000000;}
    </style>
</head>
<center>
  <body>
      <p class="text-right">
        <strong><?php echo $_SESSION['username']; ?></strong>
        <a href="logout.php" class="btn btn-default btn-sm">Logout</a>
      </p>

      <p class="h1">HTW Monitor</p>

      <h3>User Home</h3>
      <br>
      <h4>Navigation</h4>
      <br>

      <a class="btn btn-default btn-sm" href="humidityvisualization.php" role="button">Humidity Visualization</a>
      <br>
      <br>
      <a class="btn btn-default btn-sm" href="temperaturevisualization.php" role="button">Temperature Visualization</a>
      <br>
      <br>
      <a class="btn btn-default btn-sm" href="waterdetectionstatus.php" role="button">Water Detection Status</a>
      <br>
      <br>
      <a class="btn btn-default btn-sm" href="sensorupdatefrequency.php" role="button">Sensor Update Frequency</a>

  </body>
</center>
</html>
