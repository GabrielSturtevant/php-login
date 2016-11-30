<?php
session_start();

if(!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']){
	$_SESSION['redirect_message'] = "You shouldn't be doing that...";
	header('Location: redirect.php');
} else {
	$file = '../private/secret.txt';

	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	exit;
}