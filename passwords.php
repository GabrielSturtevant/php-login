<?php
class password{

    public function get_salt(){
        $foo = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
        return $foo;
  }

    public function get_hash($salt, $password){
        $options = array();
        $options['cost'] = 11;

        $hash = password_hash("$password", PASSWORD_DEFAULT, $options);

        return $hash;
  }
}
