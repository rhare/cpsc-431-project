<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Games/Game.php');

$userId = (int) $_POST['userId'];
$role = $_POST['role'];

$db = new Database();
$db_conn = $db->connect_by_role($user->role());
try {
  $puser = User::query_by_id($db_conn, $userId);
  if(!empty($puser)) {
    $puser->promoteRole($db_conn, $role);
    $message['alert_type'] = 'alert-success';
    $message['message'] = 'Player successfully created';
  } else {
    $message['alert_type'] = 'alert-danger';
    $message['message'] = 'User not found';
  }

} catch (Exception $e) {
  $message['alert_type'] = 'alert-danger';
  $message['message'] = $e->getMessage();
}
$_SESSION['message'] = $message;
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Settings/';
header('Location: '.$newURL);
exit(0);
?>
