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
    <section class="main">
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
</main>
@endsection

@section('footer--scripts')
    @parent
@endsection