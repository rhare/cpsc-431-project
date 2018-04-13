<?php
// create short variable names
$firstname = preg_replace("/\t|\R/",' ',$_POST['firstName']);
$lastname  = preg_replace("/\t|\R/",' ',$_POST['lastName']);
$name      = [$firstname, $lastname];
$street    = $_POST['street'];
$city      = $_POST['city'];
$state     = $_POST['state'];
$zipcode   = $_POST['zipCode'];
$country   = $_POST['country'];

// Connect to DB
$db_host = '192.168.99.100';  // Docker container
$db_user = 'bbuser';
$db_pass = 'Password!12345';
$db_name = 'BBTEAM';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
/* check connection */
if (mysqli_connect_errno()) {
    printf("connect failed: %s\n", mysqli_connect_errno());
    die('Database Connection Error');
}

require('Address.php');
$newAddress = new Address($name, $street, $city, $state, $zipcode, $country);

if(!empty($name)) {
    $newAddress->toDB($mysqli);
}
$mysqli->close();
require('home_page.php');
?>

