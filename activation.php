<?php

include_once "passwords.php";
$pass = new password();

include_once "connection.php";
$conn = new connection();

$activation_code = urldecode($_GET["code"]);
$username = urldecode($_GET["username"]);

$stored_hash = $conn->get_activation_hash($username);

if(password_verify($activation_code, $stored_hash)){
    $conn->activate_account($username);
}