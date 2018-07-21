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
                    <div class="register-card__header">
                        <h3>ВАШИ ДАННЫЕ</h3>
                    </div>
                    <div class="login-card__body">
                        <div class="row justify-content-center">
                            <div class="col-md-8" id="form-errors">
                                
                            </div>
                        </div>    
                        <form method="POST" action="{{ route('store_user', $course->slug) }}" class="form-login" id="register-form">
                            @csrf
                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} form-login__input" name="name" value="{{ old('name') }}" required autofocus placeholder="ИМЯ">
                                    <div class="errors"></div>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif                                    
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="phone" type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} form-login__input" minlength="10" name="phone" value="{{ old('phone') }}" pattern="^[\+?0-9]+$" title="+ХХХХХХХХХХХ или ХХХХХХХХХХХ" required placeholder="ТЕЛЕФОН ДЛЯ WHATSAPP">
                                    <div class="errors"></div>
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">                
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-login__input" name="email" value="{{ old('email') }}" required placeholder="EMAIL">
                                    <div class="errors"></div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>                               
                            </div> 
                            <div class="form-description col justify-content-center">
                                <h3 class="form-description__title">Ваш заказ</h3>
                                <p>Абонемент: {!! strip_tags($course->name) !!}</p>
                                <p>Цена: {{ $course->price }} RUB</p>
                            </div> 
                            <div class="form-group row justify-content-center">           
                                <div class="checkbox">
                                    <label class="form-login__label form-login__label-terms row align-items-center">
                                        <input class="form-login__input-remember" type="checkbox" name="check_terms" {{ old('check_terms') ? 'checked' : '' }} required> Я подтверждаю <a style="color:#fff; text-decoration:underline;" target="_blank" href="/doc/offer5.pdf" > Пользовательское соглашение</a>
                                    </label>                                    
                                </div>
                                <div id="checkbox-errors" class="col-md-8">
                                        
                                </div>                                
                            </div>
                            <div class="row justify-content-center">
                                <div class="arrow"></div>
                                <button id="submit_form_register" type="submit" class="btn form-login__button">ОФОРМИТЬ ЗАКАЗ</button>
                            </div>                      
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>  
@endsection

@section('footer-scripts')    
    @parent  
    <script  src="{{asset('js/lib/jquery.validate.min.js') }}"></script> 
    <script>
        $(document).ready(function() {             
            $("#register-form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                        }, 
                    phone: {
                        required: true
                    },
                    email:  {                         
                        required: true,
                        email: true,
                        remote : {
                            url: '{{ route('validate_email_user') }}',
                            type: 'post',
                            data: {
                                email: function()
                                {
                                    return $('#register-form :input[name="email"]').val();
                                },
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                status_email: 'issetEmail',
                            },
                         
                        }
                    },
                    check_terms: {
                        required: true
                    }  

                },
                messages:{
                    name:{
                        required: "Заполнены не все поля",
                        minlength: "в поле должно быть минимум 2 символа",
                    },            
                    phone:{
                        required: "Заполнены не все поля"
                    },
                    email:{                        
                        required: "Заполнены не все поля",
                        email: "Необходимо указать Email",
                        remote: 'Пользователь с таким Email уже зарегистрирован'    
                    },   
                    check_terms: {
                        required: "Необходимо ознакомится и подтвердить Пользовательское соглашение"
                    }        
                },
                errorPlacement: function(error, element) {
                  

                    if (element.attr("name") == "name") {                        
                        $("#form-errors").empty();  
                        $("#form-errors").append(error);                                     
                    } 
                    
                    if (element.attr("name") == "phone") {                        
                        $("#form-errors").empty();  
                        $("#form-errors").append(error);                       
                    }   

                    if (element.attr("name") == "email") {                       
                        $("#form-errors").empty();  
                        $("#form-errors").append(error);                
                    }  

                    if (element.attr("name") == "check_terms") {
                        $('#checkbox-errors').empty();
                        $('#checkbox-errors').append(error);
                    }                                   
                }
            });
        })
    </script>
    <script  src="{{asset('js/auth.js') }}"></script> 
@endsection

@section('footer-modal')
    @parent    
@endsection