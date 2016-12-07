<!DOCTYPE HTML>
<?php

include_once "connection.php";
include_once "passwords.php";

function verify_credentials($usrname, $passwrd) {
    $password_index = 2;
    $conn = new connection();
    $row = $conn->get_login_info($usrname);
    return password_verify($passwrd, $row[$password_index]);
}
//Make new account activation verification
//$is_activated = $conn->is_activated($usrname);



// Start the session
session_start();

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
    header('Location: index.php');
}
//create a new account
if(isset($_POST['new_account'])){
  header('Location: create_account.php');
}
if(isset($_POST['forgot_credentials'])){
    header('Location: restore.php'); //account recovery
}

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['log_in'])) {

    $password = (string)$_POST['password'];
    $username = strtolower((string)$_POST['username']);
    $conn = new connection();
    $now = date_create();
    $current_timestamp = date_timestamp_get($now);

    if (verify_credentials($username, $password)) {
        $is_activated = $conn->is_activated($username);
        if ($is_activated){
            $lockout_timestamp = $conn->get_lockout_time($username);
           if(!$conn->get_lockout_status($username)){
               $conn -> set_lockout_time($username, 0);
               $conn->set_lockout_status($username, 0);
               $conn->reset_multiple_attempts($username);

               $_SESSION['username'] = $username;
               $_SESSION['justIn'] = true;
               $_SESSION['loggedIn'] = true;
               header('Location: ./index.php');

           } else if($lockout_timestamp < $current_timestamp){
               $conn -> set_lockout_time($username, 0);
               $conn->set_lockout_status($username, 0);
               $conn->reset_multiple_attempts($username);

               $_SESSION['username'] = $username;
               $_SESSION['justIn'] = true;
               $_SESSION['loggedIn'] = true;
               header('Location: /index.php');

           } else {
               $error = "Your account is currently locked out until: ".date_timestamp_get($lockout_timestamp)."<br/>";
               $_SESSION['loggedIn'] = false;
           }
        } else {
            $error = "Your account has not been activated<br/>";
            $_SESSION['loggedIn'] = false;
        }
    } else {
        $_SESSION['loggedIn'] = false;
        $error = "Incorrect username or password<br/>";

        $results = $conn->check_availability($username);
        if($results->num_rows != 0){
            $now = date_create();
            if((int)$conn->get_lockout_time($username) < (int)date_timestamp_get($now)){
                $conn->set_lockout_status($username, false);
                $conn->set_lockout_time($username, 0);
            }

            if(($conn->increment_failed_logins($username) % 5) == 0) {
                $multiplier = $conn->increment_multiple_attempts($username);
                $locked_out_until = $conn->get_lockout_time($username);

                $now = date_create();
                if($locked_out_until == 0){

                    $timestamp = date_timestamp_get($now);
                    $timestamp += 5 * 60 * $multiplier;
                    $conn->set_lockout_time($username, $timestamp);
                    $conn->set_lockout_status($username, true);

                }else {
                    $timestamp = $locked_out_until + (5*60*$multiplier);
                    $conn->set_lockout_time($username, $locked_out_until);
                }
            }else {
               //shouldn't be used
            }
        }
        $status = $conn->get_lockout_status($username);
        $time_left = $conn->get_lockout_time($username);
        if($status && !($conn->get_lockout_time($username) == 0)){
            $error = $error . "You are locked out until:" . date('Y-m-d h:i:s a', $time_left). "<br/>";
        }
    }
}
?>


<?php include 'templates/headder.php'; ?>
<?php include "templates/home.php";?>
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username"  autofocus>
        <div style="width: auto; margin: 3%"></div>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="Password" name="password" class="form-control" placeholder="Password" >

        <button class="btn btn-lg btn-primary btn-block" style="margin-top: 3%;" type="submit" name="log_in">Sign in</button>
        <div style="width: auto;margin-top: 2%">
            <div style="width: 49%; float: left;">
                <button class="btn btn-sm btn-primary btn-block" type="submit" name="new_account">Create Account</button>
            </div>
            <div style="width: 49%; margin-left: 51%;">
                <button class="btn btn-sm btn-primary btn-block" type="submit" name="forgot_credentials">Forgot Credentials</button>
            </div>
        </div>
        <?php
            if(!empty($error)){
                echo "<div class=\"alert alert-danger\" style=\"margin-bottom: 0%; margin-top: 3%;\">";
                echo "<strong>DANGER: </strong>";
                echo $error;
                echo "</div>";
            }
        ?>
    </form>
<?php include "template/footer.php"; ?>