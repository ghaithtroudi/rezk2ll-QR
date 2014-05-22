<?php
/*

change email section

khaled

*/

session_start();
error_reporting(0);

if(isset($_SESSION['log'])) {

	if($_POST) {

		// some SQL injection protection
		
		$old_mail 	= addslashes($_POST['email']);
		$pass 		= addslashes($_POST['pass']);
		$new_mail 	= addslashes($_POST['newemail']);
		
		// check if there are really data
		if(empty($old_mail) || empty($pass) || empty($new_mail)) die("fill in all fields");
		
		
		// check if that's really an email
		$pattern = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,}\$/i";
		
		if(!preg_match($pattern,$old_mail) || !preg_match($pattern,$new_mail)) die("invalid email");
		
		// check for stupidity
		
		if($old_mail == $new_mail) die("choose another email");
		
		// let's connect to database
		
		include('connect.php');
		$user 	= $_SESSION['log'];
		$check 	= $connect->query("SELECT * FROM users WHERE user = '{$user}'");
		$data 	= $check->fetch(PDO::FETCH_OBJ);
		
		// let's check the password
		
		if($data->pass != $pass) die("invalid password");
		
		// let's check the email
		
		if($data->email != $old_mail) die("invalid email");
		
		// let's check the new email availability
		
		$check2 = $connect->query("SELECT count(*) as `used` FROM users WHERE email = '{$new_mail}';");
		$cntchk = $check2->fetch(PDO::FETCH_OBJ);
		if($cntchk->used != 0) die("email allready user , choose another one");
		
		// it seems everything is ok , let's update
		
		$q = $connect->query("UPDATE users SET email = '{$new_mail}' WHERE user = '{$user}'");
		
		if($q) {
			// let's send something useful to ajax
			die("done");
		}
		else {
			// mmm , WTF ??
			die("something is wrong , try again ");
		}


	}
	// nothing requested so far !
	else {
	?>
<div class="f">
<section id="mail-form" class="main">
				<div id="adr_err">
				</div>
				<form class="form-2" id="chanp">
					<h1><span class="log-in">change email adress <i class="icon-envelope"></i></span></h1>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>Password</label>
						<input style="width:300px" type="password" name="pass" id="password" placeholder="Password">
					</p>
					<p class="float">
						<label for="email"><i class="icon-envelope"></i>current email</label>
						<input style="width:300px" type="text" name="email" id="cemail" placeholder="current email">
					</p>
					<p class="float">
						<label for="email"><i class="icon-envelope"></i>New email</label>
						<input style="width:300px" type="text" name="newemail" id="nemail" placeholder="new email">
					</p>
					<p class="clearfix">    				
						<center><input type="button" onclick="changemail()" id="changeml" value="Change email"></center>
					</p>
				</form>​​
</section>
</div>
	<?php
	}
}
else die("hhhhhhhhh die BITCH !");
?>