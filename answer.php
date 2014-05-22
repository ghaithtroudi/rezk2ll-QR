<?php
session_start();
include("connect.php"); //include connection file
error_reporting(0);


if($_POST['questid']) {
	//sanitize post value
	$questid = filter_var($_POST["questid"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($questid)){
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	
	$q = $connect->query("SELECT * FROM quests WHERE id={$questid}");
	$obj = $q->fetch(PDO::FETCH_OBJ);
	
	if($obj) {
	
			//let's get the answers number of this question
			$get_ans = $connect->query("SELECT count(*) as `nnser` FROM answers WHERE id_quest = '".$obj->id."'");
			$cnt_ans = $get_ans->fetch(PDO::FETCH_OBJ);
			$num_ans = $cnt_ans->nnser;
			
			
			// and the likes number of this question
			$get_likes = $connect->query("SELECT count(*) as `nlks` FROM likes WHERE id_quest = '".$obj->id."'");
			$cnt_likes = $get_likes->fetch(PDO::FETCH_OBJ);
			$num_likes = $cnt_likes->nlks;
			
			// and the views number also
			$get_views = $connect->query("SELECT count(*) as `nvws` FROM views WHERE id_quest = '".$obj->id."'");
			$cnt_views = $get_views->fetch(PDO::FETCH_OBJ);
			$num_views = $cnt_views->nvws;
			
			// and let's check for like ability
			$user = $_SESSION['log'];
			
			// the user is not logged in
			if(empty($user)) {
			$like = '';
			}
			
			// the user os logged in
			else {
			
				// let's see if he had seen this before
				$q2 = $connect->query("SELECT count(*) as `nvs` FROM views WHERE id_quest = '{$obj->id}' AND user = '{$user}'");
				$qv = $q2->fetch(PDO::FETCH_OBJ);
				// no he didn't 
				if($qv->nvs == 0) {
					$connect->query("INSERT INTO views (id_quest,user) VALUES ('".$obj->id."','".$user."')");
				}
				
				// lets see if he liked it before
				$get_user_like = $connect->query("SELECT count(*) as `unlk` FROM likes WHERE id_quest = '".$obj->id."' AND user = '".$user."'");
				
				// he didn't like it before
				$c1 = $get_user_like->fetch(PDO::FETCH_OBJ);
				if($c1->unlk == 0) {
				
				// you can like the question mate :)
				
				$like = '<a onclick="likeit('.$obj->id.')" id="like_'.$obj->id.'" rel="'.$obj->id.'">like |</a>';
				}
				
				// he liked it before
				
				else $like='';
			}
			
			$whois = $obj->user;
			$q2 		= $connect->query("SELECT * FROM users WHERE user = '{$whois}'");
			$data 		= $q2->fetch(PDO::FETCH_OBJ);
			if(strlen($data->avatar) > 0) {
				$profilepic = $data->avatar;
			}
			else 
			$profilepic = "avatar/default.png";

			if($obj->user == $user || $user == "admin") {
				$remove = 	'<div align="right" class="x">
						 	<a onclick="rmq('.$obj->id.')"><i class="icon-remove"></i></a>
						 	</div>';
			}
			else $remove = '';

			if(strlen($obj->content) <= 50) $class = "small";
			elseif(strlen($obj->content) > 50 &&  strlen($obj->content) <= 210) $class ="large";
			elseif(strlen($obj->content) > 210) $class ="large2";
			
			echo '
			<br><br>
			<div id="answers">
			<div class="comment"  id='.$obj->id.'>
			<div class="heads">
			'.$remove.'
			<div class="avatar" onclick="showprofile('.$data->id.')"><img src="'.$profilepic.'"></div>
			<div class="name" onclick="showprofile('.$data->id.')">'.$obj->user.'</div> <br><p class="date">'.$obj->date.'</p>
			</div>
			<textarea class="'.$class.'" readonly>'.$obj->content.'</textarea>
			'.$like.'
			<span> '.$num_ans.' <i class="icon-comments-alt"></i> </span> |
			<span><span id="nlikes_'.$obj->id.'">'.$num_likes.'</span> <i class="icon-thumbs-up"></i> </span>|
			<span> '.$num_views.' <i class="icon-eye-open"></i> </span>
			</div>
			<br>
			';
			
			if(isset($_SESSION['log'])) {
			
				echo '
				<table class="postanswer">
				<tr>
				<td>
				<textarea id="myanswer" maxlength="200" style="resize: none" cols="60" rows="2" onfocus="resetask()" placeholder="write an answer ...." autofocus></textarea>
				</td>
				<tr>
				<td><button class="ask" onclick="comm('.$obj->id.')">Answer&nbsp;&nbsp;&nbsp;<i class="icon-volume-up"></i></button></td>
				</tr>
				</table>
				';

			}
			else
			echo '<button class="ask" onclick="logg()">you must login to give your answer&nbsp;&nbsp;<i class="icon-lock"></i></button><br><br><br><br>';
			
			
			echo '<div id="mycomments">';
			$comments  = $connect->query("SELECT * FROM answers WHERE id_quest = {$obj->id} ORDER BY date DESC");
			
			while($get_comms = $comments->fetch(PDO::FETCH_OBJ)){
			$whois = $get_comms->user;
			$q2		= $connect->query("SELECT * FROM users WHERE user = '{$whois}'");
			$data 	= $q2->fetch(PDO::FETCH_OBJ);
			if(strlen($data->avatar) > 0) {
				$profilepic = $data->avatar;
			}
			else 
			$profilepic = "avatar/default.png";

			$session_user_info = $connect->query("SELECT * FROM users WHERE user = '{$user}'");
			$session_user_data = $session_user_info->fetch(PDO::FETCH_OBJ);


			if($get_comms->user == $user || $session_user_data->type == 1) {
				$remove_comm = 	'<div align="right" class="x">
						 	<a onclick="rmcomm('.$get_comms->id.')"><i class="icon-remove"></i></a>
						 	</div>';
			}
			else $remove_comm = "";


			if(strlen($get_comms->answer) <= 30) $ansclass = "small";
			elseif(strlen($get_comms->answer) > 30 &&  strlen($get_comms->answer) <= 180) $ansclass ="large";
			elseif(strlen($get_comms->answer) > 180 && strlen($get_comms->answer) <= 200) $ansclass ="large2";
			elseif (strlen($get_comms->answer) > 200) $ansclass ="large3";
				echo '
					<br>
					<div id="comm'.$get_comms->id.'" class="comment" style="width:450px">
					<div class="heads">
					'.$remove_comm.'
					<div class="avatar" onclick="showprofile('.$data->id.')"><img src="'.$profilepic.'"></div>
					<div class="name" onclick="showprofile('.$data->id.')">'.$get_comms->user.'</div><br><p class="date">'.$get_comms->date.'</p>
					</div>
					<textarea style="width:420px" class="'.$ansclass.'">'.$get_comms->answer.'</textarea>
					</div>
					<br>
				';
			}
			
			echo '</div></div>';
		}
	unset($obj);
}

elseif($_POST['lastanswer'] && isset($_SESSION['log'])) {
			
	$iqd = filter_var($_POST["lastanswer"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($iqd)){
		header('HTTP/1.1 403 Forbidden BITCH !');
		die("mmmm bitch !");
	}
			
			$user = $_SESSION['log'];
			$comment  	= $connect->query("SELECT * FROM answers WHERE id_quest = {$iqd} ORDER BY date DESC");
			
			$get_comm 	= $comment->fetch(PDO::FETCH_OBJ);
			
			$whois 		= $get_comm->user;
			$q2 		= $connect->query("SELECT * FROM users WHERE user = '{$whois}'");
			$data 		= $q2->fetch(PDO::FETCH_OBJ);
			if(strlen($data->avatar) > 0) {
				$profilepic = $data->avatar;
			}
			else 
			$profilepic = "avatar/default.png";


			$session_user_info = $connect->query("SELECT * FROM users WHERE user = '{$user}'");
			$session_user_data = $session_user_info->fetch(PDO::FETCH_OBJ);


			if($get_comm->user == $user || $session_user_data->type == 1) {
				$remove_comm = 	'<div align="right" class="x">
						 	<a onclick="rmcomm('.$get_comm->id.')"><i class="icon-remove"></i></a>
						 	</div>';
			}
			else $remove_comm = "";

			if(strlen($get_comm->answer) <= 30) $ansrclass = "small";
			elseif(strlen($get_comm->answer) > 30 &&  strlen($get_comm->answer) <= 180) $ansrclass ="large";
			elseif(strlen($get_comm->answer) > 180 && strlen($get_comm->answer) <= 200) $ansrclass ="large2";
			elseif (strlen($get_comm->answer) > 200) $ansrclass ="large3";
			
				echo '
					<div id="comm'.$get_comm->id.'" class="comment" style="width:450px;">
					<div class="heads">
					'.$remove_comm.'
					<div class="avatar" onclick="showprofile('.$data->id.')"><img src="'.$profilepic.'"></div>
					<div class="name" onclick="showprofile('.$data->id.')">'.$get_comm->user.'</div><br><p class="date">'.$get_comm->date.'</p>
					</div>
					<textarea style="width:420px" class="'.$ansrclass.'">'.$get_comm->answer.'</textarea>
					</div>
					<br>
				';
}
?>