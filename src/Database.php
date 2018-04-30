<?php
class Database
{
  // Instance attributes
  private $db_host = '192.168.99.100';  // Docker container
  private $db_name = 'BBDB';
  private $db_users = ['observer', 'users', 'manager', 'dba'];
  private $db_password_observer = 'observer-pw1';
  private $db_password_users = 'users-pw1';
  private $db_password_manager = 'manager-pw1';
  private $db_password_dba = 'dba-pw1';

  function connect_observer() {
    $mysqli = new mysqli($this->db_host, 'observer', $this->db_password_observer, $this->db_name);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_errno());
        die('Database Connection Error');
    }
    return $mysqli;
  }

  function connect_users() {
    $mysqli = new mysqli($this->db_host, 'users', $this->db_password_users, $this->db_name);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_errno());
        die('Database Connection Error');
    }
    return $mysqli;
  }


  function connect_manager() {
    $mysqli = new mysqli($this->db_host, 'manager', $this->db_password_manager, $this->db_name);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_errno());
        die('Database Connection Error');
    }
    return $mysqli;
  }


  function connect_dba() {
    $mysqli = new mysqli($this->db_host, 'dba', $this->db_password_dba, $this->db_name);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_errno());
        die('Database Connection Error');
    }
    return $mysqli;
  }

  function connect_by_role($role) {
    if ($role == 'users') return $this->connect_observer();
    if ($role == 'manager') return $this->connect_observer();
    if ($role == 'dba') return $this->connect_observer();
    return $this->connect_observer();
  }
}
