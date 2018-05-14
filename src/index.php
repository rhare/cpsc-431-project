<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');
//require_once('Address.php');
//require_once('PlayerStatistic.php');
//$db_host = '192.168.99.100';  // Docker container
//$db_user = 'bbuser';
//$db_pass = 'Password!12345';
//$db_name = 'BBTEAM';
//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
///* check connection */
//if (mysqli_connect_errno()) {
//    printf("connect failed: %s\n", mysqli_connect_errno());
//    die('Database Connection Error');
//}
$db = new Database();
$conn = $db->connect_observer();
$res = $conn->query("SELECT Email FROM Users");
$players = array();
#while ($row = $res->fetch_assoc()) {
#    echo "HELLO WORLD!!";
#    echo $row;
#    print_r($row);
#}

//$q = "SELECT
//         tr.ID,
//         tr.Name_Last,
//         tr.Name_First,
//         tr.Street,
//         tr.City,
//         tr.State,
//         tr.ZipCode,
//         tr.Country,
//         COUNT(s.Player) as games_played,
//         AVG(s.Points) AS avg_points,
//         AVG(s.Assists) AS avg_assists,
//         AVG(s.Rebounds) AS avg_rebounds,
//         (SUM(s.PlayingTimeMin)*60 + SUM(s.PlayingTimeSec)) / COUNT(s.Player) DIV 60 AS avg_min,
//         (SUM(s.PlayingTimeMin)*60 + SUM(s.PlayingTimeSec)) / COUNT(s.Player) MOD 60 AS avg_sec
//      FROM
//        TeamRoster AS tr
//      LEFT JOIN 
//        Statistics AS s ON tr.ID = s.Player
//      GROUP BY tr.Name_Last, tr.Name_First";
//$res = $mysqli->query($q);
//$players = array();
//while ($row = $res->fetch_assoc()) {
//    $players[] = $row;
//}

?>

  <?php
    // for each row (record) of data retrieved from the database emit the html to populate a row in the table
    // for example:
    foreach ($players as $i => $player) {
        $name = (empty($player['Name_First'])) ? $player['Name_Last'] : $player['Name_Last'] . ', ' .$player['Name_First'];

        $a_line1 = '';
        $a_line2 = '';
        $a_line3 = '';
        if(!empty($player['Street'])) $a_line1 .= $player['Street'] . '<br/>';
        if(!empty($player['City'])) $a_line2 .= $player['City'] . ',';
        if(!empty($player['State'])) $a_line2 .= ' ' . $player['State'];
        if(!empty($player['ZipCode'])) $a_line2 .= ' ' . $player['ZipCode'];
        if(!empty($a_line2)) $a_line2 .= '<br />';
        if(!empty($player['Country'])) $a_line3 .= $player['Country'];
        $address = $a_line1 . $a_line2 . $a_line3;

        if (!empty($player['avg_min']) || !empty($player['avg_sec'])) {
            $avg_time = sprintf("%02d:%02d", $player['avg_min'], $player['avg_sec']);
            $avg_points = sprintf("%d", $player['avg_points']);
            $avg_assists = sprintf("%d", $player['avg_assists']);
            $avg_rebounds = sprintf("%d", $player['avg_rebounds']);
        } else {
            $avg_time = '';
            $avg_points = '';
            $avg_assists = '';
            $avg_rebounds = '';
        }
        $background = (empty($avg_time)) ? 'background: #e6e6e6;' :  '' ;
     ?>
    <?php
    }
  ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>
