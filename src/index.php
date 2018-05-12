  <?php require_once(__DIR__ . '/headernavbar.php'); ?>

  <body>

  <?php
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
        require_once('utils/Database.php');
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

        // Connect to database

        // if connection was successful
        // Build query to retrieve player's name, address, and averaged statistics from the joined Team Roster and Statistics tables
        // Prepare, execute, store results, and bind results to local variables
    ?>

<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Players</div>
    <table class="table">
      <tr>
        <th colspan="1" style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th colspan="2" style="vertical-align:top; border:1px solid black; background: lightgreen;">Player</th>
        <th colspan="1" style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th colspan="4" style="vertical-align:top; border:1px solid black; background: lightgreen;">Statistic Averages</th>
      </tr>
      <tr>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Name</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Address</th>

        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Games Played</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Time on Court</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Points Scored</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Number of Assists</th>
        <th style="vertical-align:top; border:1px solid black; background: lightgreen;">Number of Rebounds</th>
      </tr>
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
          <tr>
            <td  style="vertical-align:top; border:1px solid black;"><?php echo $i+1; ?></td>
            <td  style="vertical-align:top; border:1px solid black;"><?php echo $name; ?></td>
            <td  style="vertical-align:top; border:1px solid black;"><?php echo $address; ?></td>
            <td  style="vertical-align:top; border:1px solid black;"><?php echo $player['games_played']; ?></td>
            <td  style="vertical-align:top; border:1px solid black; <?php echo $background; ?>"><?php echo $avg_time; ?></td>
            <td  style="vertical-align:top; border:1px solid black; <?php echo $background; ?>"><?php echo $avg_points; ?></td>
            <td  style="vertical-align:top; border:1px solid black; <?php echo $background; ?>"><?php echo $avg_assists; ?></td>
            <td  style="vertical-align:top; border:1px solid black; <?php echo $background; ?>"><?php echo $avg_rebounds; ?></td>
          </tr>
        <?php
        }
      ?>
    </table>

  </body>
</html>
