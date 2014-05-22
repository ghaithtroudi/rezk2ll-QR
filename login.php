<?php
/*

login section 

khaled ( rezk2ll )

*/

error_reporting(0);


# yeah without request i will not work :p

if(isset($_POST['username'])) {


	# avoid sql injection
	
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	
	# yeah , same thing
	
	 if(empty($username) || empty($password)) die();
	
	# let's go to database
	
	include('connect.php');
	
	# let's select the user password first ;)
	
	$rq 	= $connect->query("select pass from users where user = '{$username}'");
	$result = $rq->fetch(PDO::FETCH_OBJ);
	
	# now let's see
	
	if($password == $result->pass) {
	
	session_start(); 
	
	$_SESSION['log'] = $username; // let's make a session
	
	echo 'true'; // and return to ajax
	
	}

}
?>