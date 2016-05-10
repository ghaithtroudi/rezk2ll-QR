<?php
/*

profile page

@author khaled

*/


session_start();
error_reporting(0);
include("connect.php");

if($_POST['uid']) {

	$uid = filter_var($_POST["uid"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($uid)){
		header('HTTP/1.1 403 Forbidden !');
		die("Erreur d'accès au serveur");
	}


	$infos = $connect->query("SELECT * FROM users WHERE id = {$uid} ");
	$uinfo = $infos->fetch(PDO::FETCH_OBJ);
	if(strlen($uinfo->user)> 1) {

		if(strlen($uinfo->avatar) < 5) {

			$profilepic = "<img src='avatar/default.png'>";
		}
		else {

			$profilepic = "<img height='50' width='70' src='{$uinfo->avatar}'>";
		}
		if(isset($_SESSION['log'])) {
		$user = $_SESSION['log'];
		$adinfo = $connect->query("SELECT * FROM users WHERE user = '{$user}' ");
		$addata = $adinfo->fetch(PDO::FETCH_OBJ);
		if($addata->type == 1) {
			$manage = '<span onclick="manage('.$uid.')" class="profile-menu">Modifier</span>';
		}
		else $manage ="";
		}
		else $manage ="";

		?>
		<br><br>
		<div class="profile-container">
			<div class="profile">
			&nbsp;<div class="heads">
						<div class="avatar">
							<center><?php echo $profilepic ?></center>
						</div>
						<div class="name">
							<?php echo $uinfo->user ?>
						</div>
						<br><br>
						<span class="profile-menu" onclick=show_info(<?php echo $uid;  ?>)>Infos</span><span class="profile-menu" onclick=show_quests(<?php echo $uid;  ?>)>Questions</span><?php echo $manage ?>
						<br>
				</div>
				<hr>
				<br>
				<div id="prof">
					<div class="mainloading">
					Chargement .... <img src="img/ajax-loader.gif">
					<br><br>
					</div>
				</div>
			</div>
			</div>
		<?php
	}

}

elseif($_POST['info']) {


	$id = filter_var($_POST["info"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($id)){
		header('HTTP/1.1 403 Forbidden');
		die("Erreur d'accès au serveur");
	}


	$data = $connect->query("SELECT * FROM users WHERE id ={$id}");
	$info = $data->fetch(PDO::FETCH_OBJ);

	$data1 = $connect->query("SELECT count(*) as cont_ans FROM answers WHERE user ='{$info->user}' ");
	$data2 = $connect->query("SELECT count(*) as cont_qst FROM quests WHERE user = '{$info->user}' ");
	$fetch1 = $data1->fetch(PDO::FETCH_OBJ);
	$fetch2 = $data2->fetch(PDO::FETCH_OBJ);

	echo '<div class="heads">
	<table>
			<tr>
			<td><p class="name">Date d''inscription &nbsp;&nbsp;</p></td><td><p> '. $info->sign_date 	.'</p> </td>
			</tr>
			<tr>
			<td><p class="name">Réponses 	&nbsp;&nbsp;</p></td><td><p> '. $fetch1->cont_ans 	.'</p></td>
			</tr>
			<tr>
			<td><p class="name">Questions 	&nbsp;&nbsp;</p></td><td><p> '. $fetch2->cont_qst	.'</p></td>
			</tr>
	</table>
		</div>';
}

elseif($_POST['quests']) {

	$id = filter_var($_POST["quests"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($id)){
		header('HTTP/1.1 403 Forbidden');
		die("Erreur d'accès au serveur");
	}
	

	$user_info = $connect->query("SELECT * FROM users WHERE id = {$id}");
	$user_data = $user_info->fetch(PDO::FETCH_OBJ);
	$resultn = $connect->query("SELECT count(*) as `nqst` FROM quests WHERE user = '{$user_data->user}' ORDER BY id DESC");
	$cnt_qst = $resultn->fetch(PDO::FETCH_OBJ);
	if ($cnt_qst->nqst != 0) {

		$results = $connect->query("SELECT * FROM quests WHERE user = '{$user_data->user}' ORDER BY id DESC");

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
			<div class="name"><p>'.$obj->date.'</p></div>
			</div>
			<textarea class="'.$class.'" readonly>'.$obj->content.'</textarea>
			<a onclick="showq('.$obj->id.')" id="answer">Answer |</a> 
			'.$like.'
			<span> '.$num_ans.' <i class="icon-comments-alt"></i> </span> |
			<span> <span id="nlikes_'.$obj->id.'">'.$num_likes.'</span> <i class="icon-thumbs-up"></i> |</span> 
			<span> '.$num_views.' <i class="icon-eye-open"></i> </span>
			</div>
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
			<p>Il parait que vous n'avez pas de questions ! <br> Tenez vous à l'aise de demander ?</p>
	</div>
	<script language="javascript">
		$("#more").slideUp("slow");
	</script>
	<?php
	die();
	}
}
elseif($_POST['admin']) {

		$id = filter_var($_POST["admin"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($id)){
		header('HTTP/1.1 403 Forbidden');
		die("Erreur d'accès au serveur");
	}
	if(isset($_SESSION['log'])) {
	$user = $_SESSION['log'];
	$info = $connect->query("SELECT * FROM users WHERE user = '$user'");
	$unfo = $info->fetch(PDO::FETCH_OBJ);

	if($unfo->type == 1) {

		$user_info = $connect->query("SELECT * FROM users WHERE id = {$id}");
		$user_data = $user_info->fetch(PDO::FETCH_OBJ);

	echo '<div class="heads">
	<table>
			<tr>
			<td><p class="name">name &nbsp;&nbsp;</p></td><td><p> '. $user_data->user 	.'</p> </td>
			</tr>
			<tr>
			<td><p class="name">password &nbsp;&nbsp;</p></td><td><p> '. $user_data->pass 	.'</p> </td>
			</tr>
			<tr>
			<td><p class="name">Email: &nbsp;&nbsp;</p></td><td><p> '. $user_data->email 	.'</p> </td>
			</tr>
			<tr>
			<td><p class="name">joining date &nbsp;&nbsp;</p></td><td><p> '. $user_data->sign_date 	.'</p> </td>
			</tr>
			<tr>
				<td colspan="2"><br><br><button class="ask" onclick="deluser('.$id.')">Delete user</button></td>
			</tr>
	</table>
		</div>';


	}
	else die("Hahaha, un bon essai mon ami");
	}
	else die("Hahaha, un bon essai mon ami");
}
elseif($_POST['deluser']) {


	$id = filter_var($_POST["deluser"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	
	//throw HTTP error if group number is not valid
	if(!is_numeric($id)){
		header('HTTP/1.1 403 Forbidden !');
		die("Erreur d'accès au serveur");
	}

	if(isset($_SESSION['log'])) {
	$user = $_SESSION['log'];

	$re = $connect->query("SELECT * FROM users WHERE user ='$user' ");
	$userd = $re->fetch(PDO::FETCH_OBJ);

	$uu = $connect->query("SELECT * FROM users WHERE id = $id");
	$ui = $uu->fetch(PDO::FETCH_OBJ);

	if($userd->type == 1 && $ui->type == 0) {
		if($connect->query("DELETE FROM users WHERE id = $id"))
			if($connect->query("DELETE FROM quests WHERE user = '$ui->user' "))
				if($connect->query("DELETE FROM answers WHERE user = '$ui->user' "))
					if($connect->query("DELETE FROM likes WHERE user = '$ui->user' "))
						die("done");
		

		}
	}

}
?>