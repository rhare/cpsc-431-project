<?php
// Implement Player class here. It will represent an instance of a Player
class PlayerStats {
  // Instance attributes
  private $personId = NULL;
  private $playerId = NULL;
  private $teamId = NULL;
  private $userId = NULL;
  private $firstName = "";
  private $lastName = "";
  private $gamesPlayed = "";
  private $minutes  = "";
  private $pointsPerGame  = "";
  private $reboundsPerGame    = "";
  private $assistsPerGame    = "";
  private $stealsPerGame    = "";
  private $blocksPerGame    = "";
  private $turnoversPerGame    = "";
  private $fieldGoalsPercentage    = "";
  private $freeThrowPercentage    = "";
  private $threePointPercentage   = "";

  public static function new_player_stats($db_conn, $firstName="", $lastName="", $gamesPlayed="", $minutes="", $pointsPerGame="", $reboundsPerGame="", $assistsPerGame="", $stealsPerGame="", $blocksPerGame="",
  $turnoversPerGame="", $fieldGoalsPercentage="", $freeThrowPercentage="", $threePointPercentage="", $teamId) {
    if(empty($firstName) || empty($lastName)) {
      throw new Exception('First Name and Last Name are required.');
    }
    // Create person
    $query = "
      INSERT  INTO Person
        (FirstName, LastName, GP, Min, PPG, RPG, APG, SPG, BPG, TPG, FGP, FTP, TPP)
      VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    if(!($stmt = $db_conn->prepare($query))){
        echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
        die("Database execution failed");
    }


    if (!$stmt->execute()) {
      if($db_conn->errno == 1062){
        throw new Exception('Person already exists  already exists.');
      } else {
        throw new Exception('Failed to add Player');
      }
    }

    // Get new person ID
    $query = "SELECT LAST_INSERT_ID()";
    $res = $db_conn->query($query);
    $row = $res->fetch_assoc();
    $personId = $row['LAST_INSERT_ID()'];

    // Create player
    $query = "
      INSERT  INTO PlayerStats
        (PersonId, TeamId)
      VALUES
        (?, ?)
    ";

    if(!($stmt = $db_conn->prepare($query))){
        echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
        die("Database execution failed");
    }

    if (!$stmt->bind_param('ii', $personId, $teamId)){
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        die("Database execution failed");
    }

    if (!$stmt->execute()) {
      throw new Exception('Failed to create player');
    }

    // Get new player ID
    $query = "SELECT LAST_INSERT_ID()";
    $res = $db_conn->query($query);
    $row = $res->fetch_assoc();
    $playerId = $row['LAST_INSERT_ID()'];
    return new PlayerStats($firstName, $lastName, $gamesPlayed, $minutes, $pointsPerGame, $reboundsPerGame, $assistsPerGame, $stealsPerGame, $blocksPerGame, $turnoversPerGame, $fieldGoalsPercentage, $freeThrowPercentage, $threePointPercentage, $userId);
  }


