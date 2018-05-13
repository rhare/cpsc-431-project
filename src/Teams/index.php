<?php require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); ?>
<div class="container my-3">
  <div class="row my-2">
    <div class="col-s">
      <?php 
      if(!empty($_SESSION['message'])) { ?>
        <div class="<?php echo $_SESSION['message']['alert_type'];?>" role="alert">
        <?php echo $_SESSION['message']['message'];?>
        </div>
      <?php } ?>
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title">Create New Team</h5>
          <form action="/Teams/processTeamUpdate.php" method="POST" data-teams="form">
            <div class="form-group">
              <label for="teamName">Team Name</label>
              <input required type="text" class="form-control" name="teamName" id="teamName" placeholder="Enter Team Name">
            </div>
            <button type="submit" class="btn btn-primary">Create New Team</button>
          </form>
        </div>
      </div>
    </div>
  </div> <!-- end row -->
  <div class="row my-2">
    <div class="col-lg">

<?php
$db = new Database();
$db_conn = $db->connect_observer();
$query = "SELECT * FROM Team";
$res = $db_conn->query($query);
$teams = array();
while ($row = $res->fetch_assoc()) {
  $teams[] = $row;
}
?>
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Team Name</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($teams as $i => $teamArr) {  ?>
              <tr scope="row">
                <td><?php echo $i+1; ?></td>
                <td><?php echo $teamArr['TeamName']; ?></td>
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
