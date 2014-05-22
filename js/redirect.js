$(document).ready(function(){
	$("#main").load("quest.php");
	$(".stats").load("stat.php");
	
	$("#homelink").click(function(){
		$("#main").load("quest.php");
	})
	$("#loginlink").click(function(){
		$("#main").load("log.html");
	});
	
	$("#loginlink2").click(function(){
		$("#main").load("log.html");
	});
	
	$("#loginlink3").click(function(){
		$("#main").load("log.html");
	});
	
	$("#registerlink").click(function(){
		$("#main").load("reg.html");
	});
	
	$("#logoutlink").click(function(){
		$("#main").load("logout.php");
	});
	$("#faqlink").click(function(){
		$("#main").load("faq.html");
	});
	
	$("#contactlink").click(function(){
		$("#main").load("cont.php");
	});
	
	$("#chpass").click(function(){
		$("#main").load('manage/pass.php');
		$("#navmenu").slideUp("slow");
	});
	
	$("#chml").click(function(){
		$("#main").load('manage/mail.php');
		$("#navmenu").slideUp("slow");
	});
	
	$("#chav").click(function(){
		$("#main").load('manage/avatar.html');
		$("#navmenu").slideUp("slow");
	});
		
});

	function deluser(id) {

		$("#prof").slideUp("slow");

		$.post('profile.php',{deluser : id},function(data){

			if(data == "done" ) {
				$("#main").load("quest.php");
			}
			else {

				$("#prof").slideDown("slow");
			}

		});

	}

		// remove error
		
		function rmx(){
		$("#nomore").toggle("slow");
		}

		// remove question

		function rmq(id){
			$.post('delquest.php',{qid:id},function(ret) {
				if(ret == "done") {

					$("#"+id).toggle("slow");
					$(".postanswer").slideUp("slow");
					$("#myanswer").slideUp("slow");
					$("#mycomments").html("<div class='wait'>Question deleted <br><br><br><br><center><a href='index.php'><button class='ask'>Get back home <i class='icon-reply'></i></button></a></center>");
					$(".stats").load("stat.php");
				}

		});

		}

		function manage(id) {

		$("#prof").slideUp("fast");

		$.post('profile.php',{admin : id},function(data){

			if(data.length > 10 ) {
				$("#prof").slideDown("fast");
				$("#prof").html(data);
			}

		});

		}

		function rmcomm(id) {
			$.post('delcomm.php',{cid:id},function(res){

				if(res == "done") {

					$("#comm"+id).slideUp("slow");
					$(".stats").load("stat.php");
				}
			})
		}

		// load question page

		function showq(id_q) {
		$.post('answer.php',{questid:id_q},function(answers){
			$("#main").html(answers).slideDown('slow');
		});
	
		}
	
	// load login page
	
	function logg(){
	
	$("#main").load("log.html");
	
	}
	
	// post a comment
	
	function comm(qid) {
		var ans = $("#myanswer").val();
		$("#myanswer").click(function(){
		
			$("#myanswer").css("border","2px solid #4c83af");
		})
		if(ans.length < 5) {
		
		$("#myanswer").css("border","2px solid red");
		
		return false;
		}
		
		$.post("addcomm.php",{addcomm : ans , idq : qid},function(commm){
		
			if(commm == "true") {
			
			$("#myanswer").val('');
			$(".stats").load("stat.php");
			$.post('answer.php',{lastanswer : qid},function(lastone){
			
			$("#mycomments").prepend(lastone).fadeIn(3000);
			
			});
			
			}
			
			else {
			
			$("#myanswer").css("border","2px solid red");
			
			return false;
			
			}
		});
}

	// change password

	function changepw() {
		password 	= $("#password").val();
		password2 	= $("#password2").val();
		password3 	= $("#password3").val();
		

			$("#password").click(function(){
			$("#password").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
	
			$("#password2").click(function(){
			$("#password2").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
			
			$("#password3").click(function(){
			$("#password3").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
		
		if(password.length < 6) {
			$("#password").css("background","red");
			$("#password").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>password must be at least 6 characters</div>");
			return false;
		}
		
		
		if(password2.length < 6) {
			$("#password2").css("background","red");
			$("#password2").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>new password must be at least 6 characters</div>");
			return false;
		}
		
		if(password3.length < 6) {
			$("#password3").css("background","red");
			$("#password3").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>new password must be at least 6 characters</div>");
			return false;
		}
		
		if(password2 != password3) {
			$("#password3").css("background","red");
			$("#password2").css("background","red");
			$("#password2").val("");
			$("#password3").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>passwords are not the same</div>");
			return false;
		}
		
		r = $("#chanp").serialize();
		$.ajax({
		
		type: "POST",
		url: "manage/pass.php",
		data: r,
		success: function(rep){
			if(rep == "done") {
			$("#main").prepend("<div id='adr_err'></div>");
			$("#chanp").slideUp("slow");
			$("#adr_err").html("<div class='wait'>password changed succesfully</div>");
			}
			else {
			$("#main").prepend("<div id='adr_err'></div>");
			$("#adr_err").html("<div class='error'>"+rep+"</div>");
			$("#password").val('');
			$("#password2").val('');
			$("#password3").val('');
			}
		}
		});
}

	function changemail(){
	
	pss = $("#password").val();
	cml = $("#cemail").val();
	nml = $("#nemail").val();
	
			$("#password").click(function(){
			$("#password").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
	
			$("#cemail").click(function(){
			$("#cemail").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
			
			$("#nemail").click(function(){
			$("#nemail").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
	
	if(pss.length < 6) {
			$("#password").css("background","red");
			$("#password").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>password must be at least 6 characters</div>");
			return false;
		}
		
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
	if(!re.test(cml)) {
			$("#cemail").css("background","red");
			$("#cemail").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>invalid email adress</div>");
			return false;
	}
	
	if(!re.test(nml)) {
			$("#nemail").css("background","red");
			$("#nemail").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>invalid email adress</div>");
			return false;
	}
	
	if(nml == cml) {
			$("#nemail").css("background","red");
			$("#nemail").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>choose a new email adress</div>");
			return false;
	}
	r = $("#chanp").serialize();
	$.ajax({
	type : "POST",
	url : "manage/mail.php",
	data : r ,
	success : function(resp){
			if(resp == "done") {
			$("#main").prepend("<div id='adr_err'></div>");
			$("#chanp").slideUp("slow");
			$("#adr_err").html("<div class='wait'>email changed succesfully</div>");
			}
			else {
			$("#main").prepend("<div id='adr_err'></div>");
			$("#adr_err").html("<div class='error'>"+resp+"</div>");
			$("#password").val('');
			$("#cemail").val('');
			$("#nemail").val('');
			}
		}
	});
}

	function contactus() {
		email = $("#mail").val();
		subject = $("#subject").val();
		massage = $("#mess").val();
	
	$("#mail").click(function(){
		$("#mail").css("background","transparent");
		$("#adr_err").fadeOut("slow");
	});
	
	$("#subject").click(function(){
		$("#subject").css("background","transparent");
		$("#adr_err").fadeOut("slow");
	});
	
	$("#mess").click(function(){
		$("#mess").css("background","transparent");
		$("#adr_err").fadeOut("slow");
	});
	
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if(!re.test(email)) {
			$("#mail").css("background","red");
			$("#mail").val("");
			$("#adr_err").fadeIn("slow");
			$("#adr_err").html("<div class='error'>invalid email</div>");
			return false;
	}
	
	if(subject.length < 5) {
			$("#subject").css("background","red");
			$("#subject").val("");
			$("#adr_err").fadeIn("slow");
			$("#adr_err").html("<div class='error'>be serius , subject is too short</div>");
			return false;
	}
	
	if(massage.length < 10) {
			$("#mess").css("background","red");
			$("#mess").val("");
			$("#adr_err").fadeIn("slow");
			$("#adr_err").html("<div class='error'>be serius , message is too short</div>");
			return false;
	}
	
	r = $("#con").serialize();
	alert(r);
	$.ajax({
	method : "POST",
	url	: "contact.php",
	data : r,
	success : function(response){
	
	if(response == "done") {
	$("#con").slideUp("slow");
	$("#adr_err").fadeIn("fast");
	$("#adr_err").html("<p class='wait'>thank you , your message has been sent to us !</p>");
	}
	else {
	
	$("#adr_err").fadeIn("fast");
	$("#adr_err").html("<p class='error'>"+response+"</p>");
	$("#mess").css("background","red");
	$("#mess").val("");
	$("#mail").css("background","red");
	$("#mail").val("");
	$("#subject").css("background","red");
	$("#subject").val("");
	}
	}
	});
	}

	function showprofile(id) {

		$.post("profile.php",{uid : id},function(response) {

				if(response.length > 20) $("#main").html(response);
		});
				$("#prof").slideUp("fast");

		$.post('profile.php',{info : id},function(data){

			if(data.length > 10 ) {
				$("#prof").slideDown("fast");
				$("#prof").html(data);
			}

		});
	}

	function show_info(id) {

		$("#prof").slideUp("fast");

		$.post('profile.php',{info : id},function(data){

			if(data.length > 10 ) {
				$("#prof").slideDown("fast");
				$("#prof").html(data);
			}

		});
	}

	function show_quests(id) {
		$("#prof").slideUp("fast");
		$.post('profile.php',{quests : id},function(data) {

			if(data.length > 10 ) {
				$("#prof").slideDown("fast");
				$("#prof").html(data);
			}

		});

	}