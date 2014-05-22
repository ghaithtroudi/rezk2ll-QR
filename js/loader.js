$(document).ready(function() {
	var track_load = 0; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	
	$('#results').load("load_quests.php", {'group_no':track_load}, function() {track_load++;}); //load first group
	
		$("#more").click(function(){//detect page scroll
			if(loading==false) //there's more data to load
			{
				loading = true; //prevent further ajax loading
				$('.animation_image').fadeIn("slow"); //show loading image
				
				//load data from the server using a HTTP POST request
				$.post('load_quests.php',{'group_no': track_load}, function(data){
								
					if(data.length < 1) $('#more').hide();

								
					$("#results").append(data); //append received data into the element
					$("#results").fadeIn(3000);
					//hide loading image
					$('.animation_image').fadeOut(2000); //hide loading image once data is received
					
					track_load++; //loaded group increment
					loading = false; 
				
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					
					alert(thrownError); //alert with HTTP error
					$('.animation_image').fadeOut(2000); //hide loading image
					loading = false;
				
				});
				
			}
	});
	
	$("#loginlink2").click(function(){
		$("#main").load("log.html");
	});
});