<?php
// Implement Team class here. It will represent an instance of a Team
// This may not be needed
class Team {

  // Instance attributes
  private $teamId = NULL;
  private $teamName = NULL;

  function teamName() {
    if( func_num_args() == 0 ) {
      return $this->teamName;
    } else if(func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamName = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function teamId() {
    if( func_num_args() == 0 ) {
      return $this->teamId;
    }
    return $this;
  }

  function __construct($teamName='') { 
    $this->teamName($teamName);
  }

   function __toString()
   {
     return (var_export($this, true));
   }

  // @TODO: Update this
  function save($db_conn) {
    $query = "INSERT INTO Team (TeamName) values (?)";
    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('s', $this->teamName)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }
    $stmt->execute();
    return True;
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
} 

?>
