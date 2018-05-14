<?php
session_start();
// create short variable names
$firstName = ucwords(strtolower(preg_replace("/\t|\R/",' ',$_POST['firstName'])));
$lastName  = ucwords(strtolower(preg_replace("/\t|\R/",' ',$_POST['lastName'])));
$gamesPlayed    = $_POST['gp'];
$minutes  = $_POST['min'];
$pointsPerGame  = $_POST['ppg'];
$reboundsPerGame  = $_POST['rpg'];
$assistsPerGame  = $_POST['apg'];
$stealsPerGame  = $_POST['spg'];
$blocksPerGame  = $_POST['bpg'];
$turnoversPerGame  = $_POST['tpg'];
$fieldGoalsPercentage  = $_POST['fgp'];
$freeThrowPercentage  = $_POST['ftp'];
$threePointPercentage  = $_POST['tpp'];

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
require_once('PlayerStats.php');
$user = unserialize($_SESSION['user']);
$db = new Database();
$db_conn = $db->connect_by_role($user->role());
try {
  $newPlayer = PlayerStats::new_player_stats($db_conn, $firstName, $lastName, $gamesPlayed, $minutes, $pointsPerGame, $reboundsPerGame, $assistsPerGame, $stealsPerGame, $blocksPerGame, $turnoversPerGame, $fieldGoalsPercentage, $freeThrowPercentage, $threePointPercentage);
  $message['alert_type'] = 'alert-success';
  $message['message'] = 'Player Stats successfully created';
} catch (Exception $e) {
  $message['alert_type'] = 'alert-danger';
  $message['message'] = $e->getMessage();
}
$_SESSION['message'] = $message;
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/PlayerStats/';
header('Location: '.$newURL);
exit(0);
?>
