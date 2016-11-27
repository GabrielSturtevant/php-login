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

  public function create_user($name, $pass, $activation) {
      $query = "INSERT INTO users (username, password, activation_hash) VALUES 
                ('".$name."','".$pass."','".$activation."');";
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

  public function get_primary_key($username){
      $query = "SELECT primary_key FROM users WHERE username = '".$username."';";
      $result = $this->conn->query($query)->fetch_row();
      return $result[0];
  }

  public function add_user_info($username, $first, $last, $email){
      $key = $this->get_primary_key($username);
      $query = "INSERT INTO user_info (user_key, first_name, last_name, email) VALUES 
                  ('".$key."','".$first."','".$last."','".$email."')";
      $this->conn->query($query);
  }

  public function get_activation_hash($username){
      $query = "SELECT activation_hash FROM users WHERE username = '".$username."';";
      $result = $this->conn->query($query)->fetch_row();
      return $result[0];
  }

  public function activate_account($username){
      $query = "UPDATE users SET activated = 1 WHERE username = '".$username."';";
      $this->conn->query($query);
  }

    public function is_activated($username){
        $query = "SELECT activated FROM users WHERE username = '" . $username . "';";
        $result = $this->conn->query($query)->fetch_row();
        return $result[0];
    }
}
