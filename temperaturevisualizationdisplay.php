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
    <script src="https://code.highcharts.com/highcharts.js"></script>
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
    <!-- <br>
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
    <p><strong>
      <?php
        echo $_POST["locationname"];
      ?>
    </strong></p> -->

    <!-- Get Temperature Data for user selected date range -->
    <?php
      $sql = "SELECT Temperature, Date, Time FROM Temperature WHERE UserName = ? AND LocationName = ? AND Date BETWEEN ? AND ? ORDER BY Date ASC, Time ASC";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("ssss", $param_username, $param_locationname, $param_startdate, $param_enddate);
      $param_username = $_SESSION['username'];
      $param_locationname = $_POST["locationname"];
      $param_startdate = $_POST["startdate"];
      $param_enddate = $_POST["enddate"];
      $stmt->execute();
      $stmt->bind_result($temperature, $date, $time);

      while ($stmt->fetch())
      {
        $temperatures[] = $temperature;
        if ($param_startdate == $param_enddate)
        {
          $dates[] = $time;
        }
        else
        {
          $dates[] = $date;
        }

      }

      $stmt->close();
      $mysqli->close();

      // echo json_encode($temperatures);
      // echo "<br>";
      // echo json_encode($dates);
      // echo "<br>";
    ?>

    <div id="container">
    </div>

    <!-- Pass Selected Location to Previous Page -->
    <form action="temperaturevisualizationdate.php" method="post">
      <input type="hidden" name="locationname" value=<?php echo $param_locationname ?> />
      <br>
      <!--<a class="btn btn-default btn-sm" href="temperaturevisualizationdate.php" role="button">Adjust Date Range</a>-->
      <input type='submit' class='btn btn-default btn-sm' value='Adjust Date Range'>
    </form>

    <br>
    <a class="btn btn-default btn-sm" href="temperaturevisualization.php" role="button">Reselect Location</a>

    <script type="text/javascript">
      var temperatures = <?php echo json_encode($temperatures) ?>;

      var dates = <?php echo json_encode($dates) ?>;

      var locationname = <?php echo json_encode($param_locationname) ?>;

      var startdate = <?php echo json_encode($param_startdate) ?>;

      var enddate = <?php echo json_encode($param_enddate) ?>;

      var subtitle = (locationname + ' from ' + startdate + ' to ' + enddate);

      // Setup HighCharts Chart
      var chart = Highcharts.chart('container',
        {
          title: {
              text: 'Temperature'
          },
          subtitle: {
              text: subtitle
          },
          xAxis: {
              categories: dates,
              minTickInterval: 7,
              min: 1,
          },
          yAxis: {
              title: {
                  text: 'Temperature'
              }
          },
          legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle'
          },
          plotOptions: {
              series: {
                  pointStart: dates[0].event
              }
          },
          series:
          [
            {
              name: 'Temperature',
              data: temperatures
            }
          ]
        }
      );
    </script>

  </body>
</center>
</html>
