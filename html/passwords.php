<?php
class password{
    public function get_hash($password){
        $options = array();
        $options['cost'] = 11;

        $hash = password_hash("$password", PASSWORD_DEFAULT, $options);

        return $hash;
  }
}
