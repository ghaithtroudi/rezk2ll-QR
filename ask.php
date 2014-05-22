<?php 
error_reporting(0);
session_start();
include('connect.php');

if($_REQUEST['last'] && isset($_SESSION['log'])) {

	$results = $connect->query("SELECT * FROM quests ORDER BY id DESC");
	
	if ($results) {
		//output results from database
		
		$obj = $results->fetch(PDO::FETCH_OBJ);
			
			
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
			$q 		= $connect->query("SELECT * FROM users WHERE user = '{$whois}'");
			$data 	= $q->fetch(PDO::FETCH_OBJ);
			if(strlen($data->avatar) > 0) {
				$profilepic = '<img src="'.$data->avatar.'" width="60" height="60">';
			}
			else 
			$profilepic = '<img src="avatar/default.png" width="60" height="60">';

			if(strlen($obj->content) < 50) $class = "small";
			elseif(strlen($obj->content) > 50 &&  strlen($obj->content) < 210) $class ="large";
			elseif(strlen($obj->content) > 210) $class ="large2";

			if($obj->user == $user || $user == "admin") {
				$remove = 	'<div align="right" class="x">
						 	<a onclick="rmq('.$obj->id.')"><i class="icon-remove"></i></a>
						 	</div>';
			}
			else $remove = '';

			echo '
			<div class="comment" id='.$obj->id.'>
			<div class="heads">
			'.$remove.'
			<div class="avatar" onclick="showprofile('.$data->id.')">
			'.$profilepic.'
			</div>
			<div class="name" onclick="showprofile('.$data->id.')">'.$obj->user.'</div> <br><p class="date">'.$obj->date.'</p>
			</div>
			<textarea class="'.$class.'" readonly>'.$obj->content.'</textarea>
			<a href="#" onclick="showq('.$obj->id.')" id="answer" rel="'.$obj->id.'"><i class="icon-comments-alt"></i>&nbsp;answer it</a> |
			'.$like.'
			<span> <span id="nlikes_'.$obj->id.'">'.$num_likes.'</span> <i class="icon-thumbs-up"></i> </span> |
			<span> '.$num_views.' <i class="icon-eye-close"></i> </span>
			</div>
			<br>
			';
		}
	
	unset($obj);
}




if(($_POST) && isset($_SESSION['log'])) {
	
	// some weak protection
	
	$question = strip_tags($_POST['question']);
	$question = addslashes($question);

	if((empty($question)) || (strlen($question) < 5)) { die(); }
	
	// sorry for consorship :(
	
	$forbiddens = array("hacked","defaced","pwned","HaCkEd");
	
	foreach($forbiddens as $forbidden) {
		$question = preg_replace('/\b'.$forbidden.'\b/', '...', $question);
	}
	
	if(!preg_match('/?/',$question)) {
	
	$question = $question." ?";
	}
	
	// let's add it to database
	
	// let's gate the time

	$date = date('Y-m-d H:i:s');
	$user = $_SESSION['log'];
	$datas = array(
		$user,$question,$date
	);
	
	$q = $connect->prepare("INSERT INTO quests (user , content , date) 
			values (?,?,?);
	");
	$go = $q->execute($datas);
	if($go) {
		die("true");
	}
	
}


?>