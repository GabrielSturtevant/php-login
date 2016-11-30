<?php
class CheckString
{
	// property declaration
	var $username;
	var $password;
	var $email;


	// method declaration
	function setUsername($user){
		$this->username= $user;
	}
	function setPasswood($pass){
		$this->password= $pass;
	}
	function setEmail($newEmail){
		$this->email= $newEmail;
	}

	//Removes unwanted chars and returns a string
	function checkUserName($var){
		$temp = str_replace("'","", $var);
		$temp = str_replace('"', '', $temp);
		$temp = str_replace('<', ' ob ', $temp);
		$temp = str_replace('</', ' cb ', $temp);
		return $temp;
	}
	//Removes unwanted chars and returns a string
	function checkPassword($var){
		$temp = str_replace("'","", $var);
		$temp = str_replace('"', '', $temp);
		$temp = str_replace('<', ' ob ', $temp);
		$temp = str_replace('</', ' cb ', $temp);
		return $temp;
	}
	//Validates email return boolean
	function checkEmail($var){
		if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
			return  false;
		}
		return  true;
	}
}
