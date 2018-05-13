<?php
// Implement User class here. It will represent an instance of a User. 
class User {
  // Instance attributes
  private $userId = NULL;
  private $email = NULL;
  private $passwordHash = NULL;
  private $role = 'observer';

  public static function query_by_email($db_conn, $email) {
    $query = "SELECT UserId, Email, Role from User where Email = ? LIMIT 1";
    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('s', $email)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      if($db_conn->errno == 1062){
        throw new Exception('User already exists');
      } else {
        throw new Exception('Failed to create the user account');
      }
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();
    return empty($row) ? Null : new User($row['Email'], '', $row['Role'], $row['UserId']);
  }


  public static function query_by_id($db_conn, $userId) {
    $query = "SELECT UserId, Email, Role from User where UserId = ? LIMIT 1";
    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('s', $userId())){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      if($db_conn->errno == 1062){
        throw new Exception('User already exists');
      } else {
        throw new Exception('Failed to create the user account');
      }
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();
    return empty($row) ? Null : new User($row['Email'], '', $row['Role'], $row['UserId']);
  }


  function check_password($db_conn, $password) {
    $query = "SELECT PasswordHash from User where UserId = ?";
    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('s', $this->userId())){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      echo "Execute failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();
    return empty($row) ? False : password_verify($password, $row['PasswordHash']);
  }


  function set_password($db_conn, $password) {
    $this->passwordHash(password_hash($password, PASSWORD_DEFAULT));
    $query = "CALL set_password(?, ?)";
    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('ss', $this->userId(), $this->passwordHash())){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      echo "Execute failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    return True;
  }

  
  function email() {
    if( func_num_args() == 0 ) {
      return $this->email;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->email = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function passwordHash() {
    if( func_num_args() == 0 ) {
      return $this->passwordHash;
    } else if(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->passwordHash = $value;
    }
    return $this;
  }


  function role() {
    if( func_num_args() == 0 ) {
      return $this->role;
    } else if(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->role = htmlspecialchars(trim($value));
    }
    return $this;
  }


  function userId() {
    if( func_num_args() == 0 ) {
      return $this->userId;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->userId = (int) $value;
    }
    return $this;
  }


  function __construct($email='', $password='', $role='observer', $userId=NULL) { 
    $this->email($email);
    $this->passwordHash(password_hash($password, PASSWORD_DEFAULT));
    $this->role($role);
    $this->userId($userId);
  }


   function __toString()
   {
     return (var_export($this, true));
   }


  function save($db_conn) {
    $query = "CALL new_user(?, ?, ?)";
    if(!($stmt = $db_conn->prepare($query))) {
      if($db_conn->errno == 1142){
        throw new Exception('Permission denied');
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('sss', $this->email(), $this->passwordHash(), $this->role())){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      if($db_conn->errno == 1062){
        throw new Exception('User already exists');
      } else {
        throw new Exception('Failed to create the user account');
      }
    }
    return True;
  }
} // end class User
?>
