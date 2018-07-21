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
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                            @if (count($errors) > 0)                                    
                                @foreach($errors->all() as $error)
                                <div class="alert alert-danger alert-danger_custom" role="alert">
                                    {{$error}}
                                </div>
                                @endforeach                                  
                            @endif 
                            </div>                               
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="form-login">
                            @csrf
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control form-login__input" name="email" value="{{ old('email') }}" required autofocus placeholder="Логин">                                    
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control  form-login__input" name="password" required placeholder="Пароль">                                   
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
    <script  src="{{asset('js/auth.js') }}"></script>    
@endsection

@section('footer-modal')
    @parent 
@endsection