@extends('front/layout/message')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/home_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media_home_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
<section class="oplata">
    <div class="container oplata__container">
        <div class="row justify-content-center align-items-center ">
            <div class="col-md-6">
                <h4 class="oplata__header">ОЙ</h4>
                <h5 class="oplata__subheader">Вы не оплатили курс</h5>
                <p class="oplata_description">Во время оплаты что-то пошло не так проверьте свои реквизиты и попробуйте купить наш курс еще раз, на сайте  <b>http://gizerskaya.com</b></p>
                <hr>
                <div class="row justify-content-center">
                    <div class="arrow"></div>
                    <a href="{{route('index')}}" class="btn oplata__button">На главную</a>
                </div> 
            </div>
        </div>
    </div>
</section>  
@endsection