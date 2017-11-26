<?php
require_once 'config.php';

$temperatures = array();
//$temperatures['name'] = 'Temperature';
$dates = array();
//$dates['name'] = 'Date';

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
    <title>Temperature Visualization</title>
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

    <h3>Temperature Visualization</h3>
    <br>
    <p><strong>
      <?php
        echo $_POST["startdate"];
      ?>
    </strong></p>
    <p><strong>
      <?php
        echo $_POST["enddate"];
      ?>
    </strong></p>

    <!-- Get Temperature Data for user selected date range -->
    <?php
      $sql = "SELECT Temperature, Date FROM Temperature WHERE UserName = ? AND Date BETWEEN ? AND ? ORDER BY Date ASC, Time ASC";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("sss", $param_username, $param_startdate, $param_enddate);
      $param_username = $_SESSION['username'];
      $param_startdate = $_POST["startdate"];
      $param_enddate = $_POST["enddate"];
      $stmt->execute();
      $stmt->bind_result($temperature, $date);

      while ($stmt->fetch())
      {
        $temperatures[] = $temperature;
        $dates[] = $date;
      }

      $stmt->close();
      $mysqli->close();

      // echo json_encode($temperatures);
      // echo "<br>";
      // echo json_encode($dates);
      // echo "<br>";
    ?>

    <a class="btn btn-default btn-sm" href="temperaturevisualization.php" role="button">Adjust Date Range</a>

  </body>
</center>
</html>
