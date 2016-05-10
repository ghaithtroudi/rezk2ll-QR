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
	Erreur lors de la connection avec le serveur <br><br>  Veuillez revérifier vos informations !
	<br><br>
	<?php
	}
	elseif($no == 2) {
	?>
	Erreur lors de la création des tables !
	<?php
	}
	else {
	?>
	Echec de création du fichier de configuration<br><br>
	Veuillez créer un fichier nommeé &quot; config.php &quot; dans le répertoire principal et y mettre ce code<br><br>
	<textarea cols="37" rows="10" readonly><?php echo $no; ?></textarea>
	<br>
	</div>
	<?php
	die();
	}
	?>
	<br><br>
	<button onclick="history.back()" class="ask">Revenir en arrière&nbsp;&nbsp;&nbsp;<i class="icon-reply"></i></button>
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
	<div class="heads"><h4>Installation Terminée</h4></div>
	<p>Succès , GL 2: Q/R a été installé avec Succès !</p>
	<br><br>
	<center><a href="index.php"><button class="ask">On Commence ! <i class="icon-plane"></i> </button></a></center>
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
	<title>Installation : GL 2 Q/R</title>
<section id="login_form" class="main">
				<center><form class="form-2" id="f" method="post">
					<h1><span class="log-in">Installation</span></h1>
					<p class="float">
						<label for="login"><i class="icon-hdd"></i>host</label>
						<input style="width:300px" type="text" name="host" placeholder="localhost">
					</p>
					<p class="float">
						<label for="login"><i class="icon-user"></i>Nom d'utilisateur</label>
						<input style="width:300px" type="text" name="user" placeholder="Nom d'Utilisateur">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>Mot de Passe</label>
						<input style="width:300px" type="password" name="pass" placeholder="Mot de Passe">
					</p>
					<p class="float">
						<label for="login"><i class="icon-table"></i>Nom de la Base de Données</label>
						<input style="width:300px" type="text" name="dbname" placeholder="MaBase">
					</p>
					<p class="float">
						<label for="login"><i class="icon-user"></i>Nom de l'Admin</label>
						<input style="width:300px" type="text" name="admin" id="username" placeholder="root">
					</p>
					<p class="float">
						<label for="password"><i class="icon-lock"></i>Mot de Passe Admin</label>
						<input style="width:300px" type="password" name="adpass" placeholder="Mot de Passe">
					</p>
					<p class="clearfix">
						<br>
						<input type="submit" class="ask" name="install" value="Installer">
					</p>
				</form></center>
</section>
<?php
}
?>