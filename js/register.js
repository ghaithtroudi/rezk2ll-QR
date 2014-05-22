
$(document).ready(function(){
  $("#username").focus();
});
	function reg() {
	

	user  = $("#username").val();
	pass  = $("#password").val();
	pass2 = $("#rpassword").val();
	email = $("#email").val();

			$("#username").click(function(){
			$("#username").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
		
			$("#email").click(function(){
			$("#email").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
			
			$("#password").click(function(){
			$("#password").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
	
			$("#rpassword").click(function(){
			$("#rpassword").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
			
	
		if(user.length < 6) {
			$("#username").css("background","red");
			$("#username").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>username must be at least 6 characters</div>");
			return false;
		}
		

		
		if(pass.length < 6) {
			$("#password").css("background","red");
			$("#password").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>password must be at least 6 characters</div>");
			return false;
		}
		

		
		if(pass != pass2) {
			$("#rpassword").css("background","red");
			$("#rpassword").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>passwords dismatch</div>");
			return false;
		}

		
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
		if(!re.test(email)) {
			$("#email").css("background","red");
			$("#email").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>invalid email</div>");
			return false;
		}
		
		var ra = /^[a-zA-Z0-9]+$/;
		if(!ra.test(user)) {
			$("#username").css("background","red");
			$("#username").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>invalid username</div>");
			return false;
		}
		
		

		
	$.ajax({
			type: "POST",
			url: "register.php",
			data: $("#f").serialize(),
			success: function(html){
			
			if(html == "welcome") {
				 $(".stats").load("stat.php");
			     $("#f").slideUp(2000);
				 $("#ulog").slideUp("slow");;
				 $("#ulrg").slideUp("slow");
				 $("#add_err").fadeIn("fast");
				 $("#add_err").html("<p class='wait'>success , you are now a member among us , welcome</p>");
				 $("#add_err").slideUp(3000);
				 window.location.reload();
			}
			
			else {
			
				 $("#add_err").fadeIn("fast");
				 $("#add_err").html("<p class='error'>"+html+"</p>");
				 $("#username").css("background","red");
				 $("#password").css("background","red");
				 $("#rpassword").css("background","red");
				 $("#email").css("background","red");
				 $("#f").click(function(){
					 $("#username").css("background","transparent");
					 $("#password").css("background","transparent");
					 $("#rpassword").css("background","transparent");
					 $("#email").css("background","transparent");
					 $("#add_err").fadeOut("slow");
				 })
				}
			
			},
		beforeSend:function()
		{
		$("#add_err").html("<img src='img/loading.gif'> wait ...")
		}
			});

		return false;
		
		}