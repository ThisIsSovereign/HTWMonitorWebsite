<?php
define('DB_SERVER', 'sovereigndb.chnv0tgw3tgj.us-east-2.rds.amazonaws.com');
define('DB_USERNAME', 'websiteuser');
define('DB_PASSWORD', '0bEF35eBOSE5grD3');
define('DB_NAME', 'htwmonitor');

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>
