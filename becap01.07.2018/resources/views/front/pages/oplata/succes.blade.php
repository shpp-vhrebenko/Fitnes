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
                <h4 class="oplata__header">Спасибо</h4>
                <h5 class="oplata__subheader">Ваш заказ принят</h5>
                <p class="oplata_description">На Вашу почту отправлено письмо с логином и паролем для входа в аккаунт. Пожалуйста проверте почту и авторизуйтесь в личном кабинете. На сайте <a class="oplata__link" href="{{route('index')}}">http://gizerskaya.com</b></p>
                <hr>
                <div class="row justify-content-center">
                    <div class="arrow"></div>
                    <a href="{{route('my-account')}}" class="btn oplata__button">Личный кабинет</a>
                </div> 
            </div>
        </div>
    </div>
</section>  
@endsection