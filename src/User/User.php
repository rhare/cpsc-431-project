<?php
class User
{

  //@TODO: 
  //  - Update to use stored procedures to allow
  //    new users to be created.
  //  - Add a 'change_password' function that calls
  //    the change password stored proc.

  // Instance attributes
  private $userId = NULL;
  private $userName = NULL;
  private $email = NULL;
  private $passwordHash = NULL;
  private $role = 'observer';

  function userName() {
    if( func_num_args() == 0 ) {
      return $this->userName;
    }  if else(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->userName = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function email() {
    if( func_num_args() == 0 ) {
      return $this->email;
    } if else(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->email = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function passwordHash() {
    if( func_num_args() == 0 ) {
      return $this->passwordHash
    }  if else(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->passwordHash = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function role() {
    if( func_num_args() == 0 ) {
      return $this->role
    }  if else(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->role = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function __construct($userName='', $email='', $passwordHash='', $role='observer') { 
    $this->userName($userName);
    $this->email($email);
    $this->passwordHash($passwordHash);
    $this->role($role);
  }

   function __toString()
   {
     return (var_export($this, true));
   }

  // @TODO: Update this
  function toDB($db_conn) {
    return False;
    //if(empty($this->userId)) {
    //  // New user.
    //  $query = "INSERT INTO Statistics
    //            (Player, PlayingTimeMin, PlayingTimeSec, Points, Assists, Rebounds)
    //            VALUES
    //            ((SELECT ID FROM TeamRoster Where Name_Last = ? AND Name_First = ?)), ?, ?, ?, ?, ? )";
    //  if(!($stmt = $db_conn->prepare($query))) {
    //    echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
    //    die("Database execution failed");
    //  }
    //  if (!$stmt->bind_param(
    //    'ssiiiii', 
    //    $this->name['LAST'],
    //    $this->name['FIRST'],
    //    $this->playingTime['MINS'], 
    //    $this->playingTime['SECS'], 
    //    $this->pointsScored, 
    //    $this->assists, 
    //    $this->rebounds
    //  )){
    //    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    //    die("Database execution failed");
    //  }
    //  $stmt->execute();
    //} else {
    //  $query = "INSERT INTO Statistics
    //    (Player, PlayingTimeMin, PlayingTimeSec, Points, Assists, Rebounds)
    //    VALUES
    //    (?, ?, ?, ?, ?, ?)";
    //  if(!($stmt = $db_conn->prepare($query))){
    //    echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
    //    die("Database execution failed");
    //  }
    //  if (!$stmt->bind_param(
    //    'iiiiii', 
    //    $this->playerID,
    //    $this->playingTime['MINS'], 
    //    $this->playingTime['SECS'], 
    //    $this->pointsScored, 
    //    $this->assists, 
    //    $this->rebounds
    //  )){
    //    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    //    die("Database execution failed");
    //  }
    //  $stmt->execute();
    //}
  }
} // end class User

?>

