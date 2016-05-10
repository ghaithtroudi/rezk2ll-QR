<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/loader.js"></script>
<script type="text/javascript" src="js/ask.js"></script>
</head>
<body>
		<div id="quest-container">
		<?php 
		session_start();
		if(empty($_SESSION['log'])) {
		?>
		<button class="ask" id="loginlink2">Vous devez vous connecter pour demander une question &nbsp;&nbsp;<i class="icon-lock"></i></button>
		<br><br>
		<br>
		<?php
		}
		
		else {
		?>
		<table>
		<tr>
		<td><textarea id="myquest" maxlength="200" style="resize: none" cols="55" rows="2" onfocus="resetask()" placeholder="Une question dans l'esprit ?" autofocus></textarea></td>
		<tr>
		<td><button class="ask" onclick="ask()" style="width:550px;">Demander &nbsp;&nbsp;&nbsp;<i class="icon-volume-up"></i></button></td>
		</tr>
		</table>
		<br><br>
		<?php
		}
		?>
		<!-- questions will be loaded here -->
		<div id="results">
		<div class="mainloading">
		Chargement .... <img src="img/ajax-loader.gif">
		<br><br>
		</div>
		</div>
		<!-- loading image -->
		<div class="animation_image" style="display:none" align="center">
		<img src="img/ajax-loader.gif">
		</div>

		<!-- load more button -->
		<button id="more">Afficher les anciennes questions &nbsp;&nbsp;<i class="icon-chevron-down"></i></button>
		</div>
</body>
</html>
