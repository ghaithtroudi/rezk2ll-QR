<?php
/*

connection file

khaled (rezk2ll)

*/

	include('config.php'); // configuration file
	
	$connect = new PDO("mysql:host={$db_host};dbname={$db_name}","{$db_username}","{$db_password}"); // PDO 

?>