<?php
class connection {
  
  //mysql credentials
  private $servername = "localhost";
  private $username = "root";
  private $password = "password";
  
  //connection to mysql
  private $conn;

  //constructor
  public function __construct() {
  	$this->conn = new mysqli($this->servername, $this->username, $this->password);
  	$this->conn->query("USE secure_database");
  }

  public function insert($name, $pass) {
      $query = "INSERT INTO users (username, password, salt) VALUES ('".$name."', '".$pass."');";
      $this->conn->query($query);
  }

  public function check_availability($name){
  	$query = "SELECT * FROM users WHERE username = '".$name."';";
	return $this->conn->query($query);
  }

  public function get_login_info($name) {
      $query = "SELECT * FROM users WHERE username = '".$name."';";
      return $this->conn->query($query)->fetch_row();
  }
}
