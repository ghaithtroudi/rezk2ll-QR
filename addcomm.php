<?php 
session_start();
error_reporting(0);


if($_POST['addcomm'] && $_POST['idq'] && isset($_SESSION['log'])) {
	// sql injection protection :3
	
	$qid  = filter_var($_POST["idq"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	if(!is_numeric($qid)){
		header('HTTP/1.1 403 Forbidden BITCH !');
		die("mmmm bitch !");
	}
	$comm = trim($_POST['addcomm']);
	$comm = strip_tags($comm);
	$comm = addslashes($comm);

	if((empty($comm)) || (strlen($comm) < 5)) { die(); }
	
	// sorry for consorship :(
	
	include('connect.php');
	
	$forbiddens = array("hacked","defaced","pwned");
	
	foreach($forbiddens as $forbidden) {
		$comm = preg_replace('/\b'.$forbidden.'\b/', '[..]', $comm);
	}
	
	$date = date('Y-m-d H:i:s');
	
	$user = $_SESSION['log'];

	$ansd = array($qid,$user,$comm,$date);

	$q = $connect->prepare("INSERT INTO answers (id_quest , user , answer , date) values (? , ? , ? , ?);");
	$op = $q->execute($ansd);
	if($op) {
		die("true");
	}
}
?>