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
<section class="oplata oplata-confirm">
    <div class="container oplata__container">
        <div class="row justify-content-center align-items-center ">
            <div class="col-md-6">
                <h4 class="oplata__header">ВАШ ЗАКАЗ</h4>
                <h5 class="oplata__subheader">{!! strip_tags($course->name) !!}</h5>
                <p class="oplata__subheader">ИТОГО: <span class="course-price"><span class="fa fa-rub"></span> {{$price}}</span></p>                  
                <div class="pay">                
                    <p class="pay__type">
                        Wallet One Единая касса
                    </p>
                    <p class="pay__description">Wallet One Единая касса — платежный агрегатор</p>
                </div> 
                <hr>             
                <div class="row justify-content-center">
                    <div class="login-card__body">
                        <form method="post" action="https://wl.walletone.com/checkout/checkout/Index" class="form-succes-oplata">
                          @foreach ( $fields as $key => $value )
                            <input name="{{$key}}"    value="{{$value}}" type="hidden"/>  
                          @endforeach                          
                          <input type="submit" value="Подтвердить заказ" class="btn oplata__button btn-center "/>
                        </form>                       
                    </div>                    
                </div> 
            </div>
        </div>
    </div>
</section>  
@endsection