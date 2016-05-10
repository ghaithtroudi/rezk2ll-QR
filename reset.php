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

		die("Le nom d'utilisateur doit être composé d'un minimum de 6 caractères");
	}

	$pattern = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

	if(!preg_match($pattern,$email)) {

		die("Email invalide");
	}

	require 'connect.php';
	
	$q = $connect->query("SELECT * FROM users WHERE user ='$username'");
	$r = $q->fetch(PDO::FETCH_OBJ) or die("Ce nom d'utilisateur n'existe pas");

	if($email != $r->email) die("Email invalide");
	else {

		$new_pass = random_pass();

		$q2 = $connect->query("UPDATE users SET pass = '$new_pass' WHERE user ='$username'");
		if(!$q2) die("Erreur inconnue , veuillez réessayer");
		else {

			$message 	 = "Bonjour, ".$r->user." ! \r\n \n\n";
			$message    .= "Comme nous le savions , vous avez demandé un nouveau mot de passe pour votre compte dans notre site GL 2 : Q/R";
			$message 	.= "Voici votre nouveau mot de passe :".$new_pass." \r\n";
			$message	.= "\n\n\n ADMIN";

			$subject 	= "Restaurer Mot de Passe";

			mail($r->email,$subject,$message) or die("Echec d'envoi de l'email , probablement une erreur dans le serveur de mails , veuillez réessayer !");
			die("Email envoyé");
		}
	}



}


?>