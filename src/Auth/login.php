<?php 
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/tools.php');
redirect_root_if_auth(); // Starts session, also imports User

require_once($_SERVER['DOCUMENT_ROOT']  . '/Auth/User.php');
require_once($_SERVER['DOCUMENT_ROOT']  . '/utils/Database.php');

if($_POST) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $db = new Database();
  $db_conn = $db->connect_observer();
  $user = User::query_by_email($db_conn, $email);
  if(!empty($user) && $user->check_password($db_conn, $password)) {
    // success login
    $_SESSION['user'] = serialize($user);
    $uri = 'http://' . $_SERVER['HTTP_HOST'];
    $newURL = $url . '/';
    header('Location: '.$newURL);
    exit(0);
  } else {
    $message['message'] = 'Invalid crednetials';
    $message['alert_type'] = 'alert-danger';
    $_SESSION['message'] = $message;
  }
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
          <h5 class="card-title">Login</h5>
          <form action="/Auth/login.php" method="POST" data-teams="form">
            <div class="form-group">
              <label for="email">Email</label>
              <input required type="email" class="form-control my-2" name="email" id="email" placeholder="Enter your email">
              <label for="password">Password</label>
              <input required type="password" class="form-control my-2" name="password" id="password" placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a class="mx-3"href="/Auth/register.php">Register</a>
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
