<?php
ob_start();
session_start();
require_once('passwords.php');
require_once('connection.php');

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name'])){
    $pass = new password();
    $conn = new connection();

    $username = $_POST['username'];
    $password = (string)$_POST['password'];

    $results = $conn->check_availability($username);

    if($results->num_rows == 0){

        $salt = $pass-> get_salt();
        $hash = $pass->get_hash($salt, $password);
        $conn->insert($username, $hash);

        //TODO
        //Add user info to database
        //get primary key, use primary key to associate personal information with username

//        $_SESSION['loggedIn'] = true;
//        header('Location: ./success.php');
    }else {
        echo "Not available";
    }
}
?>

<html>
<head>
    <title>Create an Account</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>

<!-- form for login -->
<form method="post" action="">
    <label for="first_name">First Name</label><br/>
    <input type="text" name="first_name" id="first_name" required><br/>
    <label for="first_name">Last Name</label><br/>
    <input type="text" name="last_name" id="last_name" required><br/>
    <label for="username">Username:</label><br/>
    <input type="text" name="username" id="username" required><br/>
    <label for="password">Password:</label><br/>
    <input type="password" name="password" id="password" required><br/>
    <br/>
    <input type="submit" value="Create Account">
</form>
</body>
</html>