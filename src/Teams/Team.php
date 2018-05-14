<?php
// Implement Team class here. It will represent an instance of a Team
// This may not be needed
class Team {

  // Instance attributes
  private $teamId = NULL;
  private $teamName = NULL;

  public static function query_by_teamid($db_conn, $teamId) {
    // Get player by public info
    $query = "SELECT * FROM Team WHERE TeamId = ?";

    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('i', $teamId)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      throw new Exception('Failed to get player');
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();
    if(empty($row)){
      return NULL;
    } else {
      return new Team($row['TeamName'], $teamId);
    }
  }

  function teamName() {
    if( func_num_args() == 0 ) {
      return $this->teamName; } else if(func_num_args() == 1) {
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

  function __construct($teamName='', $teamId=NULL) { 
    $this->teamName($teamName);
    $this->teamId($teamId);
  }

   function __toString()
   {
     return (var_export($this, true));
   }

  // @TODO: Update this
  function save($db_conn) {
    $query = "INSERT INTO Team (TeamName) values (?)";
    if(!($stmt = $db_conn->prepare($query))) {
      if($db_conn->errno == 1142){
        throw new Exception('Permission denied');
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('s', $this->teamName)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      if($db_conn->errno == 1062){
        throw new Exception('Team already exists');
      } else {
        throw new Exception('Failed to add new team');
      }
    }
    return True;
  }
}

?>
