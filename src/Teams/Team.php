<?php
// Implement Team class here. It will represent an instance of a Team
// This may not be needed
class Team {

  // Instance attributes
  private $teamId = NULL;
  private $teamName = NULL;

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
