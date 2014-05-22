<?php 

error_reporting(0);
include('connect.php');

$q = $connect->query("SELECT COUNT(*) as `number` FROM quests");
$result = $q->fetch(PDO::FETCH_OBJ);
$stat['q_number'] = $result->number;

$q = $connect->query("SELECT user from users order by id DESC");
$result = $q->fetch(PDO::FETCH_OBJ);

$stat['last_user'] = $result->user;

$q = $connect->query("SELECT COUNT(*) as `nanswer` FROM answers");
$result = $q->fetch(PDO::FETCH_OBJ);

$stat['answers'] = $result->nanswer;

$q = $connect->query("SELECT COUNT(*) as `nuser` FROM users");
$result = $q->fetch(PDO::FETCH_OBJ);

$stat['users'] = $result->nuser;

?>

<table>
<tr>

	<h3>Statistics</h3>
	<br>
	<hr>
	<br>
	<div class="st" title="We have <?php echo $stat['q_number']; ?> Questions">
		<span class="sp"><?php echo $stat['q_number']; ?></span><i class="icon-question"></i>
	</div>
	<br>
	<div class="st" title="We have <?php echo $stat['answers']; ?> Answers">
		<span class="sp"><?php echo $stat['answers']; ?></span> <i class="icon-lightbulb"></i>
	</div>
	<br>
	<div class="st" title="We have <?php echo $stat['users']; ?> Users">
		<span class="sp"><?php echo $stat['users']; ?></span> <i class="icon-group"></i> 
	</div>
	<br>
	<div class="st" title="Our latest user">
	<i class="icon-user"></i> <span class="sp2"> <?php echo $stat['last_user']; ?></span> 
	</div>
	<br>
	<div>
	<br>
	<br>
	<br>
	<h3>Find me</h3>
	<br>
	<hr>
	<br>
	<br>
	<br>
<a target="_blank" title="contact us on facebook" href="https://facebook.com/k2ll33d"><img src="img/fb.png"></a> &nbsp;&nbsp;
<a target="_blank" title="follow us on tweet" href="https://twitter.com/rezk2ll0"><img src="img/tw.png"></a> &nbsp;&nbsp;
<a target="_blank" title="find us in google+"href="https://plus.google.com/u/0/103281836066747354593"><img src="img/go.png"></a>
	</div>