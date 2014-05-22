<?php

/*


password reset

*/


error_reporting(0);
session_start();

function random_pass() {

	$strings = "ABCDEFGHIJ23456KLM#@=!OPQRSTUVWXYZ01789azertyuiopmlkjhgfdsqwxcvbn";

	$pass 	 = $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	$pass 	.= $strings[rand(0,64)];
	

	return $pass;
}


if($_POST['username'] && empty($_SESSION['log'])) {

	$username 	= addslashes($_POST['username']);
	$email 		= addslashes($_POST['email']);

	if(strlen($username) < 6) {

		die("username must be at least 6 characters");
	}

	$pattern = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

	if(!preg_match($pattern,$email)) {

		die("Invalid Email adress");
	}

	require 'connect.php';
	
	$q = $connect->query("SELECT * FROM users WHERE user ='$username'");
	$r = $q->fetch(PDO::FETCH_OBJ) or die("User does not exist");

	if($email != $r->email) die("Invalid email adress");
	else {

		$new_pass = random_pass();

		$q2 = $connect->query("UPDATE users SET pass = '$new_pass' WHERE user ='$username'");
		if(!$q2) die("unknow error , please try again");
		else {

			$message 	 = "Hello ".$r->user." ! \r\n \n\n";
			$message    .= "as we know , you have requested a new password to your account in our community (ReZK2LL:Q/R) \r\n\n\n";
			$message 	.= "So here is your new password :".$new_pass." \r\n";
			$message	.= "\n\n\n ReZK2LL:Q/R administration";

			$subject 	= "Password Reset";

			mail($r->email,$subject,$message) or die("faield to send e-mail , there is an error in mailing server , try again !");
			die("done");
		}
	}



}


?>