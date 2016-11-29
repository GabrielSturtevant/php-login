<?php
session_start();
if($_SESSION['loggedIn']) {
	$_SESSION['loggedIn'] = false;
	unset($_SESSION['username']);
	unset($_SESSION['justIn']);
	unset($_SESSION['lastLogin']);
	unset($_SESSION['loginAttempts']);

	$_SESSION['redirect_message'] = "Have a nice day!";
	header("Location: redirect.php");
} else {
	header('Location: index.php');
}
?>