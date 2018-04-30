<!DOCTYPE html>
<html>
  <head>
    <title>CPSC 431 HW-3</title>
  </head>
  <body>
    <h1 style="text-align:center">Cal State Fullerton Basketball Statistics</h1>

    <?php
        require_once('Address.php');
        require_once('PlayerStatistic.php');
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

        $q = "SELECT
                 tr.ID,
                 tr.Name_Last,
                 tr.Name_First,
                 tr.Street,
                 tr.City,
                 tr.State,
                 tr.ZipCode,
                 tr.Country,
                 COUNT(s.Player) as games_played,
                 AVG(s.Points) AS avg_points,
                 AVG(s.Assists) AS avg_assists,
                 AVG(s.Rebounds) AS avg_rebounds,
                 (SUM(s.PlayingTimeMin)*60 + SUM(s.PlayingTimeSec)) / COUNT(s.Player) DIV 60 AS avg_min,
                 (SUM(s.PlayingTimeMin)*60 + SUM(s.PlayingTimeSec)) / COUNT(s.Player) MOD 60 AS avg_sec
              FROM
                TeamRoster AS tr
              LEFT JOIN 
                Statistics AS s ON tr.ID = s.Player
              GROUP BY tr.Name_Last, tr.Name_First";
        $res = $mysqli->query($q);
        $players = array();
        while ($row = $res->fetch_assoc()) {
            $players[] = $row;
        }

        // Connect to database

        // if connection was successful
        // Build query to retrieve player's name, address, and averaged statistics from the joined Team Roster and Statistics tables
        // Prepare, execute, store results, and bind results to local variables
    ?>

    <table style="width: 100%; border:0px solid black; border-collapse:collapse;">
      <tr>
        <th style="width: 40%;">Name and Address</th>
        <th style="width: 60%;">Statistics</th>
      </tr>
      <tr>
        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter Name and Address -->
          <form action="processAddressUpdate.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">First Name</td>
                <td><input type="text" name="firstName" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Last Name</td>
               <td><input type="text" name="lastName" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Street</td>
               <td><input type="text" name="street" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">City</td>
                <td><input type="text" name="city" value="" size="35" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">State</td>
                <td><input type="text" name="state" value="" size="35" maxlength="100"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Country</td>
                <td><input type="text" name="country" value="" size="20" maxlength="250"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Zip</td>
                <td><input type="text" name="zipCode" value="" size="10" maxlength="10"/></td>
              </tr>

              <tr>
               <td colspan="2" style="text-align: center;"><input type="submit" value="Add Name and Address" /></td>
              </tr>
            </table>
          </form>
        </td>

        <td style="vertical-align:top; border:1px solid black;">
          <!-- FORM to enter game statistics for a particular player -->
          <form action="processStatisticUpdate.php" method="post">
            <table style="margin: 0px auto; border: 0px; border-collapse:separate;">
              <tr>
                <td style="text-align: right; background: lightblue;">Name (Last, First)</td>
<!--            <td><input type="text" name="name" value="" size="50" maxlength="500"/></td>  -->
                <td><select name="name_ID" required>
                  <option value="" selected disabled hidden>Choose player's name here</option>
                  <?php
                    // for each row of data returned,
                    //   construct an Address object providing first and last name
                    //   emit an option for the pull down list such that
                    //     the displayed name is retrieved from the Address object
                    //     the value submitted is the unique ID for that player
                    // for example:
                    //     <option value="101">Duck, Daisy</option>
                    $addresses = array();
                    foreach($players as $i => $player) {
                      $name = [$player['Name_First'], $player['Name_Last']];
                      $a = new Address($name, $player['Street'], $player['City'], $player['State'], $player['Zip'], $player['Country']);
                      $addresses[] = $a;
                      echo '<option value="' . $player['ID'] . '">' . $a->name() . '</option>';
                    }
                  ?>
                </select></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Playing Time (min:sec)</td>
               <td><input type="text" name="time" value="" size="5" maxlength="5"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Points Scored</td>
               <td><input type="text" name="points" value="" size="3" maxlength="3"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Assists</td>
                <td><input type="text" name="assists" value="" size="2" maxlength="2"/></td>
              </tr>

              <tr>
                <td style="text-align: right; background: lightblue;">Rebounds</td>
                <td><input type="text" name="rebounds" value="" size="2" maxlength="2"/></td>
              </tr>

              <tr>
               <td colspan="2" style="text-align: center;"><input type="submit" value="Add Statistic" /></td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>


    <h2 style="text-align:center">Player Statistics</h2>
    <p>
    <?php
      // emit the number of rows (records) in the table
      echo 'Number of Records: ' . $res->num_rows;
    ?>
    </p>

    <table style="border:1px solid black; border-collapse:collapse;">
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
