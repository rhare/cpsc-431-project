<?php
class Database
{
  // Instance attributes
  private $db_host = '192.168.99.100';  // Docker container
  private $db_name = 'BBDB';

  // Define default user and password
  private $db_user = 'observer';
  private $db_password = 'observer-pw1';

  // Users and passwords
  private $db_users = ['observer', 'users', 'manager', 'dba'];
  private $db_password_observer = 'observer-pw1';
  private $db_password_users = 'users-pw1';
  private $db_password_manager = 'manager-pw1';
  private $db_password_dba = 'dba-pw1';

  public function __construct($db_host='', $db_name='', $db_user='', $db_password='') {
    if(!empty($db_host)) $this->db_host = $db_host;
    if(!empty($db_name)) $this->db_name = $db_name;
    if(!empty($db_user)) $this->db_user = $db_user;
    if(!empty($db_password)) $this->db_password = $db_password;
  }

  private function connect($role) {
    $mysqli = new mysqli($this->db_host, $role, $this->db_password_observer, $this->db_name);
    /* check connection */
    if (mysqli_connect_errno()) {
        printf("connect failed: %s\n", mysqli_connect_errno());
        die('Database Connection Error');
    }
    return $mysqli;
  }

  public function connect_observer() {
    return $this->connect('observer');
  }


  public function connect_users() {
    return $this->connect('users');
  }


  public function connect_manager() {
    return $this->connect('manager');
  }


  public function connect_dba() {
    return $this->connect('dba');
  }

  public function connect_by_role($role) {
    if ($role == 'users') return $this->connect_users();
    if ($role == 'manager') return $this->connect_manager();
    if ($role == 'dba') return $this->connect_dba();
    return $this->connect_observer();
  }
}
