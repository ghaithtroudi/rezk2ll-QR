<?php
session_start();
error_reporting(0);

if(!isset($_SESSION['log'])) {die("Erreur de connexion au Serveur");}


if($_POST['chavatar']) {
	echo '	<link rel="stylesheet" type="text/css" href="css/styles.css" />
			<link rel="stylesheet" type="text/css" href="css/style.css" />';
	$tfile = $_FILES['avatar']['tmp_name'];
	$nfile = $_FILES['avatar']['name'];
	$info = getimagesize($tfile);
	if(!is_array($info)) {
		die("<div class='error' style='padding-left:50px'>Invalid file</div>");
	}
	else {
	$user = $_SESSION['log'];
	$file = move_uploaded_file($tfile , 'avatar/'.$user.'_'.$nfile);
	
	if($file) {
	$data = 'avatar/'.$user.'_'.$nfile;
	include('connect.php');
	$q = $connect->query("UPDATE users SET avatar = '{$data}' WHERE user ='{$user}'");
	if($q) 
		die("<div class='wait' style='padding-left:50px'>avatar changed successfully <br><br> Refresh to see changes </div>");
	else die("<div class='error' style='padding-left:50px'>error , try again</div>");
	}
}
}
else {
?>	
	<body style="background:transparent">
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<section id="upload_form" class="main">
	<center><form style="width:400px;" class="form-2" method="post" ENCTYPE="multipart/form-data">
	<h2>Changer avatar</h2>
	<br>
	<p class="float">
		<label for="login"><i class="icon-user"></i>selectionner une image de votre ordinateur</label>
		<br>
		<input type="file" name="avatar" style="width:300px"><br><br>
	</p>
	<p class="clearfix">		
		<input type="submit" name="chavatar" value="changer avatar" class="ask">
	</p>
	</form>
	</center>
	</section>
	</body>
<?php
}
