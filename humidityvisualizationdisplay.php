<?php
require_once 'config.php';

$humidiyvalues = array();
$dates = array();

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
    <title>Humidity Visualization</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/js/highcharts.js"></script>
    <script src="https://code.highcharts.com/js/modules/exporting.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: #000000;}
    </style>

    <!-- HighCharts Style -->
    <style>
      @import 'https://code.highcharts.com/css/highcharts.css';

      .highcharts-background
      {
        fill: #000000;
      }
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

    <h3>Humidity Visualization</h3>

    <!-- Get Humidity Data for user selected date range -->
    <?php
      $sql = "SELECT RelativeHumidity, Date, Time FROM RelativeHumidity WHERE UserName = ? AND LocationName = ? AND Date BETWEEN ? AND ? ORDER BY Date ASC, Time ASC";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("ssss", $param_username, $param_locationname, $param_startdate, $param_enddate);
      $param_username = $_SESSION['username'];
      $param_locationname = $_POST["locationname"];
      $param_startdate = $_POST["startdate"];
      $param_enddate = $_POST["enddate"];
      $stmt->execute();
      $stmt->bind_result($humidity, $date, $time);

      while ($stmt->fetch())
      {
        $humidityvalues[] = $humidity;
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
    ?>

    <!-- HighCharts Container -->
    <div id="container">
    </div>

    <!-- Pass Selected Location to Previous Page -->
    <form action="humidityvisualizationdate.php" method="post">
      <input type="hidden" name="locationname" value=<?php echo $param_locationname ?> />
      <br>
      <input type='submit' class='btn btn-default btn-sm' value='Change Date Range'>
    </form>

    <br>
    <a class="btn btn-default btn-sm" href="humidityvisualization.php" role="button">Change Location</a>

    <script type="text/javascript">
      var humidityvalues = <?php echo json_encode($humidityvalues) ?>;
      var dates = <?php echo json_encode($dates) ?>;
      var locationname = <?php echo json_encode($param_locationname) ?>;
      var startdate = <?php echo json_encode($param_startdate) ?>;
      var enddate = <?php echo json_encode($param_enddate) ?>;

      if (startdate == enddate)
      {
        var subtitle = (locationname + ' on ' + startdate);
      }
      else
      {
        var subtitle = (locationname + ' from ' + startdate + ' to ' + enddate);
      }

      // Setup HighCharts Chart
      var chart = Highcharts.chart('container',
        {
          title: {
              text: 'Relative Humidity %'
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
                  text: 'Relative Humidity %'
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
              name: 'Relative Humidity %',
              data: humidityvalues
            }
          ]
        }
      );
    </script>

  </body>
</center>
</html>
