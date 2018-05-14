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
        <form action="/PlayerStats/processPlayerStatsUpdate.php" method="POST" data-tmeams="form">
          <div class="card-body">
            <h5 class="card-title">Create New Player Statistics</h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <label for="team">Team</label>
              <select required class="form-control" name="team" id="team">
                <option value="">--Select Team--</option>
                  <?php foreach ($teams as $i => $teamArr) {  ?>
                    <option value="<?php echo $teamArr['TeamId']; ?>"><?php echo $teamArr['TeamName'];?></option>
                  <?php } ?>
              </select>
            </li>
            <li class="list-group-item">
              <div class="form-row">
                <div class="col">
                  <label for="firstName">First Name</label>
                  <input required type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter First Name">
                  <label for="lastName">Last Name</label>
                  <input required type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name">
                </div>
                <div class="col">
                  <label for="gamesPlayed">Games Played</label>
                  <input type="text" class="form-control" name="gamesPlayed" id="gamesPlayed" placeholder="Enter Games Played">
                  <label for="minutes">Minutes Played</label>
                  <input type="text" class="form-control" name="minutes" id="minutes" placeholder="Enter Minutes Played">
                  <label for="pointsPerGame">Points Per Game</label>
                  <input type="text" class="form-control" name="pointsPerGame" id="pointsPerGame" placeholder="Enter Points Per Game">
                  <label for="reboundsPerGame">Rebounds Per Game</label>
                  <input type="text" class="form-control" name="reboundsPerGame" id="reboundsPerGame" placeholder="Enter Rebounds Per Game">
                  <label for="assistsPerGame">Assists Per Game</label>
                  <input type="text" class="form-control" name="assistsPerGame" id="assistsPerGame" placeholder="Enter Assists Per Game">
                  <label for="stealsPerGame">Steals Per Game</label>
                  <input type="text" class="form-control" name="stealsPerGame" id="stealsPerGame" placeholder="Enter Steals Per Game">
                  <label for="blocksPerGame">Blocks Per Game</label>
                  <input type="text" class="form-control" name="blocksPerGame" id="blocksPerGame" placeholder="Enter Blocks Per Game">
                  <label for="turnoversPerGame">Turnovers Per Game</label>
                  <input type="text" class="form-control" name="turnoversPerGame" id="turnoversPerGame" placeholder="Enter Turnovers Per Game">
                  <label for="fieldGoalsPercentage">Field Goals Percentage</label>
                  <input type="text" class="form-control" name="fieldGoalsPercentage" id="fieldGoalsPercentage" placeholder="Enter Field Goals Percentage">
                  <label for="freeThrowPercentage">Free Throw Percentage</label>
                  <input type="text" class="form-control" name="freeThrowPercentage" id="freeThrowPercentage" placeholder="Enter Free Throw Percentage">
                  <label for="threePointPercentage">Three Point Percentage</label>
                  <input type="text" class="form-control" name="threePointPercentage" id="threePointPercentage" placeholder="Enter Three Point Percentage">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Create New Player Statistics</button>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </div>
  <div class="row my-2">
    <div class="col-lg">
<?php
$query = "
  SELECT  Person.FirstName, Person.LastName, Person.PersonId, Team.TeamName
  FROM    PlayerStats, Person, Team
  WHERE   PlayerStats.PersonId=Person.PersonId and PlayerStats.TeamId = Team.TeamId";
if(!($res = $db_conn->query($query))) {
  printf("Error: %s\n", $db_conn->error);
}
$playerStats = array();
while ($row = $res->fetch_assoc()) {
  $playerStats[] = $row;
}
?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Team</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($playerStats as $i => $playerStatsArr) {  ?>
              <tr scope="row">
                <td><?php echo $i+1; ?></td>
                <td><?php echo ucwords(strtolower($playerStatsArr['FirstName'] . ' ' . $playerStatsArr['LastName'])); ?></td>
                <td><?php echo ucwords(strtolower($playerStatsArr['TeamName'])); ?></td>
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
