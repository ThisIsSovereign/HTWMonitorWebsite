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
    <p>Select a Location:</p>

    <!-- Select Location -->
    <form action="waterdetectionstatusresults.php" method="post">
      <div class="form-group">
        <select class="form-control" style="max-width:15%;" name="locationname">
          <?php
            $sql = "SELECT LocationName FROM WaterDetection WHERE UserName = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $param_username);
            $param_username = $_SESSION['username'];
            if($stmt->execute())
            {
              $stmt->store_result();

              if($stmt->num_rows > 0)
              {
                $stmt->bind_result($locationname);
                $dataexists = 1;

                while ($stmt->fetch())
                {
                  echo "<option value='".$locationname."'>$locationname</option>";
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

      <!-- Display Submit button if current user has WD data, otherwise show warning -->
      <?php
        if ($dataexists == 1)
        {
          echo "<input type='submit' class='btn btn-default btn-sm' value='Submit'>";
        }
        else
        {
          echo "<strong><p class='bg-danger' style='max-width:15%;'>You have no Water Detection Sensor data!</p></strong>";
          echo "<i>Please record Water Detection Sensor data using the HTW Monitor Application to view current status.</i><br><br>";
        }
      ?>
    </form>

  </body>
</center>
</html>
