<?php
session_start();
if(isset($_POST['username'])){

    include_once "connection.php";
    $conn = new connection();

    $username = strtolower((string)$_POST['username']);

    if($conn->check_availability($username)->num_rows != 0) {

        include_once "send_email.php";
        $emailer = new send_email();

        include_once "passwords.php";
        $pass = new password();

        $code = (string)sha1(mt_rand(10000, 99999) . time());
        $email = $conn->get_email($_POST['username']);
        $email = (string)$email;

        $code_hash = $pass->get_hash($code);
        $code_hash = (string)$code_hash;

        $now = date_create();
        $expiration = date_timestamp_get($now);
        $expiration += 6 * 60 * 60;


        $conn->set_recovery($email, $code_hash, $expiration);
        $emailer->send_password_reset($email, $code, $_POST['username']);
    }
    $_SESSION['redirect_message'] = "Please check your email for a reset link";
    header('Location: redirect.php');
}
?>

<?php include "templates/headder.php"; ?>
<?php include "templates/home.php"; ?>
<div class="container" id="container">
    <form class="form-signin" method="post" action="">
        <h3>Enter Your Username</h3>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <button class="btn btn-lg btn-primary btn-block" style="margin-top: 3%;" type="submit" name="submit">Submit</button>
    </form>
</div>
<?php include "templates/footer.php"; ?>
