<?php
// create short variable names
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once('Team.php');
//echo "Processing team update...";

$db = new Database();
$teamName = $_POST['teamName'];
$newTeam = new Team($teamName);
$db_conn = $db->connect_dba(); // Temp for now until roles are properly inplace.
$newTeam->save($db_conn);
$uri = 'http://' . $_SERVER['HTTP_HOST'];
$newURL = $url . '/Teams/';
header('Location: '.$newURL);
?>
