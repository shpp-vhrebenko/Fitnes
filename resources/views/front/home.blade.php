@extends('front/layout/front')

@section('header-styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/animate.css@3.5.2/animate.min.css">             
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,600,700,900&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />   
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">   
    @parent   
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
<main role="main">
    <section class="main" id="main">
        <div class="elementor-background-overlay"></div>
        <div class="main__text-box">            
            <div class="main__text_1 row">                
                <div class="col-md-4"></div>
                <div class="col-md-3"></div>
                <div class="col-md-5">
                    <p class="elementor-heading-title elementor-size-xxl">ANASTASIA</p>
                </div>                
            </div>
            <div class="main__text_2">
                <p><strong>GIZER<strong style='z-index:99; position:absolute;'>S</strong><text style='opacity:0;'>K</text>KAYA</strong></p>
            </div>             
            <div class="container-fluid container__text">
                <div class="row">
                    <div class="col-md-6 main__text_3">
                        <p class="elementor-heading-title elementor-size-xxl animated animated-slow fadeIn">ОНЛАЙН ФИТНEC ДЛЯ ЖЕНЩИН</p>
                    </div>
                    <div class="col-md-3 main__text_4">
                        <p id='krasota-za-30-dney'>КРАСОТА<br/>ЗА 30 ДНЕЙ</p> 
                    </div>
                    <div class="col-md-3 main__button animated bounceIn">                         
                        <a href="#gotop" id="button-curs1" class="button-curs1 animated  slideInRight animated-slow" role="button">КУПИТЬ КУРС</a>                                 
                    </div>
                </div>
            </div>                
        </div>        
    </section>
    <section class="about" id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-1">
                    <p class='gizerhash'>#GIZERSKAYA</p>
                    <p class="about-progect"><b>О ПРОЕКТЕ</b></p>               
                    <p class="good-sam">ЗДРАВСТВУЙТЕ, Я АНАСТАСИЯ ГИЗЕРСКАЯ</p>
                    <br>
                    <p class="second-block">Моя программа - это выдержка всего лучшего из мира спорта и правильного питания. Более 7 лет активной спортивной практики, изучения физиологии и диетологии дали мне возможность создать такой комплекс упражнений, который поможет тебе привести себя в форму всего лишь за 30 дней. Теперь все в твоих руках! </p>
                    <br>
                    <p class="second-block">
                    Я помогу сбросить лишние килограммы, правильно настроить свой рацион, сделать полезную еду вкусной, а вас настоящими красавицами. </
                    </p>
                    <br/>
                    <p class="second-block">
                    Мою программу прошли уже более 500 девушек, получив по окончанию впечатляющий результат.
                    </p>
                    <br/>
                    <p class="second-block">             
                    Присоединяйся и стань лучше прямо сейчас!
                    </p>
                </div>                
                <div class="col-md-5 about__populated" >
                    <div class="elementor-background-overlay"></div>
                    <div class="play-btn">                    
                        <img width="324" height="264" src="img/playbtn.png"  alt="#" sizes="(max-width: 324px) 100vw, 324px" />                            
                    </div>                
                </div>
            </div>
        </div>        
    </section>
    <section class="price" id="price">
        <div class='price__header'>
            <p class="price__title"><b>Выберите</b></p>
            <p class="price__sub-title">Направление курса</p>
        </div>       
        <div class="price__box row">
            <div class="col-md-6 price-item" id="price_marafon">
                <h3 class="price-item__title">МАРАФОН<br>ОБЩЕЕ ПОХУДЕНИЕ</h3>
                <p class="price-item__subtitle">ВСЕГО ЗА 1990 РУБЛЕЙ ВЫ ПОЛУЧИТЕ:</p>
                <div class="price-item__icon" style="background-image: url('./img/weight-clock-icon.png');">
                    
                </div>
                <ul class="price-item__list">
                    <li>Программу тренировок на 2 недели</li>
                    <li>План питания с подробными рецептами</li>
                    <li>Чат с участницами и мной в WhatsApp</li>
                    <li>Моя поддержка 24/7</li>
                    <li>Раздел с полезными советами статьями</li>
                </ul>
                <div class="price-item__val">
                    <p>1990</p>
                    <p>рублей</p>
                </div>
                <a class="price-item__button">
                    купить курс
                </a>
                <p class="price-item__message">Cтарт марафона 11 июня</p>
            </div>
            <div class="col-md-6 price-item" id="price_curs">
                <h3 class="price-item__title">СТРОЙНОЕ ТЕЛО<br>ЗА 30 ДНЕЙ</h3>
                <p class="price-item__subtitle">ВСЕГО ЗА 2990 РУБЛЕЙ ВЫ ПОЛУЧИТЕ:</p>
                <div class="price-item__icon" style="background-image:url('./img/polotence-icon.png')">
                    
                </div>
                <ul class="price-item__list">
                    <li>Программу тренировок на месяц</li>
                    <li>План питания с подробными рецептами</li>
                    <li>Чат с участницами и мной в WhatsApp</li>
                    <li>Моя поддержка 24/7</li>
                    <li>Раздел с полезными советами статьями</li>
                </ul>
                <div class="price-item__val">
                    <p>2990</p>
                    <p>рублей</p>
                </div>
                <a class="price-item__button">
                    купить курс
                </a>
                <p class="price-item__message"></p>
            </div>
        </div>         
    </section>
    <section class="testamonials" id="testamonials">
        <div class="container">
            <div class="row">
                <div class="col-md-5 testamonials__title">
                    <h3>ОТЗЫВЫ</h3>
                    <p>234 ОТЗЫВА</p>
                </div>

                <div id="carouselTestamonials" class="carousel vert slide col-md-7" data-ride="false">
                    <div class="row">
                        <div class="col-md-2 testamonials__indicators">
                             <a class="carousel-control-prev" href="#carouselTestamonials" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                            </a>
                            <ol class="carousel-indicators carousel-indicators-numbers">
                                <li data-target="#carouselTestamonials" data-slide-to="0" class="active">1</li>
                                <li data-target="#carouselTestamonials" data-slide-to="1">2</li>
                                <li data-target="#carouselTestamonials" data-slide-to="2">3</li>
                                <li data-target="#carouselTestamonials" data-slide-to="3">4</li>
                                <li data-target="#carouselTestamonials" data-slide-to="4">5</li>
                            </ol>
                            <a class="carousel-control-next" href="#carouselTestamonials" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="col-md-10 testamonials__content">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="carousel-item">
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="carousel-item">
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>                                    
                                </div> 
                                <div class="carousel-item">
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>                                    
                                </div> 
                                <div class="carousel-item">
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>
                                    <div class="testamonial-item">
                                        <div class="testamonial-item__title">
                                            <div class="testamonial-item__ava" style="background-image:url(http://gizerskaya.com/wp-content/uploads/2018/03/girl15.png);">    
                                            </div>
                                            <div class="testamonial-item__info">
                                                <p class="title"><b>Ксения</b></p>
                                                <p class="sub-title"><span class="span-class" style="display:block; float:left;"><b>22 года</b></span></p>
                                            </div>
                                        </div>
                                        <div class="testamonials-item__description">
                                            <svg width="26" height="20" xmlns="http://www.w3.org/2000/svg" style="position:absolute; top:-13px; left:32px;"><g><title>Layer 1</title><path id="svg_2" d="m0.750001,18.749998l12.000001,-17.999999l12.000001,17.999999l-24.000003,0z" fill-opacity="null" fill="#a3748a"></path></g></svg>
                                            <p class="testamonials-item__text">Я тоже скину свой результат! Настя спасибо за марафон, я уже писала что к еде начала относиться как к средству существования, не более. Вот сегодня пе...<a class="testamonials-item__open-modal" data-id="0" data-target="#exampleModal" data-toggle="modal" ><span>Подробнее</span></a></p>
                                        </div>
                                    </div>                                    
                                </div>                                                                
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>        
    </section>
    <section class="results" id="results">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="results__title">
                        <p><span class="results__header"><b>РЕЗУЛЬТАТЫ</b></span><br>
                        <span>С НАМИ ТРЕНИРУЮТСЯ БОЛЕЕ</span><br>
                        <span><strong>5 000</strong> ДЕВУШЕК</span>
                        </p>
                    </div>                    
                </div>
                <div class="col-md-9">
                    <div id="sliderResults" class="slider-results">
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result1.jpg);"></div>
                        </div>
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result2.jpg);"></div>
                        </div>  
                        <div class="results-item">
                            <div class="content-overlay"></div>                                       
                            <div class="results-item__img" style="background-image:url(./img/result3.jpg);"></div>
                        </div>  
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result4.jpg);"></div>
                        </div>  
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result5.jpg);"></div>
                        </div>  
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result6.jpg);"></div>
                        </div>  
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <div class="results-item__img" style="background-image:url(./img/result7.jpg);"></div>
                        </div>              
                    </div>
                </div>                
                
            </div>
        </div>   
    </section>  
    <section class="faq" id="faq">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-1 faq__text">
                    <div class="faq-text" id="faq-text">
                        <p class="faq-text__title"><strong>FAQ</strong></p>
                        <p class="faq-text__subtitle"><strong id="faq-question">Что входит в стоимость программы?
                        </strong></p>
                        <p class="faq-text__description" id="faq-answer">
                            В стоимость программы входит курс тренировок на указанный срок, программа питания с рецептами, раздел с интересными статьями и советами, моя поддержка в общем чате 24/7, а также связь с куратором программы.
                        </p>
                    </div>
                </div>
                <div class="col-md-5 faq__list" >
                    <div class="overflow-bg"></div>
                    <ul id="faq-list-tab" class="faq-list">    
                        <li data-id-question="0" class="curent"><a>Что входит в стоимость программы?</a></li>
                        
                        <li data-id-question="1"><a>Какие ограничения по возрасту?</a></li>
                        
                        <li data-id-question="2"><a>Смогу ли я выполнять тренировки дома?</a></li>
                        
                        <li data-id-question="3"><a>Могу ли я участвовать в программе, если у меня есть ограничения по здоровью?</a></li>
                        
                        <li data-id-question="4"><a>Останется ли у меня доступ к программе, после окончания курса?</a></li>
                        
                        <li data-id-question="5"><a>Можно ли тренироваться, если я беременна/нахожусь на ГВ?</a></li>
                        
                        <li data-id-question="6"><a>Как скоро я увижу результат тренировок?</a></li>
                       
                        <li data-id-question="7"><a>Если я не употребляю в пищу какие-то определенные продукты? </a></li>
                        
                        <li data-id-question="8"><a>Есть ли в программе меню для вегетарианцев? </a></li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </section>  
</main>
@endsection

@section('footer-scripts')    
    @parent    
    <script  src="{{asset('js/lib/slick.min.js') }}"></script>   
    <script  src="{{asset('js/main.js') }}"></script> 
@endsection

@section('footer-modal')
    @parent 
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal_block-otziv" role="document">
            <div class="modal-content block-otziv">
                
                <button type="button" class="close block-otziv__close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">X</span>
                    </button>
                <div class="modal-body">
                    <div class="block-otziv__info">
                        <div class="foto-otziv">
                            <img src="http://gizerskaya.com/wp-content/uploads/2018/03/girl12.png">
                        </div>
                        <div class="name">Вика</div>
                        <span class="age">21 год</span>
                    </div>
                    <div class="block-otziv__text">Настя огромное спасибо за марафон,за мотивацию. Я полностью изменила своё отношение к еде и к образу жизни</div> 
                </div>  
                           
            </div>            
        </div>
    </div>    
@endsection