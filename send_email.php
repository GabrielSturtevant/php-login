<?php

class send_email{
    function send_activation_email($email, $code, $username){
        $to      = (string)$email;
        $subject = 'Please Activate Your Account';
        $message = 'In order to activate your account, please go to
         http://ubuntu-webserver/activation.php?code='.urlencode($code).'&username='.urlencode($username);
        $headers = 'From: webmaster@ubuntu-server.com' . "\r\n";
        mail($to, $subject, $message, $headers);
    }
}