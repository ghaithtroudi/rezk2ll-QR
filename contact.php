<?php
error_reporting(0);
$my_mail = "k2ll33d2@gmail.com"; // your mail :)

// to make sure that the user hit the button , or a smart pentester
if($_POST['mail']) {
	
	$mail = $_POST['mail'];
	$subj = $_POST['subj'];
	$mess = $_POST['message'];
	
	// normally this is for a non ajax request , maybe a pentester
	if(
	empty($mail) ||
	empty($subj) ||
	empty($mess)
	)
	
	die("you've got an empty fields , double check please");

	
	// same here 
	
	$pattern = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
	if(!preg_match($pattern,$mail)) die("invalid email");
	
	// also
	
	if(strlen($subj) < 5 || strlen($mess) < 10) die("too short !");

    // let's create the message
	
	$message  = "FROM : ".trim($mail)." \r\n";
    $message .=	"Message :".trim($mess)." \r\n";
 
	// and the email headers
	 
	$headers = 'From: '.$mail."\r\n".
	'Reply-To: '.$mail."\r\n" .
	'X-Mailer: PHP/' . phpversion();
	 
	$op = @mail($my_mail, $subj, $message, $headers);
	
	if($op) die("done"); // send a response to ajax :) , he'll love it
	else die("Failed to send message"); // WTF ?? 
}
?>