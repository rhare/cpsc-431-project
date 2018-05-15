<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
$user = get_user_or_redirect_login(); // Starts session, also imports User
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php'); 

// This will display user setting options such as change password, promote user, logout, etc
$db = new Database();
$db_conn = $db->connect_observer();
$query = "SELECT UserId, Email from User";
$res = $db_conn->query($query);
$users = array();
while ($row = $res->fetch_assoc()) {
  $users[] = $row;
}

if(!empty($_SESSION['message'])) { ?>
<div class="<?php echo $_SESSION['message']['alert_type'];?>" role="alert">
<?php echo $_SESSION['message']['message'];?>
</div>
<?php } ?>

<div class="container my-3">
  <div class="row my-2">
    <div class="col-s">
      <div class="card" style="width: 36rem;">
        <form action="/Settings/promoteUser.php" method="POST" data-teams="form">
          <div class="card-body">
            <h5 class="card-title">Promote User</h5>
            <div class="form-group">
                <div class="form-group">
                  <label for="userId">User</label>
                    <select required class="form-control" name="userId" >
                      <option value="">--Select User--</option>
                        <?php foreach ($users as $i => $userArr) {  ?>
                          <option value="<?php echo $userArr['UserId']; ?>">
                            <?php echo $userArr['Email'];?>
                          </option>
                        <?php } ?>
                    </select>
                  <label for="role">User</label>
                    <select required class="form-control" name="role" >
                      <option value="">--Select User--</option>
                      <option value="observer">Observer</option>
                      <option value="users">User</option>
                      <option value="manager">Manager</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Promote User</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-s">
      <div class="card" style="width: 36rem;">
        <form action="/Settings/resetPassword.php" method="POST" data-teams="form">
          <div class="card-body">
            <h5 class="card-title">Reset Password</h5>
            <div class="form-group">
                <div class="form-group">
                  <label for="userId">User</label>
                    <select required class="form-control" name="userId" >
                      <option value="">--Select User--</option>
                        <?php foreach ($users as $i => $userArr) {  ?>
                          <option value="<?php echo $userArr['UserId']; ?>">
                            <?php echo $userArr['Email'];?>
                          </option>
                        <?php } ?>
                    </select>
                  <label for="password">Password</label>
                  <input required type="password" class="form-control" name="password" placeholder="Enter Password">
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?
unset($_SESSION['message']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>
