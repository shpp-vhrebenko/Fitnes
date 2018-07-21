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
                <div class="pay" id="typePay">                
                    <p class="pay__type">
                      <label class="pay__label">
                          <input class="pay__checkbox" type="radio" name="type_pay" value="walletone" checked> Wallet One Единая касса
                      </label>                       
                    </p>
                    <p>
                      <label class="pay__label" disabled>
                          <input class="pay_checkbox" type="radio" name="type_pay" value="paypal" disabled> PayPal
                      </label>  
                    </p>
                    <!-- <p class="pay__description">Wallet One Единая касса — платежный агрегатор</p> -->
                </div> 
                <hr>
                <div class="row justify-content-center">
                  <div class="arrow"></div>
                  <button  class="btn oplata__button btn-center " id="paySubmit">Подтвердить заказ</button>
                </div>                           
                <div class="row justify-content-center">
                    <div class="login-card__body">
                        <form method="post" action="https://wl.walletone.com/checkout/checkout/Index" class="form-succes-oplata" id="walletone-form">
                          @foreach ( $fields as $key => $value )
                            <input name="{{$key}}"    value="{{$value}}" type="hidden"/>  
                          @endforeach                         
                        </form>                       
                    </div>                    
                </div>
                <div class="row justify-content-center">
                     <div class="login-card__body">
                         <form method="post" action="{{route('pay_to_paypal')}}" class="form-succes-oplata" id="paypay-form">
                             {{ csrf_field() }}
                             <input name="amount" value="{{$price}}" type="hidden"/>
                         </form>                       
                     </div>                    
                </div>
            </div>
        </div>
    </div>
</section>  
@endsection

@section('footer-scripts') 
  <script>
    jQuery(document).ready(function($) {
      $("#paySubmit").click(function(){
          var radioValue = $("input[name='type_pay']:checked").val();          
          if(radioValue){
            switch (radioValue) {
              case 'walletone':
                $('#walletone-form').submit();
                break;
              case 'paypal':
                $('#paypay-form').submit();
                break;             
              default:
                $('#walletone-form').submit();
            }
          }
      });
    });
  </script>
@endsection