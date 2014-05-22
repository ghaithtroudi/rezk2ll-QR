function resetpw() {

		name = $("#user").val();
		mail = $("#mail").val();

			$("#user").click(function(){
			$("#user").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});


			$("#mail").click(function(){
			$("#mail").css("background","transparent");
			$("#adr_err").fadeOut("slow");
			});
		
		if(name.length < 6) {
			$("#user").css("background","red");
			$("#user").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>username must be at least 6 characters</div>");
			return false;
		}

		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
	if(!re.test(mail)) {
			$("#mail").css("background","red");
			$("#mail").val("");
			$("#adr_err").fadeIn("fast");
			$("#adr_err").html("<div class='error'>invalid email adress</div>");
			return false;
	}

	$.ajax({
		type: "POST",
		url: "reset.php",
		data: "username="+name+"&email="+mail,
		success: function(html){
	    if(html=='done')
    {

    $("#f").slideUp(3500);
	$("#adr_err").fadeIn("fast");
	$("#adr_err").html("<p class='wait'>A new password have been sended to your e-mail box</p>");
    }

    else
    {
	 $("#adr_err").fadeIn("fast");
     $("#adr_err").html("<p class='error'>"+html+"</p>");
	 $("#user").val("");
	 $("#mail").val("");
	 $("#user").css("background","red");
	 $("#mail").css("background","red");
	 }
   },
   beforeSend:function()
   {
    $("#adr_err").html("<div class='animation_image' ><img src='img/ajax-loader.gif'></div>")
   }
	});

  return false;

	}