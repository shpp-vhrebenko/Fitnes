@extends('auth/layout/front')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/auth/auth_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/media_auth_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
<section class="auth">
    <div class="container auth__container">
        <div class="row justify-content-center align-items-center ">
            <div class="col-md-10">
                <div class="login-card">
                    <div class="register-card__header card-header">
                        <h3 class="card-header__title">ВАШ ЗАКАЗ</h3>
                        <p class="card-header__description">{!! $course->name !!}</p>
                        <p class="card-header__price">ИТОГО: <span class="fa fa-rub"></span>  {{$price}}</p>
                    </div>

                    <div class="login-card__body">
                        <form method="post" action="https://wl.walletone.com/checkout/checkout/Index" class="form-succes-oplata">
                          <input name="WMI_MERCHANT_ID"    value="117327853980" type="hidden"/> 
                          <input name="WMI_PAYMENT_AMOUNT" value="{{$price}}" type="hidden"/>
                          <input name="WMI_CURRENCY_ID"    value="643" type="hidden"/>  
                          <input name="WMI_DESCRIPTION"    value="Оплата Курса {{strip_tags($course->name)}}" type="hidden"/>
                          <input name="WMI_SUCCESS_URL"    value="{{route('success_oplata')}}" type="hidden"/>
                          <input name="WMI_FAIL_URL"       value="{{route('error_oplata')}}" type="hidden"/>
                          <input type="hidden" name="WMI_EXPIRED_DATE" value="{{$date}}">
                          <input name="WMI_SIGNATURE" value="{{$signature}}" type="hidden">
                          <input type="submit" value="Подтвердить" class="btn form-login__button btn-center "/>
                        </form>
                       <!--  <form method="POST" action="{{ route('oplata_course') }}" class="form-login" id="register-form">
                           @csrf
                                        
                          
                           <div class="form-group row justify-content-center">          
                               <div class="checkbox">
                                   <label class="form-login__label form-login__label-terms row align-items-center">
                                       <input class="form-login__input-remember" type="checkbox" name="check_course" {{ old('check_course') ? 'checked' : '' }}> Я подтверждаю оплату
                                   </label>
                               </div>                                
                           </div>
                           <div class="row justify-content-center">
                               <div class="arrow"></div>
                               <button id="submit_form_register" type="submit" class="btn form-login__button">ОПЛАТИТЬ ЗАКАЗ</button>
                           </div>                      
                       </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>  
@endsection

@section('footer-scripts')    
    @parent   
@endsection

@section('footer-modal')
    <div class="modal fade" id="modalContacts" tabindex="-1" role="dialog" aria-labelledby="modalContactsTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-block-contacts" role="document">
        <div class="modal-content block-contacts">
            <button type="button" class="close block-contacts__close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">X</span>
            </button>
            <div class="modal-body">
                <form action="#" class="form-contacts">
                    <div class="form-group">
                        <label for="user-name">имя</label>
                        <input type="text" class="form-control" id="user-name"  placeholder="" required="true">             
                    </div>
                    <div class="form-group">
                        <label for="user-email">Email</label>
                        <input type="email" class="form-control" id="user-email" placeholder="" required="true">                        
                    </div>
                    <div class="form-group form-contacts__text">
                        <label for="user-textMessage">текст сообщения</label>
                        <textarea class="form-control" id="user-textMessage" rows="4"></textarea>
                    </div>
                    <button class="form-contacts__button" id="submitFormContacts">отправить</button>
                </form>
            </div>
          
        </div>
      </div>
    </div>  
@endsection