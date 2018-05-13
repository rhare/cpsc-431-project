<?php 
session_start();
unset($_SESSION['user']);
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Auth/login.php';
header('Location: '.$newURL);
exit(0);
?>
