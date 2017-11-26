<?php
require_once 'config.php';

$dataexists = 0;

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
    <p>Selected Location: <strong><?php echo $_POST["locationname"];?></strong></p>
    <p>Select a starting date:</p>

    <!-- Select Location -->
    <form action="temperaturevisualizationdisplay.php" method="post">

      <!-- Select Starting Date -->
      <div class="form-group" id="startdateselect">
        <select class="form-control" style="max-width:15%;" name="startdate">
          <?php
            $sql = "SELECT DISTINCT Date FROM Temperature WHERE UserName = ? AND LocationName = ? ORDER BY Date ASC";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $param_username, $param_locationname);
            $param_username = $_SESSION['username'];
            $param_locationname = $_POST["locationname"];
            if($stmt->execute())
            {
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $stmt->bind_result($startdate);
                $dataexists = 1;

                while ($stmt->fetch())
                {
                  echo "<option value='".$startdate."'>$startdate</option>";
                }
              }
              else
              {
                $dataexists = 0;
              }
            }

            $stmt->close();
          ?>
        </select>
      </div>

      <p>Select an ending date:</p>

      <!-- Select Starting Date -->
      <div class="form-group" id="enddateselect">
        <select class="form-control" style="max-width:15%;" name="enddate">
          <?php
            $sql = "SELECT DISTINCT Date FROM Temperature WHERE UserName = ? AND LocationName = ? ORDER BY Date DESC";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $param_username, $param_locationname);
            $param_username = $_SESSION['username'];
            $param_locationname = $_POST["locationname"];
            if($stmt->execute())
            {
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $stmt->bind_result($enddate);
                $dataexists = 1;

                while ($stmt->fetch())
                {
                  echo "<option value='".$enddate."'>$enddate</option>";
                }
              }
              else
              {
                $dataexists = 0;
              }
            }

            $stmt->close();
            $mysqli->close();
          ?>
        </select>
      </div>

      <!-- Pass Location name to next page -->
      <input type="hidden" name="locationname" value=<?php echo $param_locationname ?> />

      <!-- Display Submit button if current user has data, otherwise show warning -->
      <?php
        if ($dataexists == 1)
        {
          echo "<input type='submit' class='btn btn-default btn-sm' value='Submit'>";
        }
        else
        {
          echo "<strong><p class='bg-danger' style='max-width:15%;'>You have no Temperature Sensor data!</p></strong>";
          echo "<i>Please record Temperature Sensor data using the HTW Monitor Application to visualize your data.</i><br><br>";
        }
      ?>
    </form>

    <br>
    <a class="btn btn-default btn-sm" href="temperaturevisualization.php" role="button">Reselect Location</a>

  </body>
</center>
</html>
