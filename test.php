<?php
/*$to      = 'gsturtevant87@gmail.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: webmaster@ubuntu-server.com' . "\r\n" .
    'Reply-To: gsturtevant87@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);*/

//include_once "connection.php";
//
//$conn = new connection();

//$result = $conn->get_primary_key("gabe");
//print $result[0];
//$result = $conn->get_primary_key("gabe");
//print "Hello world\n\n";
//$foo = $result;
//print $foo[0];
//print_r($result);

/*include_once "send_email.php";
$foo = new activation_email();
$foo->send_activation_email('gsturtevant87@gmail.com','14941569616514157815649456','juan carlos');*/
include_once "passwords.php";
include_once "connection.php";
include_once "send_email.php";
$emailer = new send_email();
$conn = new connection();
$pass = new password();
//$code = (string)sha1(mt_rand(10000,99999).time());
//$activation_code = $pass->get_hash($code);
//
//$conn->create_user("potato5","1234",$activation_code);
//$emailer->send_activation_email("gsturtevant87@gmail.com",$code,"potato5");
$conn->add_user_info("Dork","dino","doofus","gsturtevant");
//print "Code: ".$code."\nActivation code: ". $activation_code."\n";