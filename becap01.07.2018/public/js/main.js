$( document ).ready(function() {
	// animation sidebar navigation
    $("#button_open_sidebar-menu").click(function(){     	 
		$("#sidebar-menu").stop(true , true).animate({right:"0"},500);				      
	});				    
	$("#button_close_sidebar-menu").click(function(){				         	
		$("#sidebar-menu").stop(true , true).animate({right:"-325"},500);				      
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


	// animation smooth scrolling of the anchor in the address bar
	$("a.faq-link").click(function(e) {
		e.preventDefault();
	})


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



	// review --------------------------------------------------------
	var textarr = [
		"Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня первый день как марафон закончился, а я начала день по прежнему с овсянки" , 
        "Девочки, марафон огонь, мне вообще сладкого не хочется, это первый раз за весь мой сознательный возраст (а мне вот скоро 28). И все тренировки переписала названия и буду делать дальше! Прям чувствую, как крепнет тело и уходят бока!" , 
        "Я уже заметно сдулась) И строго с питанием) Мне нужно было что-то такое, для мозга, чтобы он не сорвался. А отражение уже радует) Уже придумываю красивые луки на весну, с обновлённой фигурой. Спасибо Насте! Марафон просто" , 
        "Настя огромное спасибо за марафон,за мотивацию. Я полностью изменила своё отношение к еде и к образу жизни" , 
        "Настя, большое спасибо за марафон у меня ушли не только лишние места,так ещё и к удивлению мне и врачам у меня рука стала лучше после перелома,хотя врачи говорили что она навсегда останется такой(((( поясню... мне 3 года назад сломал бывший руку,и врачи сказали что больше до конца она сгибаться не будет,соответственно и разгибаться,но через силу благодаря тренировкам,она практически разогнулась это такое достижение для меня" , 
        "У меня - 2 кг. И тело подтянулось. Весь долго стоял и только когда я начала питаться хорошо и правильно, благодаря Насте, у меня пошли такие изменения классные. Я жду следующий марафон. Настя, спасибо огромное!!! И вам, девочки, вместе намного веселей и эффективней стройнеть. Я счастлива, что в голове все встало на свои места и теперь не хочется сладкого, а хочется и дальше оздоравливаться и чувствовать себя легко)" , 
        "Ещё до марафона начала бегать по пять километров в день и час в день плавать, также пошла на танцы. Но после тренировок процесс пошёл куда стремительнее) Так что результат фактически за месяц - минус 7 кг, с учетом того, что изначальный вес был не такой уж и большой - 49 (легкая кость+маленький рост). Так что ничего невозможного в жизни нет. Кстати, благодаря изменённому питанию, гормональный фонд наладился) волосы начали наконец-то расти, ногти укрепились. Спасибо огромное", 
        "Настя! Огромное тебе спасибо ,за прекрасные 2 недели ты открыла глаза на правите питание, думала Марофон закончится наемся сладкого ,а оказалось я в нем не так сильно и нуждаюсь . Могу спокойно смотреть на гамбургеры и т.д. Я очень рада своему результату буду держать себя и дальше в такой форме. Огромное спасибо",
        "А я вот только сейчас подумала : “как же нам всем повезло оказаться в этом марафоне в это время, ведь скоро 14е февраля А самый лучший подарок для наших половинок это мы в хорошей форме! Красивые, счастливые,  стройненькие и подтянутые!!!» Настя, спасибо большое тебе!!! Прошла только неделя, но результаты просто прекрасны! Самое главное не тянет согрешить с питанием, да и как бы к алкоголю всеравно) Фигурка с каждым днём все красивее и красивее, и мы просто светимся от счастья!!!Марафон 100% стоит своих денег!!  я безумно счастлива.",
        "У меня пока минус 1 кг, но я уже поняла, что это неважно, когда я вижу результат в зеркале, когда я надеваю старые джинсы, в которые не влазила моя попочка и прыгаю в них с разбегу и с лёгкостью  да это и не единственные изменения, у меня меняется от морального состояния до физического: уверенность в себе, появляется миллион целей, самосовершенствование Настя, я тебя обожаю за то, что вовремя нашла это все дело и начала строить свою фигуру и улучшать здоровье, с твоей помощью.",
        "Привет, буду участвовать в третьем марафоне, не пишу в группе потому что иногда не успеваю все прочитать, хотела сказать тебе огромное спасибо потому что никогда не представляла что такое правильно питаться, ты научила действительно многому, я горжусь своим телом, уже через неделю прорисовался рельеф пресса СПАСИБО.",
        "Привет, Насть, хочу сказать тебе огромное спасибо. Без тебя я бы так и не узнала как правильно питаться. Пока получилось вот что) Но это ещё не конец. С 1 января продолжаю дальше заниматься и правильно питаться. ( питаться теперь всегда буду правильно) Ни дня не могла без шоколада, поела сегодня утром, такая тяжесть от него, что вообще никогда не хочу его есть. Спасибо тебе.",
        "Настя, привет!  Хотела написать раньше, но с учетом загруженности только появилась возможность  Хочу сказать тебе огромное спасибо  благодаря двум марафонам и потом ещё месяцу твоих тренировок, я выработала Привычку к правильному питанию и к ежедневным тренировкам (в таком режиме уже с 15 января). Уже не сорваться) да и не хочется). Это уже образ жизни. Полюбила свой зал  (Полгода собиралась туда пойти и все никак) без тренеровок уже не могу, бегу после работы с большим удовольствием в зал или домой для выполнения основной тренировки С питанием все в строгом режиме, научилась правильно составлять свой ежедневный рацион, нашла много вкусных и полезных вкусняшек))  Я рада что успела в последние часы записаться к тебе на второй марафон, мне нужна была эта перезагрузка!)",
        "Спасибо большое, Настя, благодаря этому марафону смогла наконец-то похудеть. Даже после него продолжаю правильно питаться и ходить на спорт! Изменила свои пищевые привычки, на вредное вообще не тянет! В общем, очень рада)",
        "Очень понравилась программа тренировок, не думала, что можно так круто похудеть в домашних условиях! Нагрузка достаточно сильная, но быстро втягиваешься и уже не можешь без этого) Питание тоже понравилось, сытно и не пришлось голодать)"];

  
	var arrImgsReview = [
		"http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl14.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl13.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl12.png",
        "http://gizerskaya.com/wp-content/uploads/2018/03/girl11.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl10.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl9.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl8.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl7.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl6.png",
	    "http://gizerskaya.com/wp-content/uploads/2018/03/girl5.png",
        "http://gizerskaya.com/wp-content/uploads/2018/03/girl4.png",
        "http://gizerskaya.com/wp-content/uploads/2018/03/girl3-1.png",
        "http://gizerskaya.com/wp-content/uploads/2018/03/girl2-1.png",
        "http://gizerskaya.com/wp-content/uploads/2018/03/girl1-1.png"
	];
  
	var namearr = ["Ксения","Алена","Маша","Вика","Сабина","Ала","Настя","Саша","Валерия","Катя","Люда","Анжела","Оксана","Лена","Яна"];
	var agearr = ["22 года","26 лет","23 года","21 год","29 лет","21 год","27 лет","21 год","28 лет","24 года","21 год","25 лет","20 лет","23 года","27 лет"];
	var countReview = textarr.length;
	var countItem = Math.round(countReview/3);
	var indexItem = 0;  
	for (var i = 0; i < countItem ; i++) {
		var $carouselItem = $('<div>').addClass('carousel-item');
		if(i == 0) {
			$carouselItem.addClass('active');
		}
		var countReviewInItem = 3;
		if(countReview != 0) {
			if(countReview > countReviewInItem) {				
				for (var q = 0; q < countReviewInItem; q++) {
					// item testamonial
					var $testamonialItem = $('<div>').addClass('testamonial-item');

					// form title item testamonial
					var $testamonialItemTitle = $('<div>').addClass('testamonial-item__title');
					var $testamonialItemAva = $('<div>').addClass('testamonial-item__ava')
														.css("background-image", "url( '" + arrImgsReview[indexItem] + "' )");
					var $testamonialItemInfo = $('<div>').addClass('testamonial-item__info')
															.append($('<p>').addClass('title')
																			.append($('<b>').text(namearr[indexItem])))
															.append($('<p>').addClass('sub-title')
																			.append($('<span>').addClass('span-class')
																								.append($('<b>').text(agearr[indexItem]))));
					$testamonialItemTitle.append($testamonialItemAva)
											.append($testamonialItemInfo);

					// form testamonial item description
					var $testamonialItemDescription = $('<div>').addClass('testamonials-item__description');																			
													
					var $testamonialItemText = $('<div>').addClass('testamonials-item__text')
															.text(textarr[indexItem].substring(0, 150) + '...')	
															.append($('<a>').addClass('testamonials-item__open-modal')
																			.attr('data-id', indexItem)
																			.attr('data-target', '#testamonialModal')
																			.attr('data-toggle', 'modal')
																			.text('Подробнее'));
					$testamonialItemDescription.append($testamonialItemText);										
																													
					// form testamonial item
					$testamonialItem.append($testamonialItemTitle)
									.append($testamonialItemDescription);

					// append carusel-item 
					$carouselItem.append($testamonialItem);
					indexItem++;
				}
				$('#carouselTestamonials-inner').append($carouselItem);	

				// form testamonial indicator
				var $testamonialIndicator = $('<li>').attr('data-slide-to', i)
														.attr('data-target', '#carouselTestamonials')
														.text(i+1);
				if(i == 0) {
					$testamonialIndicator.addClass('active');	
				}
				$('#carouselTestamonialsIndicators').append($testamonialIndicator);
				// end form testamonial indicator

				countReview = countReview - countReviewInItem;
			} else if (countReview < countReviewInItem || countReview == countReviewInItem) {				
				for (var j = 0; j < countReview; j++) {
					// item testamonial
					var $testamonialItem = $('<div>').addClass('testamonial-item');

					// form title item testamonial
					var $testamonialItemTitle = $('<div>').addClass('testamonial-item__title');
					var $testamonialItemAva = $('<div>').addClass('testamonial-item__ava')
														.css("background-image", "url( '" + arrImgsReview[indexItem] + "' )");
					var $testamonialItemInfo = $('<div>').addClass('testamonial-item__info')
															.append($('<p>').addClass('title')
																			.append($('<b>').text(namearr[indexItem])))
															.append($('<p>').addClass('sub-title')
																			.append($('<span>').addClass('span-class')
																								.append($('<b>').text(agearr[indexItem]))));
					$testamonialItemTitle.append($testamonialItemAva)
											.append($testamonialItemInfo);

					// form testamonial item description
					var $testamonialItemDescription = $('<div>').addClass('testamonials-item__description');																			
													
					var $testamonialItemText = $('<div>').addClass('testamonials-item__text')
															.text(textarr[indexItem].substring(0, 150) + '...')	
															.append($('<a>').addClass('testamonials-item__open-modal')
																			.attr('data-id', indexItem)
																			.attr('data-target', '#testamonialModal')
																			.attr('data-toggle', 'modal')
																			.text('Подробнее'));
					$testamonialItemDescription.append($testamonialItemText);										
																													
					// form testamonial item
					$testamonialItem.append($testamonialItemTitle)
									.append($testamonialItemDescription);

					// append carusel-item 
					$carouselItem.append($testamonialItem);
					indexItem++;
				}
				$('#carouselTestamonials-inner').append($carouselItem);

				// form testamonial indicator
				var $testamonialIndicator = $('<li>').attr('data-slide-to', i)
														.attr('data-target', '#carouselTestamonials')
														.text(i+1);
				if(i == 0) {
					$testamonialIndicator.addClass('active');	
				}
				$('#carouselTestamonialsIndicators').append($testamonialIndicator);
				// end form testamonial indicator

				countReview = 0;
			} else {
				
			}
			
		}	
	}

		// open modal review
		$('#carouselTestamonials-inner').on('click','.testamonials-item__open-modal',function(e){
			var dataId = $(this).data('id');
			var curImg = arrImgsReview[dataId];
			var curName = namearr[dataId];
			var curAge = agearr[dataId];
			var curText = textarr[dataId];
			var $modalTestamonials = $('#testamonialModal');
			$modalTestamonials.find('.block-otziv__info img').attr('src',curImg);
			$modalTestamonials.find('.block-otziv__info .name').text(curName);
			$modalTestamonials.find('.block-otziv__info .age').text(curAge);
			$modalTestamonials.find('.block-otziv__text').text(curText);
		});
	// end review --------------------------------------------------------


	// faq ---------------------------------------------------------
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
    	var faqTextBox = $('.faq-text');
    	faqTextBox.attr('id', 'question_' + idQuestion);		
		$("#faq-question").text($(this).text());
		$("#faq-answer").text(answers[idQuestion]);
		var currentQuestionItem = $("#faq-list-tab").find('li.curent').removeClass('curent');		
		$(this).addClass('curent');	

	 	$('html,body').stop(false,true).animate({
			scrollTop: $("#faq").offset().top
		})	
	});

	// end faq ---------------------------------------------------------


	//animation results
	var arrImgResults = ["./img/result1.jpg", 
							"./img/result7.jpg", 
							"./img/result6.jpg", 
							"./img/result5.jpg", 
							"./img/result4.jpg",
							"./img/result3.jpg",
							"./img/result2.jpg"
						]
	var countTestam = arrImgResults.length - 1;		
	var counterResultG = 1;	
	var counterResultV = 1;		


	var blockWidthResult = parseInt(jQuery('.slick-dots li').css('width'));	
	var blockHeightResult = parseInt(jQuery('.slick-dots li').css('height'));
	var blockMarginLeftResult = parseInt(jQuery('.slick-dots li').css('margin-right'));	
	var blockMarginBottomResult = parseInt(jQuery('.slick-dots li').css('margin-bottom'));	
	var countTestamWidthLength = (arrImgResults.length * blockWidthResult) + (countTestam * blockMarginLeftResult);
	var countTestamHeightLength = (arrImgResults.length * blockWidthResult) + (countTestam * blockMarginBottomResult);

	var howplusResultG = blockWidthResult + blockMarginLeftResult;
	var howplusResultV = blockHeightResult + blockMarginBottomResult;	
	
	var currentScrollPositionResultG = 0;
	var currentScrollPositionResultV = 0;
	$('.slick-dots li').click(function(){  	
		if($(window).width() >= '992') {
			if(counterResultV < countTestam) {
				currentScrollPositionResultV = howplusResultV * $(this).index();					
				$(".slick-dots").animate({ scrollTop: currentScrollPositionResultV + "px" }, 1100);
				counterResultV = $(this).index() + 1;
			}	
		} else {
			if(counterResultG < countTestam) {
				currentScrollPositionResultG = howplusResultG * $(this).index();					
				$(".slick-dots").animate({ scrollLeft: currentScrollPositionResultG + "px" }, 1100);
				counterResultG = $(this).index() + 1;
			}	
		}					
	});

	$('.slick-next').click(function(e){	
		if($(window).width() >= '992') {
			if(counterResultV < countTestam) {
				if(counterResultV > 0) {
					currentScrollPositionResultV += howplusResultV;
				}						
				$(".slick-dots").animate({ scrollTop: currentScrollPositionResultV + "px" }, 1100);
				counterResultV++;
			} else {
				currentScrollPositionResultV = -currentScrollPositionResultV;			
				$(".slick-dots").animate({ scrollTop: currentScrollPositionResultV + "px" }, 800);
				currentScrollPositionResultV = 0;
				counterResultV = 0;
			}
		} else {
			if(counterResultG < countTestam) {
				if(counterResultG > 0) {
					currentScrollPositionResultG += howplusResultG;
				}						
				$(".slick-dots").animate({ scrollLeft: currentScrollPositionResultG + "px" }, 1100);
				counterResultG++;
			} else {
				currentScrollPositionResultG = -currentScrollPositionResultG;			
				$(".slick-dots").animate({ scrollLeft: currentScrollPositionResultG + "px" }, 800);
				currentScrollPositionResultG = 0;
				counterResultG = 0;
			}
		}	
				
	});

	$('.slick-prev').click(function(e){	
		if($(window).width() >= '992') {
			if(counterResultV > 0 && currentScrollPositionResultV > 0){
				currentScrollPositionResultV -= howplusResultV;			
				$(".slick-dots").animate({ scrollTop: currentScrollPositionResultV + "px" }, 1100);
				counterResultV--;
			} else {
				currentScrollPositionResultV = countTestamHeightLength;			
				$(".slick-dots").animate({ scrollTop: currentScrollPositionResultV + "px" }, 800);			
				counterResultV = countTestam;
			}
		} else {
			if(counterResultG > 0 && currentScrollPositionResultG > 0){
				currentScrollPositionResultG -= howplusResultG;			
				$(".slick-dots").animate({ scrollLeft: currentScrollPositionResultG + "px" }, 1100);
				counterResultG--;
			} else {
				currentScrollPositionResultG = countTestamWidthLength;			
				$(".slick-dots").animate({ scrollLeft: currentScrollPositionResultG + "px" }, 800);			
				counterResultG = countTestam;
			}
		}	
		
	});


	//end animation results

	

});