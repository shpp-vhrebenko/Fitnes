@extends('front/layout/front')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/home_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media_home_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
    <section class="main" id="main">
        <div class="main__background-overlay"></div>
        <div class="main__container container-fluid">            
            <div class="main__text_1 row">                
                <div class="col-xl-4 col-lg-4"></div>
                <div class="col-xl-3 col-lg-3"></div>
                <div class="col-xl-5 col-lg-5 col-md-12">
                    <p>ANASTASIA</p>
                </div>                
            </div>
            <div class="main__text_2 row"> 
                <div class="col-md-12">
                    <p><strong>GIZER<span class="lete-s">S</span><span class="lete-k" >K</span>KAYA</strong></p>
                </div>               
                
            </div>             
            <div class="container__text row">                
                <div class="col-xl-6 col-lg-6 col-md-12 main__text_3">
                    <p class="elementor-heading-title elementor-size-xxl animated animated-slow fadeIn">ОНЛАЙН ФИТНEC ДЛЯ ЖЕНЩИН</p>
                </div>
                <div class="col-xl-3 col-lg-3 main__text_4">
                    <p id='krasota-za-30-dney'>КРАСОТА<br/>ЗА 30 ДНЕЙ</p> 
                </div>
                <div class="col-xl-3 col-lg-3 main__button animated bounceIn">         
                    <a href="#price" id="button-curs1" class="button-curs animated  slideInRight animated-slow anchor-link" role="button">КУПИТЬ КУРС</a>                       
                </div>                
            </div>                
        </div>        
    </section>
    <section class="about" id="about">
        <div class="container about__container">
            <div class="row">
                <div class="col-md-12 col-xl-7 col-lg-7 about__description">
                    <div class="about__description-block">
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
                </div>                
                <div class="col-md-12 col-xl-5 col-lg-5 about__populated" >
                    <div class="about__background-overlay"></div>
                    <div class="play-btn">                    
                        <img  src="img/playbtn.png"  alt="#"  />                            
                    </div>                
                </div>
            </div>
        </div>        
    </section>
    <section class="price" id="price">
        <div class="container price__container">
            <div class='price__header'>
                <p class="price__title"><b>Выберите</b></p>
                <p class="price__sub-title">Направление курса</p>
            </div>       
            <div class="price__box row">
                @if(isset($marathon))
                <div class="col-sm-12 col-md-12 col-lg-6 price-item" id="price_marafon">
                    <h3 class="price-item__title">{!! $marathon->name !!}</h3>
                    <p class="price-item__subtitle">ВСЕГО ЗА {{$marathon->price}} {{ Lang::choice('messages.price', $marathon->price) }} ВЫ ПОЛУЧИТЕ:</p>
                    <div class="price-item__icon" style="background-image: url('./uploads/icons/{{$marathon->icon}}');">                        
                    </div>
                    @if(isset($marathon->description))
                    {!! $marathon->description !!} 
                    @endif
                    <div class="price-item__val">
                        <p>{{$marathon->price}}</p>
                        <p>{{ Lang::choice('messages.price', $marathon->price) }}</p>
                    </div>
                    <a class="price-item__button" href="{{route('register_user', $marathon->slug)}}">
                        купить курс
                    </a>                    
                    <p class="price-item__message">{{$marathon_message}}</p>
                </div>
                @endif
                @if(isset($course))
                <div class="col-sm-12 col-md-12 col-lg-6 price-item" id="price_curs">
                    <h3 class="price-item__title">{!! $course->name !!}</h3>
                    <p class="price-item__subtitle">ВСЕГО ЗА {{$course->price}} {{ Lang::choice('messages.price', $course->price) }} РУБЛЕЙ ВЫ ПОЛУЧИТЕ:</p>
                    <div class="price-item__icon" style="background-image:url('./uploads/icons/{{$course->icon}}')">
                        
                    </div>
                    @if(isset($course->description))
                    {!! $course->description !!} 
                    @endif                   
                    <div class="price-item__val">
                        <p>{{$course->price}}</p>
                        <p>{{ Lang::choice('messages.price', $course->price) }}</p>
                    </div>
                    <a class="price-item__button" href="{{route('register_user', $course->slug)}}">
                        купить курс
                    </a>
                    <p class="price-item__message"></p>
                </div>
                @endif
            </div> 
        </div>
                
    </section>
    <section class="testamonials" id="review">
        <div class="container-fluid testamonials__container">
            <div class="row">
                <div class="col-md-12 col-lg-5 testamonials__title">
                    <h3>ОТЗЫВЫ</h3>
                    <p>234 ОТЗЫВА</p>
                </div>
                <div id="carouselTestamonials" class="testamonials__carousel carousel vert slide col-md-12 col-lg-7" data-ride="false">
                    <div class="row">
                        <div class="col-12 order-2 col-md-12 col-lg-1 order-lg-1 testamonials__indicators">
                             <a class="carousel-control-prev" href="#carouselTestamonials" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                            </a>
                            <ol class="carousel-indicators carousel-indicators-numbers" id="carouselTestamonialsIndicators">                                
                            </ol>
                            <a class="carousel-control-next" href="#carouselTestamonials" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="col-12 order-1 col-md-12 col-lg-11 order-lg-2 testamonials__content">
                            <div class="carousel-inner" id="carouselTestamonials-inner">                             
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>        
    </section>
    <section class="results" id="results">
        <div class="container results-container">
            <div class="row">
                <div class="col-md-12 col-lg-2 col-xl-3 results-title__box">
                    <div class="results__title">
                        <p><span class="results__header"><b>РЕЗУЛЬТАТЫ</b></span><br>
                        <span>С НАМИ ТРЕНИРУЮТСЯ БОЛЕЕ</span><br>
                        <span><strong>5 000</strong> ДЕВУШЕК</span>
                        </p>
                    </div>                    
                </div>
                <div class="col-md-12 col-lg-10 col-xl-9 results-slider__box">
                    <div id="sliderResults" class="slider-results">
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result1.jpg">                   
                        </div> 
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result2.jpg">                   
                        </div>   
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result3.jpg">                   
                        </div>   
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result4.jpg">                   
                        </div>
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result5.jpg">                   
                        </div>
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result6.jpg">                   
                        </div>
                        <div class="results-item">
                            <div class="content-overlay"></div>                                  
                            <img class="results-item__img img-responsive" src="./img/result7.jpg">                   
                        </div>                                                                                         
                    </div>
                </div>                
                
            </div>
        </div>   
    </section>  
    <section class="faq" id="faq">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-lg-7 col-xl-7 offset-lg-1 offset-xl-1 faq__text">
                    <div class="faq-text" id="faq-text">
                        <p class="faq-text__title"><strong>FAQ</strong></p>
                        <p class="faq-text__subtitle"><strong id="faq-question">Что входит в стоимость программы?
                        </strong></p>
                        <p class="faq-text__description" id="faq-answer">
                            В стоимость программы входит курс тренировок на указанный срок, программа питания с рецептами, раздел с интересными статьями и советами, моя поддержка в общем чате 24/7, а также связь с куратором программы.
                        </p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 col-xl-4 faq__list" >
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
    <section class="contacts" id="contacts">
        <div class="contacts__bg-overlay"></div>
        <div class="container-fluid ">
            <div class="row contacts-container">
                <div class="col-md-12 col-lg-12 col-xl-7 offset-xl-4 contacts__content">
                    <div class="contacts__first-text">
                        <p class="contacts__title">ОСТАЛИСЬ</p>
                        <p class="contacts__subtitle" >
                            <strong>
                               ВОПРОСЫ?
                            </strong>
                        </p>
                    </div>
                    <p class="contacts__second-text"><b>НАПИШИТЕ МНЕ И Я СВЯЖУСЬ С ВАМИ</b></p>
                    <a class="contacts__mail-link" href="mailto:gizerskaya.fitness@mail.ru">gizerskaya.fitness@mail.ru</a>           
                    <p class="contacts__third-text"><b>СВЯЗАТЬСЯ СО МНОЙ</b></p>
                    <button id="contacts-button-w" class="contacts__button" data-toggle="modal" data-target="#modalContacts">НАПИСАТЬ ПИСЬМО</button>
                </div>
                <div class="col-md-12 contacts__content-md">
                    <p class="contacts__third-text"><b>СВЯЗАТЬСЯ СО МНОЙ</b></p>
                    <button id="contacts-button-d" class="contacts__button" data-toggle="modal" data-target="#modalContacts">НАПИСАТЬ<br>ПИСЬМО</button>
                </div>
            </div>            
        </div>
    </section>
