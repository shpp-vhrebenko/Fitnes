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
        <div class="row justify-content-center align-items-center">
            <div class="col-md-10">
                <div class="login-card">
                    <div class="login-card__header">Сброс пароля</div>

                    <div class="login-card__body">
                        <form method="POST" action="{{ route('password.request') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-login__input" name="email" value="{{ $email ?? old('email') }}" required autofocus placeholder="Логин">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-center"> 
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-login__input" name="password" required placeholder="Пароль">

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control form-login__input" name="password_confirmation" required placeholder="Подтвердите Пароль">
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">                           
                                <button type="submit" class="form-login__button">
                                    Сбросить пароль
                                </button>                               
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
    <script  src="{{asset('js/auth.js') }}"></script> 
@endsection

@section('footer-modal')
    @parent 
@endsection
