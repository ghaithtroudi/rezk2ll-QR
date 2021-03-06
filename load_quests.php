<?php
session_start();
include("connect.php"); //include connection file
error_reporting(0);
$items_per_group = 4; // how many question per request ?


if($_POST) {
	//sanitize post value
	$group_number = filter_var($_POST["group_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($group_number)){
		header('HTTP/1.1 403 Forbidden BITCH !');
		die("mmmm bitch !");
	}
	
	//get current starting point of records
	$position = ($group_number * $items_per_group);
	
	//Limit our results within a specified range. 
	$resultn = $connect->query("SELECT count(*) as `nqst` FROM quests ORDER BY id DESC LIMIT $position, $items_per_group");
	$cnt_qst = $resultn->fetch(PDO::FETCH_OBJ);
	if ($cnt_qst->nqst != 0) {

		$results = $connect->query("SELECT * FROM quests ORDER BY id DESC LIMIT $position, $items_per_group");

		//output results from database
		
		while($obj = $results->fetch(PDO::FETCH_OBJ)) {
			
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
				$profilepic = $data->avatar;
			}
			else 
			$profilepic = "avatar/default.png";

			$session_user_info = $connect->query("SELECT * FROM users WHERE user = '{$user}'");
			$session_user_data = $session_user_info->fetch(PDO::FETCH_OBJ);


			if($obj->user == $user || $session_user_data->type == 1) {
				$remove = 	'<div align="right" class="x">
						 	<a onclick="rmq('.$obj->id.')"><i class="icon-remove"></i></a>
						 	</div>';
			}
			else $remove = "";

			if(strlen($obj->content) < 50) $class = "small";
			elseif(strlen($obj->content) > 50 &&  strlen($obj->content) < 210) $class ="large";
			elseif(strlen($obj->content) > 210) $class ="large2";
			
			echo '
			<div class="comment"  id='.$obj->id.'>
			<div class="heads">
			'.$remove.'
			<div class="avatar" onclick="showprofile('.$data->id.')"><img src="'.$profilepic.'" width="60" height="60"></div>
			<div class="name" onclick="showprofile('.$data->id.')">'.$obj->user.'</div> <br><p class="date">'.$obj->date.'</p>
			</div>
			<textarea class="'.$class.'" readonly>'.$obj->content.'</textarea>
			<a onclick="showq('.$obj->id.')" id="answer">Answer |</a> 
			'.$like.'
			<span> '.$num_ans.' <i class="icon-comments-alt"></i> </span> |
			<span> <span id="nlikes_'.$obj->id.'">'.$num_likes.'</span> <i class="icon-thumbs-up"></i> |</span> 
			<span> '.$num_views.' <i class="icon-eye-open"></i> </span>
			</div>
			<br><br>
			';
		}
	
	}
	else
	{
	?>
	<div id="nomore" class="comment">
			<div class="heads">
			<div align="right" class="x">
			<a onclick="rmx()"><i class="icon-remove"></i></a>
			</div>
			<div class="name">Oops !</div>
			</div>
			<p>Il parait qu'il n'y a pas de question pour le moment ! <br> Seriez vous le premier à y demander ?</p>
	</div>
	<script language="javascript">
		$("#more").slideUp("slow");
	</script>
	<?php
	die();
	}
	unset($obj);
}
?>