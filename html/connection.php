<?php
class connection {

  //connection to mysql
  private $conn;

  public function __construct() {
	  $config = parse_ini_file('../private/credentials.ini');
  	$this->conn = new mysqli($config['servername'], $config['username'],$config['password']);
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

  private function get_primary_key($username){
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

  public function increment_failed_logins($username){
      $query = "SELECT failed_login_attempts FROM users WHERE username = '".$username."';";
      $result = $this->conn->query($query)->fetch_row();
      $attempts = $result[0] + 1;

      $query = "UPDATE users SET total_failed_login_attempts = total_failed_login_attempts + 1 WHERE username = '".$username."';";
      $this->conn->query($query);

      $query = "UPDATE users SET failed_login_attempts = ".$attempts." WHERE username = '".$username."';";
      $this->conn->query($query);
      return $attempts;
  }

  public function reset_failed_logins($username){
      $query = "SELECT failed_login_attempts FROM users WHERE username = '".$username."';";
      $results = $this->conn->query($query)->fetch_row();

      $query = "UPDATE users SET failed_login_attempts = 0 WHERE username = '".$username."';";
      $this->conn->query($query);

      return $results[0];
  }

    public function increment_multiple_attempts($username){
        $query = "SELECT multiple_attempts FROM users WHERE username = '".$username."';";
        $result = $this->conn->query($query)->fetch_row();
        $attempts = $result[0] + 1;

        $query = "UPDATE users SET multiple_attempts = ".$attempts." WHERE username = '".$username."';";
        $this->conn->query($query);
        return $attempts;
    }

    public function reset_multiple_attempts($username){
        $query = "UPDATE users SET multiple_attempts = 0 WHERE username = '".$username."';";
        $this->conn->query($query);
    }

    public function get_multiplier($username){
        $query = "SELECT multiple_attempts FROM users WHERE username = '".$username."';";
        $result = $this->conn->query($query)->fetch_row();
        return $result[0];

    }

    public function get_lockout_time($username){
        $query = "SELECT locked_out_until FROM users WHERE username = '".$username."';";
        $result = $this->conn->query($query)->fetch_row();
        return $result[0];
    }

    public function set_lockout_time($username, $time){
        $query = "UPDATE users SET locked_out_until = ".$time." WHERE username = '".$username."';";
        $this->conn->query($query);
    }

    public function get_lockout_status($username){
        $query = "SELECT is_locked_out FROM users WHERE username = '".$username."';";
        $result = $this->conn->query($query)->fetch_row();
        return $result[0];
    }

    public function set_lockout_status($username, $status){
        $query = "UPDATE users SET is_locked_out = ".$status." WHERE username = '". $username."';";
        $this->conn->query($query);
    }

    public function get_last_login($username){
        $query = "SELECT last_login FROM users WHERE username = '".$username."';";
        $results = $this->conn->query($query)->fetch_row();

        return $results[0];
    }

    public function set_last_login($username, $time){
        $query = "UPDATE users SET last_login = ".$time." WHERE username = '".$username."';";
        $this->conn->query($query);
    }

    public function get_logins($username){
        $query = "SELECT successful_logins FROM users WHERE username = '".$username."';";
        $results = $this->conn->query($query)->fetch_row();

        return $results[0];
    }

    public function increment_logins($username){
        $query = "UPDATE users SET successful_logins = successful_logins + 1 WHERE username = '".$username."';";
        $this->conn->query($query);
    }

    public function get_first_name($username){
        $key = $this->get_primary_key($username);
        $query = "SELECT first_name FROM user_info WHERE user_key =".$key.";";
        $results = $this->conn->query($query)->fetch_row();

        return $results[0];
    }

    public function get_email($username){
        $key = $this->get_primary_key($username);
        $query = "SELECT email FROM user_info WHERE user_key =".$key.";";
        $results = $this->conn->query($query)->fetch_row();

        return $results[0];
    }

    public function set_recovery($email, $code, $time){
        $query = "SELECT user_key FROM user_info WHERE email = '".$email."';";
        $id = $this->conn->query($query)->fetch_row();
	    $query = "DELETE FROM recovery WHERE user_key = ".$id[0].";";
	    $this->conn->query($query);
        $query = "INSERT INTO recovery (user_key, security_hash, expiration) VALUES (".$id[0].",'".$code."',".$time.");";
        $this->conn->query($query);
    }

	public function get_password_expiration($username){
		$id = $this->get_primary_key($username);
		$query = "SELECT expiration FROM recovery WHERE user_key = ".$id.";";
		$results = $this->conn->query($query)->fetch_row();

		return $results[0];
	}

	public function reset_password($username, $password){
		$query = "UPDATE users SET password = '".$password."' WHERE username = '".$username."';";
		$this->conn->query($query);
	}

	public function get_reset_hash($username){
		$id = $this->get_primary_key($username);
		$query = "SELECT security_hash FROM recovery WHERE user_key = ".$id.";";
		$results = $this->conn->query($query)->fetch_row();

		return $results[0];
	}
}
