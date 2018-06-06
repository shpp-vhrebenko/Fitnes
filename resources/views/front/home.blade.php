@extends('front/layout/front')

@section('header-styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/animate.css@3.5.2/animate.min.css">             
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,600,700,900&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />   
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">    
    @parent   
@endsection

@section('header-scripts')
   
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
                    <img width="324" height="264" src="img/playbtn-324x308-копия.png"  alt="#" sizes="(max-width: 324px) 100vw, 324px" />                            
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
</main>
@endsection

@section('footer--scripts')
    @parent
@endsection