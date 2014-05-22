<?php

session_start();
error_reporting(0);
include('connect.php');

if($_POST['cid'] && isset($_SESSION['log'])) {

	$user = $_SESSION['log'];

	$cid 	= filter_var($_POST["cid"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($cid)){
		header('HTTP/1.1 403 Forbidden BITCH !');
		die("mmmm bitch !");
	}

	$q 		= $connect->query("SELECT * FROM answers WHERE id = {$cid} ");
	$ans 	= $q->fetch(PDO::FETCH_OBJ);

	if($ans->user == $user || $user == 'admin') {


		$connect->query("DELETE FROM answers WHERE id = {$cid} ");

		die("done");

	}


}

?>