  public static function query_by_playerid($db_conn, $playerId) {
    // Get player by public info
    $query = "
      SELECT  PlayerStats.PersonId,
              PlayerStats.UserId,
              PlayerStats.TeamId,
              PersonStats.FirstName,
              PersonStats.LastName
      FROM    PlayerStats, Person
      WHERE   PlayerStats.PlayerId = ?
              AND PlayerStats.PersonId = Person.PersonId
    ";

    if(!($stmt = $db_conn->prepare($query))) {
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('i', $playerId)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      throw new Exception('Failed to get player');
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();

    $firstName = $row['FirstName'];
    $lastName = $row['LastName'];
    $personId = $row['PersonId'];
    $userId = $row['UserId'];
    $teamId = $row['TeamId'];

    // Try to get private information
    $query = "
      SELECT  Person.GP,
              Person.Min,
              Person.PPG,
              Person.RPG,
              Person.APG,
              Person.SPG,
              Person.BPG,
              Person.TPG,
              Person.FGP,
              Person.FTP,
              Person.TPP
      FROM    Person
      WHERE   PersonId= ?
    ";

    if(!($stmt = $db_conn->prepare($query))) {
      if($db_conn->errno == 1142){
        new Player($firstName, $lastName, '', '', '', '', '', '', '', '', '', '', '', $playerId, $teamId, $personId, $userId);
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }
    if (!$stmt->bind_param('i', $personId)){
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      die("Database execution failed");
    }

    if (!$stmt->execute()) {
      throw new Exception('Failed to get player');
    }
    $res = $stmt->get_result();
    $row =  $res->fetch_assoc();
    if (empty($row))
      return Null;
    else {
      $gamePlayed    = $row['GP'];
      $minutes  = $row['Min'];
      $pointsPerGame  = $row['PPG'];
      $reboundsPerGame  = $row['RPG'];
      $assistsPerGame  = $row['APG'];
      $stealsPerGame  = $row['SPG'];
      $blocksPerGame  = $row['BPG'];
      $turnoversPerGame  = $row['TPG'];
      $fieldGoalsPercentage  = $row['FGP'];
      $freeThrowPercentage  = $row['FTP'];
      $threePointPercentage  = $row['TPP'];
      new Player($firstName, $lastName, $gamesPlayed, $minutes, $pointsPerGame, $reboundsPerGame, $assistsPerGame, $stealsPerGame, $blocksPerGame, $turnoversPerGame, $fieldGoalsPercentage, $freeThrowPercentage, $threePointPercentage, $playerId, $teamId, $personId, $userId);
    }
  }

  // Operations

  // name() prototypes:
  //   string name()                          returns name in "Last, First" format.
  //                                          If no first name assigned, then return in "Last" format.
  //
  //   void name(string $value)               set object's $name attribute in "Last, First"
  //                                          or "Last" format.
  //
  //   void name(array $value)                set object's $name attribute in [first, last] format
  //
  //   void name(string $first, string $last) set object's $name attribute
  function teamId() {
    if( func_num_args() == 0 ) {
      return $this->teamId;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->teamId = (int) $value;
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


  function playerId() {
    if( func_num_args() == 0 ) {
      return $this->playerId;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->playerId = (int) $value;
    }
    return $this;
  }

  function personId() {
    if( func_num_args() == 0 ) {
      return $this->personId;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->personId = (int) $value;
    }
    return $this;
  }
  function firstName() {
    if( func_num_args() == 0 ) {
      return $this->firstName;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->firstName = htmlspecialchars(trim($value));
    }
    return $this;
  }
  function lastName() {
    if( func_num_args() == 0 ) {
      return $this->lastName;
    } else if (func_num_args() == 1) {
      $value = func_get_arg(0);
      $this->lastName = htmlspecialchars(trim($value));
    }
    return $this;
  }

  function gamesPlayed() {
    if( func_num_args() == 0 ) {
      return $this->gamesPlayed;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
        if( is_string($value) )
          $this->gamesPlayed = $value;
    }
    return $this;
  }

  function minutes() {
    if( func_num_args() == 0 ) {
      return $this->minutes;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->minutes = $value;
    }
    return $this;
  }

  function pointsPerGame() {
    if( func_num_args() == 0 ) {
      return $this->pointsPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->pointsPerGame = $value;
    }
    return $this;
  }

  function reboundsPerGame() {
    if( func_num_args() == 0 ) {
      return $this->reboundsPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->reboundsPerGame = $value;
    }
    return $this;
  }

  function assistsPerGame() {
    if( func_num_args() == 0 ) {
      return $this->assistsPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->assistsPerGame = $value;
    }
    return $this;
  }

  function stealsPerGame() {
    if( func_num_args() == 0 ) {
      return $this->stealsPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->stealsPerGame = $value;
    }
    return $this;
  }

  function blocksPerGame() {
    if( func_num_args() == 0 ) {
      return $this->blocksPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->blocksPerGame = $value;
    }
    return $this;
  }

  function turnoversPerGame() {
    if( func_num_args() == 0 ) {
      return $this->turnoversPerGame;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->turnoversPerGame = $value;
    }
    return $this;
  }

  function fieldGoalsPercentage() {
    if( func_num_args() == 0 ) {
      return $this->fieldGoalsPercentage;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->fieldGoalsPercentage = $value;
    }
    return $this;
  }

  function freeThrowPercentage() {
    if( func_num_args() == 0 ) {
      return $this->freeThrowPercentage;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->freeThrowPercentage = $value;
    }
    return $this;
  }

  function threePointPercentage() {
    if( func_num_args() == 0 ) {
      return $this->threePointPercentage;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->threePointPercentage = $value;
    }
    return $this;
  }





  function __construct($firstName="", $lastName="", $gamesPlayed="", $minutes="", $pointsPerGame="", $reboundsPerGame="", $assistsPerGame="", $stealsPerGame="", $blocksPerGame="", $turnoversPerGame="", $fieldGoalsPercentage="", $freeThrowPercentage="", $threePointPercentage="", $playerId=NULL, $teamId=NULL, $personId=NULL, $userId=NULL) {
    $this->firstName($firstName);
    $this->lastName($lastName);
    $this->gamesPlayed($gamesPlayed);
    $this->minutes($minutes);
    $this->pointsPerGame($pointsPerGame);
    $this->reboundsPerGame($reboundsPerGame);
    $this->assistsPerGame($assistsPerGame);
    $this->stealsPerGame($stealsPerGame);
    $this->blocksPerGame($blocksPerGame);
    $this->turnoversPerGame($turnoversPerGame);
    $this->fieldGoalsPercentage($fieldGoalsPercentage);
    $this->freeThrowPercentage($freeThrowPercentage);
    $this->threePointPercentage($threePointPercentage);
    $this->playerId($playerId);
    $this->teamId($teamId);
    $this->personId($personId);
    $this->userId($userId);
  }

  function __toString() {
      return (var_export($this, true));
  }



  function save($db_conn) {
    $query = "
      UPDATE PERSON
      SET
        FirstName = ?,
        LastName = ?,
        GP = ?,
        Min = ?,
        PPG = ?,
        RPG = ?,
        APG = ?,
        SPG = ?,
        BPG = ?,
        TPG = ?,
        FGP = ?,
        FTP = ?,
        TPP = ?
      WHERE PersonId = ?
    ";

    if(!($stmt = $db_conn->prepare($query))){
      if($db_conn->errno == 1142){
        throw new Exception('Permission denied');
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }


    if (!$stmt->execute()) {
      throw new Exception('Failed to save the player');
    }
    return True;
  }
}
?>
