<?php require_once($_SERVER['DOCUMENT_ROOT']  . '/header.php'); ?>
<div class="container">
  <div class="row">
    <div class="col-md">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title">Create New Team</h5>
          <form action="/Teams/processTeamUpdate.php" method="POST">
            <div class="form-group">
              <label for="teamName">Team Name</label>
              <input required type="text" class="form-control" name="teamName" id="teamName" placeholder="Enter Team Name">
            </div>
            <button type="submit" class="btn btn-primary">Create New Team</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$db = new Database();
$db_conn = $db->connect_dba(); // Temp for now until roles are properly inplace.
$query = "SELECT * FROM Team";
$res = $db_conn->query($query);
$teams = array();
while ($row = $res->fetch_assoc()) {
  $teams[] = $row;
}
?>

<table class="table">
  <tr>
    <th style="vertical-align:top; border:1px solid black; background: lightgreen;"></th>
    <th style="vertical-align:top; border:1px solid black; background: lightgreen;">TeamName</th>
  </tr>

  <?php foreach ($teams as $i => $teamArr) {  ?>
      <tr>
        <td  style="vertical-align:top; border:1px solid black;"><?php echo $i+1; ?></td>
        <td  style="vertical-align:top; border:1px solid black;"><?php echo $teamArr['TeamName']; ?></td>
      </tr>
  <?php } ?>
</table>

<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>
