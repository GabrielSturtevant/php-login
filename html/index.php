<?php
session_start();
ob_start();
include_once "connection.php";
if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false){
    header('Location: login.php');
} else {
    $conn = new connection();
    $username = $_SESSION['username'];
    if($_SESSION['justIn']){
        $_SESSION['justIn'] = false;
        $_SESSION['loginAttempts'] = $conn->reset_failed_logins($username);
        $_SESSION['lastLogin'] = $conn->get_last_login($username);
        $now = date_create();
        $now = date_timestamp_get($now);
        $conn->set_last_login($username, $now);
        $conn->increment_logins($username);
    }
    $first_name = $conn->get_first_name($username);
    if($_SESSION['lastLogin'] == 0) {
        $message = "This is your first time logging into the website.";
    } else {
        $last_login = date('m-d-Y h:i:s a', $_SESSION['lastLogin']);
        $login_attempts = $_SESSION['loginAttempts'];
        $total_logins = $conn->get_logins($username);
        $message = "Since your last login: ".$last_login.",<br/>there have been ".$login_attempts.
            " failed attempts to access<br/> your account. You have logged on ".$total_logins." times.";
    }
}
?>

<?php include "templates/headder.php"; ?>
<title>Welcome!</title>

<?php include "templates/home.php"; ?>
<div class="jumbotron">
    <h2><?php echo "Hello ".ucfirst($first_name)."!<br/>"?></h2>
    <h3><?php echo $message; ?></h3>
</div>
<?php include "templates/footer.php"; ?>
