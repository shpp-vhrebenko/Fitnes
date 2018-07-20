$( document ).ready(function() {
	var flag = true;
	$("#open-sidebar").on('click',function(){	  
		if(flag) {
			$("#profile-sidebar").stop( true, true ).animate({left: "0px"} , 1000);
			flag = false;
		} else {
			$("#profile-sidebar").stop( true, true ).animate({left: "-265px"} , 1000);
			flag = true;
		} 	    
	});  	  

});