@endsection


@section('footer-scripts')    
    @parent    
    <script  src="{{asset('js/lib/slick.min.js') }}"></script>   
    <script  src="{{asset('js/main.js') }}"></script> 
@endsection

@section('footer-modal')
    @parent 
    <div class="modal fade" id="testamonialModal" tabindex="-1" role="dialog" aria-labelledby="testamonialModalLabel" aria-hidden="true">
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

    <div class="modal fade" id="modalContacts" tabindex="-1" role="dialog" aria-labelledby="modalContactsTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-block-contacts" role="document">
        <div class="modal-content block-contacts">
            <button type="button" class="close block-contacts__close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">X</span>
            </button>
            <div class="modal-body">
                <h5 class="block-contacts__header">Написать письмо</h5>
                <form action="#" class="form-contacts">
                    <div class="form-group">
                        <label for="name">имя</label>
                        <input type="text" class="form-control" id="name"  placeholder="" required="true">             
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="" required="true">                        
                    </div>
                    <div class="form-group form-contacts__text">
                        <label for="textMessage">текст сообщения</label>
                        <textarea class="form-control" id="textMessage" rows="4"></textarea>
                    </div>
                    <button class="form-contacts__button" id="submitFormContacts">отправить</button>
                </form>
            </div>
          
        </div>
      </div>
    </div>  
@endsection