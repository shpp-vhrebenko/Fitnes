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
                    <div class="login-card__header">ЛИЧНЫЙ КАБИНЕТ</div>

                    <div class="login-card__body">
                        <form method="POST" action="{{ route('login') }}" class="form-login">
                            @csrf
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-login__input" name="email" value="{{ old('email') }}" required autofocus placeholder="Логин">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-login__input" name="password" required placeholder="Пароль">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">                       
                                <button type="submit" class="form-login__button">
                                    Войти
                                </button>                               
                            </div>

                            <div class="form-group row justify-content-center">                       
                                <div class="checkbox">
                                    <label class="form-login__label row align-items-center">
                                        <input class="form-login__input-remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> ЗАПОМНИТЬ ПАРОЛЬ
                                    </label>
                                </div>                                
                            </div>

                            <div class="form-group row justify-content-center">
                                <a class="btn btn-link form-login__button-link" href="{{ route('password.request') }}">
                                    ЗАБЫЛИ ПАРОЛЬ?
                                </a>
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