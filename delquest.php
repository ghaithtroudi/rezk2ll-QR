<?php
session_start();
error_reporting(0);
include('connect.php');

if($_POST['qid'] && isset($_SESSION['log'])) {

	$user 	= $_SESSION['log'];
	$qid 	= filter_var($_POST["qid"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($qid)){
		header('HTTP/1.1 403 Forbidden BITCH !');
		die("mmmm bitch !");
	}

	$q 		= $connect->query("SELECT * FROM quests WHERE id={$qid}");

	$get	= $q->fetch(PDO::FETCH_OBJ);

	if($user == $get->user || $user == "admin") {

		if($connect->query("DELETE FROM quests WHERE id = {$qid};"))

			if($connect->query("DELETE FROM answers WHERE id_quest = {$qid};"))

				$connect->query("DELETE FROM likes WHERE id_quest = {$qid}");

			die("done");
		

	}

}

?>