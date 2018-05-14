<?php
class Game {
  // Instance attributes
  private $gameId = NULL;
  private $teamId_A = NULL;
  private $teamId_B = NULL;
  private $teamScore_A = NULL;
  private $teamScore_B = NULL;
  private $gameDate = "";

  public static function new_game($db_conn, $teamId_A, $teamId_B, $teamScore_A, $teamScore_B, $gameDate) {
    if(empty($gameDate))
      $gameDate = date("Y-m-d");
    // Create person
    $query = "
      INSERT  INTO Game
        (TeamId_A, TeamId_B, TeamScore_A, TeamScore_B, GameDate)
      VALUES
        (?, ?, ?, ?, ?)
    ";
    if(!($stmt = $db_conn->prepare($query))){
        echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
        die("Database execution failed");
    }

    if (!$stmt->bind_param('iiiis', $teamId_A, $teamId_B, $teamScore_A, $teamScore_B, $gameDate)){
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        die("Database execution failed");
    }

    if (!$stmt->execute()) {
      throw new Exception('Failed to add Game');
    }

    // Get new person ID
    $query = "SELECT LAST_INSERT_ID()";
    $res = $db_conn->query($query);
    $row = $res->fetch_assoc();
    $gameId = $row['LAST_INSERT_ID()'];

    return new Game($teamId_A, $teamId_B, $teamScore_A, $teamScore_B, $gameDate, $gameId);
  }


  public static function query_by_gameid($db_conn, $gameId) {
    // Get player by public info
    $query = "SELECT * FROM Game WHERE GameId = ?";

    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('i', $gameId)){
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
      $teamId_A = $row['TeamId_A'];
      $teamId_B = $row['TeamId_B'];
      $teamScore_A = $row['TeamScore_A'];
      $teamScore_B = $row['TeamScore_B'];
      $gameDate = $row['GameDate'];
      return new Game($teamId_A, $teamId_B, $teamScore_A, $teamScore_B, $gameDate, $gameId);
    }
  }

  function teamId_A() {
    if( func_num_args() == 0 ) {
      return $this->teamId_A;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamId_A = (int) $value;
    }
    return $this;
  }

  function teamId_B() {
    if( func_num_args() == 0 ) {
      return $this->teamId_B;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamId_B = (int) $value;
    }
    return $this;
  }

  function teamScore_A() {
    if( func_num_args() == 0 ) {
      return $this->teamScore_A;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamScore_A = (int) $value;
    }
    return $this;
  }

  function teamScore_B() {
    if( func_num_args() == 0 ) {
      return $this->teamScore_B;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamScore_B = (int) $value;
    }
    return $this;
  }

  function gameDate() {
    if( func_num_args() == 0 ) {
      return $this->gameDate;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->gameDate = date("Y-m-d",strtotime($value));
    }
    return $this;
  }

  function gameId() {
    if( func_num_args() == 0 ) {
      return $this->gameId;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->gameId = (int) $value;
    }
    return $this;
  }

  function __construct($teamId_A=NULL, $teamId_B=NULL, $teamScore_A=0, $teamScore_B=0, $gameDate="", $gameId=NULL) {
    $this->teamId_A($teamId_A);
    $this->teamId_B($teamId_B);
    $this->teamScore_A($teamScore_A);
    $this->teamScore_B = $teamScore_B;
    $this->gameDate($gameDate);
    $this->gameId = $gameId;
  }

  function __toString() {
      return (var_export($this, true));
  }


  function save($db_conn) {
    $query = "
      UPDATE Game
      SET
        TeamScore_A = ?,
        TeamScore_B = ?,
        GameDate =?
      WHERE GameId = ?
    ";

    if(!($stmt = $db_conn->prepare($query))){
      if($db_conn->errno == 1142){
        throw new Exception('Permission denied');
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }

    if (!$stmt->bind_param(
      'iisi', 
      $this->TeamScore_A(),
      $this->TeamScore_B(),
      $this->gameDate(),
      $this->gameId()
    )){
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        die("Database execution failed");
    }
    if (!$stmt->execute()) {
      throw new Exception('Failed to save the game');
    }
    return True;
  }
}
?>
