<?php

// create short variable names
$name_id    = (int) $_POST['name_ID'];
$time       = preg_replace("/\t|\R/",' ',$_POST['time']);
$points     = (int) $_POST['points'];
$assists    = (int) $_POST['assists'];
$rebounds   = (int) $_POST['rebounds'];

// Connect to DB
$db_host = '192.168.99.100';  // Docker container
$db_user = 'bbuser';
$db_pass = 'Password!12345';
$db_name = 'BBTEAM';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
/* check connection */
if (mysqli_connect_errno()) {
    printf("connect failed: %s\n", mysqli_connect_errno());
    die('Database Connection Error');
}

require('PlayerStatistic.php');

$newStat = new PlayerStatistic('', $time, $points, $assists, $rebounds, $name_id);
if(!empty($name_id)) {
    $newStat->toDB($mysqli);
}
$mysqli->close();
require('home_page.php');
?>

