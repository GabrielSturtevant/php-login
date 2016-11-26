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
// Start the session
session_start();

//create a new account
if(isset($_POST['new_account'])){
  header('Location: create_account.php');
}

// Error message
if (isset($_POST['username']) && isset($_POST['password'])) {

    $password = (string)$_POST['password'];
    $username = (string)$_POST['username'];

    if (verify_credentials($username, $password) && isset($_POST['g-recaptcha-response'])
            && $_POST['g-recaptcha-response']) {
        $secret = '6LeZtQwUAAAAAJC3-h8q7O62evV6551YCQ0rtHmA';
        $response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $validation = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=".
            "$response&remoteip=$remoteip");
//        $error = var_dump($validation);

        echo $error;
        $_SESSION['loggedIn'] = true;
        header('Location: ./success.php');
    } else {
        $_SESSION['loggedIn'] = false;
        $error = "Invalid username and password!";
    }
}
?>

<html>
<head>
    <title>Login Page</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
<!-- Output error message if any -->
<?php echo $error; ?>

<!-- form for login -->
<form method="post" action="/index.php">
    <label for="username">Username:</label><br/>
    <input type="text" name="username" id="username" required><br/>
    <label for="password">Password:</label><br/>
    <input type="password" name="password" id="password" required><br/><br/>
    <div class="g-recaptcha" data-sitekey="6LeZtQwUAAAAAOhiXiLGhci6qZN772_xhOua82Oz"></div>
    <br/>
    <input type="submit" name="log_in" value="Log In!">
    <input type="submit" name="new_account" value="Create Account"><br/>
</form>
</body>
</html>
