<?php
ob_start();
session_start();

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
    header('Location: index.php');
}

include_once 'passwords.php';
include_once 'connection.php';
include_once 'send_email.php';
include_once 'checkstring.php';

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['first_name'])
    && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['g-recaptcha-response'])
&& $_POST['g-recaptcha-response']){
    $pass = new password();
    $conn = new connection();
    $email_sender = new send_email();
    $checker = new CheckString();

    $username = strtolower((string)$_POST['username']);
    $password = (string)$_POST['password'];
    $email = (string)$_POST['email'];
    $first_name = (string)$_POST['first_name'];
    $last_name = (string)$_POST['last_name'];

    if(!$checker->checkPassword($password)){
        $_SESSION['redirect_message'] = "Please choose a different password";
        header('Location: redirect.php');
    }
    if(!$checker->checkEmail($email)){
        $_SESSION['redirect_message'] = "Please choose a valid email";
        header('Location: redirect.php');
    }
    if(!$checker->checkUserName($username)){
        $_SESSION['redirect_message'] = "Please choose a valid username";
        header('Location: redirect.php');
    }

    $results = $conn->check_availability($username);

    if($results->num_rows == 0){

        $code = (string)sha1(mt_rand(10000,99999).time());
        $activation_code = $pass->get_hash($code);
        $hash = $pass->get_hash($password);
        $conn->create_user($username, $hash, $activation_code);
        $conn->add_user_info($username,$first_name,$last_name,$email);
        $email_sender->send_activation_email($email,$code,$username);
        $_SESSION['redirect_message'] = "Please check your email for an activation link";
        header('Location: redirect.php');
    }else {
        $error = "Username is not available<br>";
    }
}
?>

<?php include 'templates/headder.php'; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php include "templates/home.php"?>
<div class="container">
    <form class="form-signin" method="post" action="">
        <h2 class="form-signin-heading">Create an account</h2>

        <?php
        if(!empty($error)){
            echo "<div class=\"alert alert-danger\" style=\"margin-bottom: 0%; margin-top: 3%;\">";
            echo "<strong>DANGER: </strong>";
            echo $error;
            echo "</div>";
        }
        ?>

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
        <div class="g-recaptcha" data-sitekey="6LeOFg0UAAAAAMTIt9xo7hXHZsHmGDmX-ef99_HO"></div>
        <button class="btn btn-lg btn-primary btn-block" id="create" disabled type="submit">Submit</button>
    </form>
</div> <!-- /container -->
<?php include "template/footer.php"; ?>
</body>
</html>
