$(document).ready(function(){
  $("#login_form").fadeIn("slow");
  $("#username").focus();

  
  $("#logregister").click(function(){
    $("#main").load("reg.html");
  });

  $("#forget").click(function(){
    $("#main").load("reset.html");
  });

 $("#login").click(function(){

  username=$("#username").val();
  password=$("#password").val();
  
  var ra = /[a-zA-Z0-9]+$/;
	if(!ra.test(username)) {
	$("#add_err").fadeIn("fast");
     $("#add_err").html("<p class='error'>Wrong username / password</p>");
	 $("#username").val("");
	 $("#password").val("");
	 $("#username").css("background","red");
	 $("#password").css("background","red");
	return false;
	}
  
  $.ajax({
   type: "POST",
   url: "login.php",
   data: "username="+username+"&password="+password,
   success: function(html){
    if(html=='true')
    {
     $("#f").slideUp(3500);
     $("#ulog").slideUp(3500);;
	 $("#ulrg").slideUp(3500);
	 $("#add_err").fadeIn("fast");
	 $("#add_err").html("<p class='wait'>welcome</p>");
	 $("#add_err").slideUp(3500);
	 window.location.reload();
    }
    else
    {
	 $("#add_err").fadeIn("fast");
     $("#add_err").html("<p class='error'>Wrong username / password</p>");
	 $("#username").val("");
	 $("#password").val("");
	 $("#username").css("background","red");
	 $("#password").css("background","red");
	 }
   },
   beforeSend:function()
   {
    $("#add_err").html("<div class='animation_image' ><img src='img/ajax-loader.gif'></div>")
   }
  });
  return false;
 });
 $("#username").click(function(){
 $("#add_err").fadeOut("slow");
 $("#password").css("background","transparent");
 $("#username").css("background","transparent");
 });

});