<?php
session_start();
include_once "passwords.php";
$pass = new password();

include_once "connection.php";
$conn = new connection();

$info = $_SERVER['QUERY_STRING'];
$info = convert_uudecode(base64_decode($info));
parse_str($info, $result);

$activation_code = $result['code'];
$username = urldecode($result['username']);

$stored_hash = $conn->get_activation_hash($username);

if(password_verify($activation_code, $stored_hash)){
    $conn->activate_account($username);
    $_SESSION['redirect_message'] = "Activation Successful<br/>";
    header('Location: redirect.php');
}else {
    $_SESSION['redirect_message'] = "Activation was not Successful<br/>";
    header('Location: redirect.php');
}
