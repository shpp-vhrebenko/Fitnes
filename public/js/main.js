$( document ).ready(function() {
	// animation sidebar navigation
    $("#fixed-menu div div").click(function(){    
		$("#div-menu").stop(true , true).animate({right:"0"},500);				      
	});				    
	$("#div-menu .button-close").click(function(){				         	
		$("#div-menu").stop(true , true).animate({right:"-325"},500);				      
	});

	// animation smooth scrolling of the anchor in the address bar
	$("a.anchor-link").on("click", function(e){
		var anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: $(anchor.attr('href')).offset().top
		}, 777);
		e.preventDefault();
		return false;
	});	

	$('#sliderResults').slick({   
    dots: true,
    customPaging : function(slider, i) {
    var thumb = $(slider.$slides[i]).data();
    return '<a>'+(i+1)+'</a>';
    },
    arrows: true,
    vertical: true,
    slidesToShow: 1,
    centerMode: true,       
  }); 


	

});