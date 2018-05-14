<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Games/Game.php');

$teamId_A = (int) $_POST['teamA'];
$teamId_B = (int) $_POST['teamB'];
$teamScore_A = (int) $_POST['teamScoreA'] < 0 ? 0 : (int) $_POST['teamScoreA'];
$teamScore_B = (int) $_POST['teamScoreB'] < 0 ? 0 : (int) $_POST['teamScoreB'];
$gameDate = $_POST['gameDate'];

$db = new Database();
$db_conn = $db->connect_by_role($user->role());
try {
  $newPlayer = Game::new_game($db_conn, $teamId_A, $teamId_B, $teamScore_A, $teamScore_B, $gameDate);
  $message['alert_type'] = 'alert-success';
  $message['message'] = 'Player successfully created';
} catch (Exception $e) {
  $message['alert_type'] = 'alert-danger';
  $message['message'] = $e->getMessage();
}
$_SESSION['message'] = $message;
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Games/';
header('Location: '.$newURL);
exit(0);
?>
