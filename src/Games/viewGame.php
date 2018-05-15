<?php
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

if(isset($_GET['id'])){
  $id = $_GET['id'];
} else {
  echo "SHiiit!!";
  http_response_code(404);
  exit(0);
}
require_once($_SERVER['DOCUMENT_ROOT']  . '/Teams/Team.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Games/Game.php');
$db = new Database();
$db_conn = $db->connect_observer();
$game = Game::query_by_gameid($db_conn, $id);
if(empty($game)) {
  http_response_code(404);
  exit(0);
}
$teamA = Team::query_by_teamid($db_conn, $game->teamId_A());
$teamB = Team::query_by_teamid($db_conn, $game->teamId_B());
?>
<?php require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); ?>
<div class="container my-3">
  <!-- Form row -->
  <div class="row my-2">
    <div class="col-s">
      <?php 
        // Generate list of teams.
        $db = new Database();
        $db_conn = $db->connect_observer();
        $query = "
          SELECT  Person.FirstName, 
                  Person.LastName, 
                  Player.PlayerId, 
                  Team.TeamName
          FROM    Person, Player, Team
          WHERE   (Team.TeamId = ? OR Team.TeamId = ?)
                  AND Player.TeamId = Team.TeamId
                  AND Player.PersonId = Person.PersonId
        ";
        if(!($stmt = $db_conn->prepare($query))){
            echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
            die("Database execution failed");
        }

        if (!$stmt->bind_param('ii', $game->teamId_A(), $game->teamId_B())){
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            die("Database execution failed");
        }

        if (!$stmt->execute()) {
          throw new Exception('Failed to add Game');
        }
        $res = $stmt->get_result();
        $players = array();
        while ($row = $res->fetch_assoc()) {
          $players[] = $row;
        }

      if(!empty($_SESSION['message'])) { ?>
        <div class="<?php echo $_SESSION['message']['alert_type'];?>" role="alert">
        <?php echo $_SESSION['message']['message'];?>
        </div>
      <?php } ?>
      <div class="card" style="width: 36rem;">
        <form action="/Stats/processStatUpdate.php" method="POST" data-tmeams="form">
          <div class="card-body">
            <h5 class="card-title">Create New Game Stat</h5>
            <div class="form-group">
              <label for="teamA">Team A</label>
              <select required class="form-control" name="teamA" id="teamA">
                <option value="">--Select Player--</option>
                  <?php foreach ($players as $i => $playerArr) {  ?>
                    <option value="<?php echo $playerArr['PlayerId']; ?>">
                      <?php echo $playerArr['FirstName'].' '.$playerArr['LastName'].' ('.$playerArr['TeamName'].')';?>
                    </option>
                  <?php } ?>
              </select>
              <label for="minutes">Playing Time Minutes</label>
              <input required min="0" max="40" type="number" class="form-control" name="minutes" id="minutes" placeholder="Enter Minutes" default="0">
              <label for="seconds">Playing Time Seconds</label>
              <input required min="0" max="60" type="number" class="form-control" name="seconds" id="seconds" placeholder="Enter Seconds" default="0">
              <label for="points">Points</label>
              <input required min="0" type="number" class="form-control" name="points" id="points" placeholder="Enter Points" default="0">
              <label for="assist">Assits</label>
              <input required min="0" type="number" class="form-control" name="assists" id="assits" placeholder="Enter Assits" default="0">
              <label for="rebounds">Rebounds</label>
              <input required min="0" type="number" class="form-control" name="rebounds" id="rebounds" placeholder="Enter Rebounds" default="0">
            </div>
            <button type="submit" class="btn btn-primary">Create New Game Stat</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Game row -->
  <div class="row my-2">
    <div class="col-lg">
      <div class="card" style="width: 72rem;">
        <div class="card-body">
        <h3 class="card-title"><?php echo $teamA->teamName() . " Vs. " . $teamB->teamName() . "  (".$game->gameDate().")";?></h3>
        <p class="card-text"><?php echo $game->teamScore_A() . " - " . $game->teamScore_B(); ?></p>
        </div>
      </div>
    </div>
  </div>
  <!-- table row -->
  <div class="row my-2">
    <div class="col-lg">
<?php
$query = "
  SELECT  Person.FirstName, 
          Person.LastName, 
          Team.TeamName, 
          Stat.PlayingTimeMin, 
          Stat.PlayingTimeSec, 
          Stat.Points,
          Stat.Assists,
          Stat.Rebounds
  FROM    Stat, Player, Team, Person
  WHERE   Stat.GameId = ? 
          AND Player.PlayerId = Stat.PlayerId
          AND Team.TeamId = Player.TeamId
          AND Player.PersonId = Person.PersonID
  ORDER BY Team.TeamName
";

if(!($stmt = $db_conn->prepare($query))){
  echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
  echo "HERE I AM!!";
  die("Database execution failed");
}

if (!$stmt->bind_param('i', $id)){
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    die("Database execution failed");
}
if (!$stmt->execute()) {
  throw new Exception('Failed to game stats');
}
$stats = array();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $stats[] = $row;
}
?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Player</th>
            <th scope="col">Team</th>
            <th scope="col">Playing Time</th>
            <th scope="col">Points</th>
            <th scope="col">Assists</th>
            <th scope="col">Rebounds</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stats as $i => $statsArr) {  ?>
              <tr scope="row">
                <td><?php echo $i+1; ?></td>
                <td><?php echo ucwords(strtolower($statsArr['FirstName'] . ' ' . $statsArr['LastName'])); ?></td>
                <td><?php echo $statsArr['TeamName']; ?></td>
                <td><?php printf("%02d:%02d", $statsArr['PlayingTimeMin'], $statsArr['PlayingTimeSec']);?></td>
                <td><?php echo $statsArr['Points']; ?></td>
                <td><?php echo $statsArr['Assists']; ?></td>
                <td><?php echo $statsArr['Rebounds']; ?></td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </div> <!-- col-lg -->
  </div> <!-- end row -->
</div>
<?php 
unset($_SESSION['message']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); 
?>
