<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once('Team.php');
$message = array();
$db = new Database();
$teamName = $_POST['teamName'];
$newTeam = new Team($teamName);
$db_conn = $db->connect_dba(); // Temp for now until roles are properly inplace.
try {
  $saved = $newTeam->save($db_conn);
} catch (Exception $e) {
  $message['alert_type'] = 'alert-danger';
  $message['message'] = $e->getMessage();
}

if($saved) {
  $message['message'] = 'Succesfully added team';
  $message['alert_type'] = 'alert-success';
}
$_SESSION['message'] = $message;
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Teams/';
header('Location: '.$newURL);
exit(0);
?>
