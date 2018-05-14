<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
redirect_root_if_auth(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
if($_POST) {
  $email = trim($_POST['email']);
  $password1 = trim($_POST['password1']);
  $password2 = trim($_POST['password2']);
  $message = array();
  if($password1 != $password2) {
    $message['message'] = 'Passwords do not match';
    $message['alert_type'] = 'alert-danger';
  } else if (strlen($password1) < 8) {
    $message['message'] = 'Password must be at least 8 characters long.';
    $message['alert_type'] = 'alert-danger';
  } else if (!preg_match("/[a-z]/", $password1)){
    $message['message'] = 'Password requires at least one lowercase letter';
    $message['alert_type'] = 'alert-danger';
  } else if (!preg_match("/[A-Z]/", $password1)){
    $message['message'] = 'Password requires at least one uppercase letter';
    $message['alert_type'] = 'alert-danger';
  } else if (!preg_match("/[0-9]/i", $password1)){
    $message['message'] = 'Password requires at least one number';
    $message['alert_type'] = 'alert-danger';
  } else {
    $user = new User($email, $password1, 'observer');
    $db = new Database();
    $db_conn = $db->connect_observer();
    try {
      $saved = $user->save($db_conn);
    } catch (Exception $e) {
      $message['alert_type'] = 'alert-danger';
      $message['message'] = $e->getMessage();
    }

    if($saved) {
      $message['message'] = 'Succesfully created your account';
      $message['alert_type'] = 'alert-success';
      $_SESSION['message'] = $message;
      $uri = 'http://' . $_SERVER['HTTP_HOST'];
      $newURL = $url . '/Auth/login.php';
      header('Location: '.$newURL);
      exit(0);
    }
  }
  $_SESSION['message'] = $message;
}


?>
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
          <h5 class="card-title">Register</h5>
          <form action="/Auth/register.php" method="POST" data-teams="form">
            <div class="form-group">
              <label for="email">Email</label>
              <input <?php if(!empty($email)) echo 'value="'.$email.'"';?>required type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
              <label for="password1">Password</label>
              <input required type="password" class="form-control" name="password1" id="password1" placeholder="Enter your password" aria-describedby="passwordHelp">
              <small id="passwordHelp" class="form-text text-muted">Password requirements: at least 8 character, 1 uppercase, 1 lowercase.</small>
              <label for="password2">Confirm password</label>
              <input required type="password" class="form-control" name="password2" id="password2" placeholder="Confirm your password">
            </div>
            <button type="submit" class="btn btn-primary btn-success">Register</button>
            <a class="mx-3"href="/Auth/login.php">back to login</a>
          </form>
        </div>
      </div>
    </div>
  </div> <!-- end row -->
</div>
<?php 
unset($_SESSION['message']);
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); 
?>
