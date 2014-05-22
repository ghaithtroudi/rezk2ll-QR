<?php
session_start();
error_reporting(0);


if($_POST['likeid'] && isset($_SESSION['log'])) {

	include('connect.php');
	
	$id  = filter_var($_POST["likeid"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	if(!is_numeric($id)){
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	$user 	= $_SESSION['log']; 
	$q 		= $connect->query("SELECT count(*) as `nlks` FROM likes WHERE id_quest = '{$id}' AND user = '{$user}'");
	$qq 	= $q->fetch(PDO::FETCH_OBJ);
	if($qq->nlks == 0) {
	
		$q2 = $connect->query("INSERT INTO likes (id_quest,user) VALUES ('{$id}','{$user}')");
		
		if($q2) {
		
			$nlikes = $connect->query("SELECT COUNT(*) as `numberlikes` FROM likes WHERE id_quest = {$id}");
			$clikes = $nlikes->fetch(PDO::FETCH_OBJ);
			die($clikes->numberlikes);
			}
	
	}
	
}
?>