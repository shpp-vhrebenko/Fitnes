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

	// initial plugin slick slider
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

	// faq
	var answers = [
	"В стоимость программы входит курс тренировок на указанный срок, программа питания с рецептами, раздел с интересными статьями и советами, моя поддержка в общем чате 24/7, а также связь с куратором программы."
     ,"Для участия в программе вы должны быть старше 18 лет."
     ,"Все марафоны разработаны для занятий дома, без необходимости иметь какое-либо дополнительное оборудование. Программы, направленные на набор мышечной массы, наоборот, могут выполняться только в спортивном зале."
     ,"Если у вас есть ограничения по здоровью, для начала, вам необходимо проконсультироваться с врачом."
     ,"Доступ к программе тренировок открывается только на время действия абонемента. При желании вы сможете продлить абонемент. "
     ,"Данные программы предназначены для относительно здоровых людей, не имеющих каких-либо ограничений. Программы не предназначены для беременных или кормящих девушек.",
    "При правильном выполнении указаний, тренировок, и соблюдении питания, результаты вы увидите уже после первой недели. ",
    "Не волнуйтесь, наше меню достаточно гибкое, у вас будет возможность выбирать из предложенных продуктов.",
    "К сожалению, на данный момент такое питание отсутсвует, но будет разработано в ближайшем будущем."];

    $("#faq-list-tab li").click(function(){
    	var idQuestion = $(this).data('idQuestion');		
		$("#faq-question").text($(this).text());
		$("#faq-answer").text(answers[idQuestion]);
		var currentQuestionItem = $("#faq-list-tab").find('li.curent').removeClass('curent');		
		$(this).addClass('curent');	
	});


	

});