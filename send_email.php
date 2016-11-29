<?php

class send_email{
    public function send_activation_email($email, $code, $username){
        $to_send = "code=".$code."&username=".$username;
        $to_send = base64_encode(convert_uuencode($to_send));
        $to      = (string)$email;
        $subject = 'Please Activate Your Account';
        $message = 'In order to activate your account, please go to
         http://ubuntu-webserver/activation.php?'.$to_send;
        $headers = 'From: webmaster@ubuntu-server.com' . "\r\n";
        mail($to, $subject, $message, $headers);
    }

    public function send_password_reset($email, $code, $username){
        $to_send = "code=".$code."&username=".$username;
        $to_send = base64_encode(convert_uuencode($to_send));
        $to      = (string)$email;
        $subject = 'Password Reset';
        $message = 'In order to reset your password, please click the link below<br/> 
         http://ubuntu-webserver/reset.php?'.$to_send;
        $headers = 'From: webmaster@ubuntu-server.com' . "\r\n";
        mail($to, $subject, $message, $headers);
    }
}