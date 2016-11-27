<?php
ob_start();
session_start();
include_once 'passwords.php';
include_once 'connection.php';
include_once 'send_email.php';

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['first_name'])
    && isset($_POST['last_name']) && isset($_POST['email'])){
    $pass = new password();
    $conn = new connection();
    $email_sender = new send_email();

    $username = $_POST['username'];
    $password = (string)$_POST['password'];
    $email = (string)$_POST['email'];
    $first_name = (string)$_POST['first_name'];
    $last_name = (string)$_POST['last_name'];

    echo "$last_name<br/>";

    $results = $conn->check_availability($username);

    if($results->num_rows == 0){

//        $time = (string)date('l jS \of F Y h:i:s A');
        $code = (string)sha1(mt_rand(10000,99999).time());
        $activation_code = $pass->get_hash($code);
        $hash = $pass->get_hash($password);
        $conn->create_user($username, $hash, $activation_code);
        $conn->add_user_info($username,$first_name,$last_name,$email);
        $email_sender->send_activation_email($email,$code,$username);
    }else {
        echo "Username is not available";
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/signin.css" rel="stylesheet">

    <title>Create an Account</title>
    <script src="js/functions.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>

<div class="container">
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">Create an account</h2>

        <label for="first_name" class="sr-only">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required autofocus>

        <label for="last_name" class="sr-only">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" required>

        <label for="username" class="sr-only">Username:</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>

        <label for="email" class="sr-only">Email</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Email" required>

        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" id="password" class="form-control" style="margin-bottom: 0%" placeholder="Password" onkeyup="password_criteria();" required>
        <div class="alert alert-info" id="password-warnings" style="margin-bottom: 0%">
            <strong>Requirements:</strong><br>
            <ol>
                <li>Must have at least 8 characters</li>
                <li>Must have at least one special character</li>
                <li>Must have at least one uppercase letter</li>
                <li>Must have at least one number</li>
            </ol>
        </div>
        <label for="password" class="sr-only">Re-enter Password</label>
        <input type="password" name="password-dup" id="password-dup" class="form-control" style="margin-bottom: 0%" placeholder="Re-enter password" onkeyup="password_match();" required>
        <div class="" id="passwords-match" style="margin-bottom: 0%"></div>

        <button class="btn btn-lg btn-primary btn-block" id="create" disabled type="submit">Sign in</button>
    </form>
</div> <!-- /container -->
</body>
</html>
