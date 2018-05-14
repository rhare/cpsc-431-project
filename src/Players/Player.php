<?php
// Implement Player class here. It will represent an instance of a Player
class Player {
  // Instance attributes
  private $personId = NULL;
  private $playerId = NULL;
  private $teamId = NULL;
  private $userId = NULL;
  private $firstName = "";
  private $lastName = "";
  private $street = "";
  private $city   = "";
  private $state  = "";
  private $zip    = "";
  private $country    = "";

  public static function new_player($db_conn, $firstName="", $lastName="", $email="", $street="", $city="", $state="", $zipcode="", $country="", $teamId) {
    if(empty($firstName) || empty($lastName) || empty($email)) {
      throw new Exception('First Name, Last Name, and Email are required.');
    }
    // Create person
    $query = "
      INSERT  INTO Person
        (FirstName, LastName, Email, Street, City, State, Country, ZipCode)
      VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    if(!($stmt = $db_conn->prepare($query))){
        echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
        die("Database execution failed");
    }

    // ZipCode has check constarints on it. Will only allow valid zipcode or Null.
    // Cast to NULL if empty.
    $zipcode = (empty($zipcode)) ? NULL : $zipcode;
    if (!$stmt->bind_param(
      'ssssssss', 
      $firstName,
      $lastName,
      $email,
      $street, 
      $city, 
      $state, 
      $country, 
      $zipcode
    )){
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
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
      INSERT  INTO Player
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
    return new Player($firstName, $lastName, $email, $street, $city, $state, $zip, $country, $playerId, $teamId, $personId, $userId);
  }


  public static function query_by_playerid($db_conn, $playerId) {
    // Get player by public info
    $query = "
      SELECT  Player.PersonId,
              Player.UserId,
              Player.TeamId,
              Person.FirstName,
              Person.LastName
      FROM    Player, Person
      WHERE   Player.PlayerId = ?
              AND Player.PersonId = Person.PersonId
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
      SELECT  Person.Email,
              Person.Street,
              Person.City,
              Person.State,
              Person.Country,
              Person.ZipCode
      FROM    Person
      WHERE   PersonId= ?
    ";

    if(!($stmt = $db_conn->prepare($query))) {
      if($db_conn->errno == 1142){
        new Player($firstName, $lastName, '', '', '', '', '', '', $playerId, $teamId, $personId, $userId);
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
      $email = $row['Email'];
      $street = $row['Street'];
      $city = $row['City'];
      $state = $row['State'];
      $zip = $row['ZipCode'];
      $country = $row['Country'];
      new Player($firstName, $lastName, $email, $street, $city, $state, $zip, $country, $playerId, $teamId, $personId, $userId);
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


  //   string street()                          returns street
  //
  //   void street(string $value)               set object's $street attribute 
  //                                                 in "minutes:seconds" format.
  function street() {
    if( func_num_args() == 0 ) {
      return $this->street;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
        if( is_string($value) )
          $this->street = $value;
    }
    return $this;
  }

  // city() prototypes:
  //   int city()               returns city
  //                                         
  //   void city(int $value)    set object's $city attribute
  function city() {  
    if( func_num_args() == 0 ) {
      return $this->city;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->city = $value;
    }
    return $this;
  }

  // state() prototypes:
  //   int state()            returns the state
  //
  //   void state(int $value) set object's $state attribute
  function state() {
    if( func_num_args() == 0 ) {
      return $this->state;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if( is_string($value) )
        $this->state = $value;
    }
    return $this;
  }

  // zipcode() prototypes:
  //   int zipcode()               returns the zipcode
  //
  //   void zipcode(int $value)    set object's $zipcode attribute
  function zipcode() {
    // int rebounds()
    if( func_num_args() == 0 ) {
      return $this->zipcode;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if (is_string($value))
        $this->zipcode = $value;
    }
    return $this;
  }

  // country() prototypes:
  //   string country()               returns the country
  //
  //   void country(string $value)    set object's $country attribute
  function country() {
    // int rebounds()
    if( func_num_args() == 0 ) {
      return $this->country;
    } else if( func_num_args() == 1 ) {
      $value = func_get_arg(0);
      if (is_string($value))
        $this->country = $value;
    }
    return $this;
  }

  function __construct($firstName="", $lastName="", $email="", $street="", $city="", $state="", $zip="", $country="", $playerId=NULL, $teamId=NULL, $personId=NULL, $userId=NULL) {
    $this->firstName($firstName);
    $this->lastName($lastName);
    $this->street($street);
    $this->city($city);
    $this->state($state);
    $this->zipcode($zip);
    $this->country($country);
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
        Email = ?,
        Street = ?,
        City = ?,
        State = ?,
        Country = ?,
        ZipCode = ?
      WHERE PersonId = ?
    ";

    if(!($stmt = $db_conn->prepare($query))){
      if($db_conn->errno == 1142){
        throw new Exception('Permission denied');
      }
      echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
      die("Database execution failed");
    }

    // ZipCode has check constarints on it. Will only allow valid zipcode or Null.
    // Cast to NULL if empty.
    $zipcode = (empty($this->zipcode)) ? NULL : $this->zipcode;
    if (!$stmt->bind_param(
      'ssssssssi', 
      $this->firstName,
      $this->lastName,
      $this->email,
      $this->street, 
      $this->city, 
      $this->state, 
      $this->country, 
      $zipcode,
      $this->personId
    )){
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        die("Database execution failed");
    }
    if (!$stmt->execute()) {
      throw new Exception('Failed to save the player');
    }
    return True;
  }
}
?>
