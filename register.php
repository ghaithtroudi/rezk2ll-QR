<?php

/*

registration section

khaled ( rezk2ll )

*/


# no debugging :p
session_start();
error_reporting(0);

	# only when there are some requests i will work

if(isset($_POST['username'])) {

	# am trying to avoid sql injection :(
	
	$username  = addslashes($_POST['username']); 
	$password  = addslashes($_POST['password']);
	$rpassword = addslashes($_POST['rpassword']);
	$email     = addslashes($_POST['email']);
	
	# seconday check for user inputs , who knows , maybe a non ajax request ;)
	
	if(
	empty($username)  || 
	empty($password)  || 
	empty($rpassword) ||
	empty($email) 	  
	) die();
	
	# seconday check for passwords , who knows , maybe a non ajax request ;)
	
	if($password !== $rpassword) die();
	
	# seconday check for email  , who knows , maybe a non ajax request ;)
	
	if(!preg_match("/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,}\$/i", $email)) {
	die("invalid e-mail");
	}
	
	# # seconday check for email  , who knows , maybe a pentester ;)
	if(!preg_match('/^[a-zA-Z0-9]+$/',$username) || strlen($username) < 6) {
	die("invalid username");
	}
	
	# am trying to avoid some kiddies stupidities
	
	$fails = array("admin","hacked","pwned","deface","fuck","sex","khaled"); // some words i don't to see
	
	foreach($fails as $fail) {
	if(preg_match("/{$fail}/",$username)) die("Nom d'utilisateur invalide");
	}
	
	# i think everything is Ok , let's go to database
	
	include("connect.php");
	
	# first i will check if the username or email is allreay used !
	$rq 	= $connect->query("select * from users");
	
	while($result = $rq->fetch(PDO::FETCH_OBJ)) {
		if($result->user  == $username) die("Ce nom d'utilisateur existe déjà");
		if($result->email == $email )	die("Cet Email existe déjà");
	}
	
	# ok , it's ok , let's put it on database
	$date = date("Y-m-d");
	$queryData = array($username,$password,$email,$date,0);

	$query = $connect->prepare("
	INSERT INTO users (`user`,`pass`,`email`,`sign_date`,`type`) VALUES (?,?,?,?,?);
	");
	
	# ok , user registred now 
	if($query->execute($queryData)) {
	
	$_SESSION['log'] = $username; // start session
	die("welcome"); // ajax will love to see this
	}
	
	# shit ! , an error in the server 
	
	else die("Erreur inconnue , veuillez réessayer");
		
}
?>