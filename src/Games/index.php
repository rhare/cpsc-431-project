<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

if(!empty($_GET) && isset($_GET['id'])) {
  require('viewGame.php');
  exit(0);
}
?>
<?php require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); ?>
<div class="container my-3">
  <div class="row my-2">
    <div class="col-s">
      <?php 
        // Generate list of teams.
        $db = new Database();
        $db_conn = $db->connect_observer();
        $query = "SELECT TeamName, TeamId from Team";
        $res = $db_conn->query($query);
        $teams = array();
        while ($row = $res->fetch_assoc()) {
          $teams[] = $row;
        }

      if(!empty($_SESSION['message'])) { ?>
        <div class="<?php echo $_SESSION['message']['alert_type'];?>" role="alert">
        <?php echo $_SESSION['message']['message'];?>
        </div>
      <?php } ?>
      <div class="card" style="width: 36rem;">
        <form action="/Games/processGameUpdate.php" method="POST" data-tmeams="form">
          <div class="card-body">
            <h5 class="card-title">Create New Game</h5>
            <div class="form-group">
              <label for="teamA">Team A</label>
              <select required class="form-control" name="teamA" id="teamA">
                <option value="">--Select Team--</option>
                  <?php foreach ($teams as $i => $teamArr) {  ?>
                    <option value="<?php echo $teamArr['TeamId']; ?>"><?php echo $teamArr['TeamName'];?></option>
                  <?php } ?>
              </select>
              <label for="teamB">Team B</label>
              <select required class="form-control" name="teamB" id="teamB">
                <option value="">--Select Team--</option>
                  <?php foreach ($teams as $i => $teamArr) {  ?>
                    <option value="<?php echo $teamArr['TeamId']; ?>"><?php echo $teamArr['TeamName'];?></option>
                  <?php } ?>
              </select>
              <label for="teamScoreA">Team Score A</label>
              <input required type="number" class="form-control" name="teamScoreA" id="teamScoreA" placeholder="Enter Team A Score" default="0">
              <label for="teamScoreA">Team Score B</label>
              <input required type="number" class="form-control" name="teamScoreB" id="teamScoreB" placeholder="Enter Team B Score" default="0">
              <label for="gameDate">Game Date</label>
              <input type="date" class="form-control" name="gameDate" id="gameDate" placeholder="Enter Game Date">
            </div>
            <button type="submit" class="btn btn-primary">Create New Game</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row my-2">
    <div class="col-lg">
<?php
$query = "
  SELECT  TA.TeamName as TeamName_A, 
          TB.TeamName as TeamName_B,
          G.GameId,
          G.TeamScore_A,
          G.TeamScore_B,
          G.GameDate
  FROM    Game as G, 
          Team as TA, 
          Team as TB
  WHERE   G.TeamId_A = TA.TeamId 
          AND G.TeamId_B = TB.TeamId";

if(!($res = $db_conn->query($query))) {
  printf("Error: %s\n", $db_conn->error);
  exit(0);
}
$games = array();
while ($row = $res->fetch_assoc()) {
  $games[] = $row;
}
?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Game</th>
            <th scope="col">Team A Score</th>
            <th scope="col">Team B Score</th>
            <th scope="col">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($games as $i => $gameArr) {  ?>
              <tr scope="row">
                <td><?php echo $i+1; ?></td>
                <td>
                <a href="/Games/?id=<?php echo $gameArr['GameId'];?>">
                    <?php echo ucwords(strtolower($gameArr['TeamName_A'] . ' vs. ' . $gameArr['TeamName_B'])); ?>
                  </a>
                </td>
                <td><?php echo $gameArr['TeamScore_A']; ?></td>
                <td><?php echo $gameArr['TeamScore_B']; ?></td>
                <td><?php echo $gameArr['GameDate']; ?></td>
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
