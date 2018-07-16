$( document ).ready(function() {
	// animation sidebar navigation
    $("#button_open_sidebar-menu").click(function(){     	 
		$("#sidebar-menu").stop(true , true).animate({right:"0"},500);				      
	});				    
	$("#button_close_sidebar-menu").click(function(){				         	
		$("#sidebar-menu").stop(true , true).animate({right:"-325"},500);				      
	});
});