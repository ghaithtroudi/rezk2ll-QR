<?php 
error_reporting(0);
session_start();

if(!file_exists('config.php')) die("<script>location.replace('install.php')</script>");

# user is not logged in , mmm he will get the first menu

if(empty($_SESSION['log'])) {
	$account = "";
	$access ='
	<li id="ulog" width="60px"><a href="#" class="normalMenu" id="loginlink"><i class="icon-lock"></i>&nbsp;&nbsp;Connexion</a></li>
	<li id="ulrg" width="60px"><a href="#" class="normalMenu" id="registerlink"><i class="icon-plus"></i>&nbsp;&nbsp;Inscription</a></li>';
}

# user is logged :)

else {
	include("connect.php");
	$user 	= $_SESSION['log'];
	$q 		= $connect->query("SELECT * FROM users WHERE user = '{$user}'");
	$data 	= $q->fetch(PDO::FETCH_OBJ);
	if(strlen($data->avatar) > 0) {
		$profilepic = '<img class="ppc" alt="'.$data->user.'" src="'.$data->avatar.'">';
	}
	else 
	$profilepic ='<img class="ppc" alt="'.$data->user.'" src="avatar/default.png">';
	$access ='';
	$account = '
		<span id="pp">'.$profilepic.'</span>
		<span id="men"><i class="icon-reorder"></i></span>
		<div id="navmenu">
			<div class="lk"><center><i class="icon-cogs"></i>&nbsp;Paramètres</center></div>
			<hr>
			<div class="lk"><a href="#" id="chpass"><i class="icon-key"></i>&nbsp;Mot de passe</a></div>
			<div class="lk"><a href="#" id="chml"><i class="icon-envelope"></i>&nbsp;Email</a></div>
			<div class="lk"><a href="#" id="chav"><i class="icon-user"></i>&nbsp;Avatar</a></div>
			<div class="lk"><a href="#" id="logoutlink"><i class="icon-signout"></i>&nbsp;Déconnexion</a></div>
		</div>
		';
}


?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GL 2 : Q/R</title>


<!-- yeah a lot of includes i know :'( -->

<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/comment.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript" src="js/redirect.js"></script>
<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript" src="js/register.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<script type="text/javascript" src="js/like.js"></script>
</head>

<body>

	<!-- header -->

<div id="menu-container">
	<table>
	
	<!-- Site logo -->
	
	<td width="20%">
		<img width="170" src="img/log.png">
	</td>
	
	<!-- Site menu -->
	
	<td width="60%">
		<ul id="navigationMenu">
			<li width="60px"><a href="#" class="normalMenu" id="homelink"><i class="icon-home"></i>&nbsp;&nbsp;Accueil</a></li>
			<li width="60px"><a href="#" class="normalMenu" id="faqlink"><i class="icon-info"></i>&nbsp;&nbsp;FAQ</a></li>
			<?php echo $access; ?>
			<li width="60px"><a href="#" class="normalMenu" id="contactlink"><i class="icon-envelope"></i>&nbsp;&nbsp;Contact</a></li>
		</ul>
	<?php echo $account; ?>
	</td>
	
	<!-- Search field -->
	
	<td width="20%">
		<div align="right">
		<div id="search">
		  <input name="searchdata" onblur="resetsearch()" id="searchdata" type="text" onkeyup="searching(this.value)" placeholder="Chercher ...">
		</div>
		</div>
	</td>
	
	</table>
</div>
 <!-- let's go down -->
	<center>
	
		<div id="main">
		
		<!-- all the magic happens here -->
		
		</div>
		
		<div id="add_err">
		
		<!-- i have some errors and msgs to show here -->
		
		</div>
		
		<div class="stats">
		
		<!-- site statistics here -->
		
		</div>
	</center>
</body>
</html>