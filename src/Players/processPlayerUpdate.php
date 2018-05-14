<?php
// Get user
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login();

// create short variable names
$firstName = ucwords(strtolower(preg_replace("/\t|\R/",' ',$_POST['firstName'])));
$lastName  = ucwords(strtolower(preg_replace("/\t|\R/",' ',$_POST['lastName'])));
$email    = $_POST['email'];
$street    = ucwords(strtolower($_POST['street']));
$city      = ucwords(strtolower($_POST['city']));
$state     = ucwords(strtolower($_POST['state']));
$zipcode   = $_POST['zipCode'];
$country   = ucwords(strtolower($_POST['country']));
$teamId   = $_POST['team'];

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once('Player.php');
$db = new Database();
$db_conn = $db->connect_by_role($user->role());
try {
  $newPlayer = Player::new_player($db_conn, $firstName, $lastName, $email, $street, $city, $state, $zipcode, $country, $teamId);
  $message['alert_type'] = 'alert-success'; $message['message'] = 'Player successfully created';
} catch (Exception $e) {
  $message['alert_type'] = 'alert-danger';
  $message['message'] = $e->getMessage();
}
$_SESSION['message'] = $message;
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Players/';
header('Location: '.$newURL);
exit(0);
?>
