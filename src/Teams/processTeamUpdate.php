<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Teams/Team.php');
$message = array();
$db = new Database();
$teamName = $_POST['teamName'];
$newTeam = new Team($teamName);
$db_conn = $db->connect_by_role($user->role());
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
