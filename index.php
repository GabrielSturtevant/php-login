<!DOCTYPE HTML>
<?php

include_once "connection.php";
include_once "passwords.php";

function verify_credentials($usrname, $passwrd) {
    $password_index = 2;
    $conn = new connection();
    $row = $conn->get_login_info($usrname);
    $is_activated = $conn->is_activated($usrname);
    return password_verify($passwrd, $row[$password_index]) && $is_activated;
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!--    <link rel="icon" href="../../favicon.ico">-->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/signin.css" rel="stylesheet">

    <title>Login Page</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
<!-- Output error message if any -->
<?php echo $error; ?>

<div class="container">
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username"  autofocus>
        <div style="width: auto; margin: 3%"></div>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="Password" name="password" class="form-control" placeholder="Password" >
        <div class="g-recaptcha" data-sitekey="6LeOFg0UAAAAAMTIt9xo7hXHZsHmGDmX-ef99_HO"></div>
        <button class="btn btn-lg btn-primary btn-block" style="margin-top: 3%" type="submit" name="log_in">Sign in</button>
        <div style="width: auto;margin-top: 2%">
            <div style="width: 49%; float: left;">
                <button class="btn btn-sm btn-primary btn-block" type="submit" name="new_account">Create Account</button>
            </div>
            <div style="width: 49%; margin-left: 51%;">
                <button class="btn btn-sm btn-primary btn-block" type="submit" name="Forgot_Credentials">Forgot Credentials</button>
            </div>
        </div>
    </form>
</div> <!-- /container -->
<!---->
<!--<!-- form for login -->-->
<!--<form method="post" action="/index.php">-->
<!--    <label for="username">Username:</script></label><br/>-->
<!--    <input type="text" name="username" id="username"><br/>-->
<!--    <label for="password">Password:</label><br/>-->
<!--    <input type="password" name="password" id="password"><br/><br/>-->
<!--    <div class="g-recaptcha" data-sitekey="6LeOFg0UAAAAAMTIt9xo7hXHZsHmGDmX-ef99_HO"></div>-->
<!--    <br/>-->
<!--    <input type="submit" name="log_in" value="Log In!">-->
<!--    <input type="submit" name="new_account" value="Create Account"><br/>-->
<!--</form>-->
</body>
</html>
