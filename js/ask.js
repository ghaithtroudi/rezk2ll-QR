function resetask() {

$("#myquest").css("border","2px solid #4c83af");

}

$(document).ready(function(){

	$(".ask").click(function(){
	
		var myquest = $("#myquest").val();
		
		if(myquest.length < 5) {
		$("#myquest").css("border","2px solid red");
		return false;
		}
		
		$("#myquest").blur(function(){
		
		$("#myquest").css("border","2px solid #4c83af");
		
		});
		
		if(myquest.charAt('?') == -1) {
		myquest = myquest+" ?";
		}
		
		$.ajax({
		type : "POST",
		url : "ask.php",
		data : {"question" : myquest},
		success: function(data){
		if(data == "true") {
		$("#myquest").val('');
		$(".stats").load("stat.php");
		$("#nomore").fadeOut("slow");
			$.get('ask.php?last=get',function(lastone){
			
			$("#results").prepend(lastone).fadeIn(3000);
			
			});
		}
		else {
		$("#myquest").css("border","2px solid red");
		}
		}
		});
		
		
	});
});