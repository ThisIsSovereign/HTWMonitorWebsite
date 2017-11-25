<?php
require_once 'config.php';

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
    <title>Water Detection Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
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

    <h3>Water Detection Status</h3>
    <br>
    <p class="h5">
      <?php
        echo $_POST["locationname"];
      ?>
    </p>

    <!-- Get water detection status of currently selected location and display -->
    <?php
      $sql = "SELECT Status, Date, Time FROM WaterDetection WHERE LocationName = ? AND UserName = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("ss", $param_locationname, $param_username);
      $param_locationname = $_POST["locationname"];
      $param_username = $_SESSION['username'];
      $stmt->execute();
      $stmt->bind_result($status, $date, $time);

      while ($stmt->fetch())
      {
        if($status == 0)
        {
          echo "<strong><p class='bg-success' style='max-width:15%;'>No water currently detected.</p></strong>";
          echo "<i>Last update recieved at <strong>".$time."</strong> on <strong>".$date."</strong>.</i><br><br>";
        }
        else if ($status == 1)
        {
          echo "<strong><p class='bg-danger' style='max-width:15%;'>Water detected!</p></strong>";
          echo "<i>Last update recieved at <strong>".$time."</strong> on <strong>".$date."</strong>.</i><br><br>";
        }
      }

      $stmt->close();
      $mysqli->close();
    ?>

    <a class="btn btn-default btn-sm" href="waterdetectionstatus.php" role="button">Check another location</a>

  </body>
</center>
</html>
