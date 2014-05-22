<?php
/*

change password section

khaled

*/
session_start();
error_reporting(0);


if(isset($_SESSION['log'])) {

	// when a ajax make a request
	if($_POST) {
		$password 		= addslashes($_POST['cpassword']);
		$new_password 	= addslashes($_POST['new_password']);
		$new_password2 	= addslashes($_POST['new_password2']);
		
		if(empty($password) || empty($new_password) || empty($new_password2))
		die("please fill in all the fields");

		
		if($new_password !== $new_password2) 
		die("passwords are not the same");
		
		include("connect.php");
		
		$user 			= $_SESSION['log'];
		$q 				= $connect->query("SELECT pass as `pass` FROM users WHERE user ='{$user}'");
		$old_password 	= $q->fetch(PDO::FETCH_OBJ);
		if($old_password->pass == $password) {
		
			$q2 = $connect->query("UPDATE users SET pass = '{$new_password}' WHERE user = '{$user}'");
			
			if($q2) die("done");
		}
		else {
			die("invalid password");
		}
		
	}
	else {
	?>
<div class="f">
<section id="PASS-form" class="main">
				<div id="adr_err">
				</div>
				<form class="form-2" id="chanp">
					<h1><span class="log-in">change password <i class="icon-key"></i></span></h1>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>Password</label>
						<input style="width:300px" type="password" name="cpassword" id="password" placeholder="Password">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>New Password</label>
						<input style="width:300px" type="password" name="new_password" id="password2" placeholder="new Password">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>New Password</label>
						<input style="width:300px" type="password" name="new_password2" id="password3" placeholder="re-Password">
					</p>
					<p class="clearfix">    				
						<center><input type="button" onclick="changepw()" name="changepass" id="changepass" value="Change password"></center>
					</p>
				</form>​​
</section>
</div>
	<?php
	}
}
else die("hhhhhh die BITCH !");

?>