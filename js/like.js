function likeit(id) {

	$.post('like.php',{likeid : id},function(response){
		if(!isNaN(response)) {
		
			$("#nlikes_"+id).text(response);
			$("#like_"+id).fadeOut('slow');
		}
	});



return false;
}