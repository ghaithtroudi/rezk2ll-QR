<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/comment.css" />
<?php
error_reporting(0);

// fake installation attempt
if(file_exists('config.php')) die("<div class='error' style='padding-left:50px;'>Script is allready installed !</div>");


if($_POST) {

	$host = trim($_POST['host']);
	$user = trim($_POST['user']);
	$pass = $_POST['pass'];
	$base = trim($_POST['dbname']);
	$admn = trim($_POST['admin']);
	$apas = trim($_POST['adpass']);
	
	function errors($no) {
	echo '<div class="error">';
	if($no == 1) {
	?>
	Failed to connect to database server <br><br> please double check your informations !
	<br><br>
	<?php
	}
	elseif($no == 2) {
	?>
	Error while creating tables !
	<?php
	}
	else {
	?>
	Failed to create configuration file<br><br>
	please create a file named &quot; config.php &quot; in main folder and put these codes in it<br><br>
	<textarea cols="37" rows="10" readonly><?php echo $no; ?></textarea>
	<br>
	</div>
	<?php
	die();
	}
	?>
	<br><br>
	<button onclick="history.back()" class="ask">Go back&nbsp;&nbsp;<i class="icon-reply"></i></button>
	</div>
	<?php
	}

	
	try {
		$connect = new PDO("mysql:host={$host};dbname={$base}",$user,$pass);
	}
	catch(PDOException $e) {
	 	die(errors(1));
	}

	$req = array();
	
	$req[0] = ("
	CREATE TABLE `answers` (

		id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		id_quest int(11) NOT NULL,
		user varchar(30) NOT NULL,
		answer TEXT NOT NULL,
		date DATETIME NOT NULL
		);
	");

	$req[1] = ("
	CREATE TABLE `likes` (
		
		id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		id_quest int(11) NOT NULL,
		user varchar(30) NOT NULL
	);
	");

	$req[2] = ("
	CREATE TABLE `quests` (
		
		id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user varchar(30) NOT NULL,
		content TEXT NOT NULL,
		date DATETIME NOT NULL
	);
	");

	$req[3] = ("
	CREATE TABLE `users` (

		id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user varchar(30) NOT NULL,
		pass varchar(30) NOT NULL,
		email varchar(30) NOT NULL,
		type ENUM('0','1') NOT NULL,
		sign_date DATE,
		avatar TEXT
	);
	");

	$req[4] = ("
	CREATE TABLE `views` (
		id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		id_quest int(11) NOT NULL,
		user varchar(30) NOT NULL
	);
	");
	
	foreach($req as $rq) {
		$reqet = $connect->query($rq);
		if(!$reqet) die(errors(2));
	}

	$rdate = date("Y-m-d");
	$admin_info = array($admn,$apas,1,$rdate); // admin information to insert
	$add_admin = $connect->prepare("INSERT INTO users (user , pass , type , sign_date) VALUES (? , ? , ? , ?);"); // Preparing to insert
	$add_admin->execute($admin_info); // inserting
	?>
	<div class="f">
	<div class="comment">
	<div class="heads"><h4>Installation complete</h4></div>
	<p>success , rezk2ll: Q/R has been installed successfully !</p>
	<br><br>
	<center><a href="index.php"><button class="ask">Let's START <i class="icon-plane"></i> </button></a></center>
	</div>
	</div>
	<?php
	$dol = "$";
	$config_file  = "<?php \n\n";
	$config_file .= " /* \n\n";
	$config_file .= " main configuration file \n";
	$config_file .= " */ \n\n";
	$config_file .= $dol."db_host 		= '{$host}' ; // database host \n\n";
	$config_file .= $dol."db_username 	= '{$user}' ; // database user \n\n";
	$config_file .= $dol."db_password 	= '{$pass}' ; // database password \n\n";
	$config_file .= $dol."db_name		= '{$base}' ; // database name \n\n";
	$config_file .= "?>";
	
	file_put_contents('config.php',$config_file) or die(errors($config_file));
}
else
{
?>
	<title>Installation : rezk2ll Q/R</title>
<section id="login_form" class="main">
				<center><form class="form-2" id="f" method="post">
					<h1><span class="log-in">Installation</span></h1>
					<p class="float">
						<label for="login"><i class="icon-hdd"></i>host</label>
						<input style="width:300px" type="text" name="host" placeholder="host">
					</p>
					<p class="float">
						<label for="login"><i class="icon-user"></i>username</label>
						<input style="width:300px" type="text" name="user" placeholder="Username">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>Password</label>
						<input style="width:300px" type="password" name="pass" placeholder="Password">
					</p>
					<p class="float">
						<label for="login"><i class="icon-table"></i>database name</label>
						<input style="width:300px" type="text" name="dbname" placeholder="Username">
					</p>
					<p class="float">
						<label for="login"><i class="icon-user"></i>admin name</label>
						<input style="width:300px" type="text" name="admin" id="username" placeholder="Username">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>admin password</label>
						<input style="width:300px" type="password" name="adpass" placeholder="Password">
					</p>
					<p class="clearfix">
						<br>
						<input type="submit" class="ask" name="install" value="install">
					</p>
				</form></center>
</section>
<?php
}
?>