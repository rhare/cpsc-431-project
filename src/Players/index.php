<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); 
?>
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
        <form action="/Players/processPlayerUpdate.php" method="POST" data-tmeams="form">
          <div class="card-body">
            <h5 class="card-title">Create New Player</h5>
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
                  <label for="email">Email</label>
                  <input required type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                </div>
                <div class="col">
                  <label for="street">Street</label>
                  <input type="text" class="form-control" name="street" id="street" placeholder="Enter Street">
                  <label for="city">City</label>
                  <input type="text" class="form-control" name="city" id="city" placeholder="Enter City">
                  <label for="state">State</label>
                  <input type="text" class="form-control" name="state" id="state" placeholder="Enter State">
                  <label for="country">Country</label>
                  <input type="text" class="form-control" name="country" id="country" placeholder="Enter Country">
                  <label for="zipCode">Zip Code</label>
                  <input type="text" class="form-control" name="zipCode" id="zipCode" placeholder="Enter Zip Code">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Create New Player</button>
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
  FROM    Player, Person, Team 
  WHERE   Player.PersonId=Person.PersonId and Player.TeamId = Team.TeamId";
if(!($res = $db_conn->query($query))) {
  printf("Error: %s\n", $db_conn->error);
}
$players = array();
while ($row = $res->fetch_assoc()) {
  $players[] = $row;
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
          <?php foreach ($players as $i => $playerArr) {  ?>
              <tr scope="row">
                <td><?php echo $i+1; ?></td>
                <td><?php echo ucwords(strtolower($playerArr['FirstName'] . ' ' . $playerArr['LastName'])); ?></td>
                <td><?php echo ucwords(strtolower($playerArr['TeamName'])); ?></td>
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
