<?php
session_start();

include_once "passwords.php";
$pass = new password();

include_once "connection.php";
$conn = new connection();

$info = $_SERVER['QUERY_STRING'];
$info = convert_uudecode(base64_decode($info));
parse_str($info, $result);

$reset_code = $result['code'];
$username = urldecode($result['username']);

$stored_hash = $conn->get_reset_hash($username);

$now = date_create();
$now = date_timestamp_get($now);

$expiration_time = $conn->get_password_expiration($username);

if($expiration_time > $now){
	if(password_verify($reset_code, $stored_hash)){
		if (isset($_POST['password']) && isset($_POST['password-dup']) && !$_SESSION['loggedIn']){
			$pass_hash = $pass->get_hash($_POST['password']);

			$conn->reset_password($username, $pass_hash);

			$_SESSION['redirect_message'] = 'Your password has been reset. Please log in.';
			header('Location: redirect.php');
		}
	}else {
		$_SESSION['redirect_message'] = $message . "Your password reset link is invalid.<br/>Request new password reset link.";
		header('Location: redirect.php');
	}
} else {
	$_SESSION['redirect_message'] = $message . "Your password reset link has expired.<br/>Request new password reset link";
	header('Location: redirect.php');
}

?>

<?php include "templates/headder.php"; ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php include "templates/home.php"; ?>
<?php echo $message; ?>
<div class="container" id="container">

	<form class="form-signin" method="post" action="">
		<h3>Enter Your New Password</h3>
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
		<button class="btn btn-lg btn-primary btn-block" style="margin-top: 3%;" type="submit" name="submit">Submit</button>
	</form>
</div>
<?php include "templates/footer.php"; ?>
