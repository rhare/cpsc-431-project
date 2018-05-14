<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/';
header('Location: '.$newURL);
exit(0);
// create short variable names
//$name_id    = (int) $_POST['name_ID'];
//$time       = preg_replace("/\t|\R/",' ',$_POST['time']);
//$points     = (int) $_POST['points'];
//$assists    = (int) $_POST['assists'];
//$rebounds   = (int) $_POST['rebounds'];

?>

