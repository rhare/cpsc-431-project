<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User
require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); 
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>
