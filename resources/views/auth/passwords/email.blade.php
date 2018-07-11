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
                    <div class="login-card__header">ЗАБЫЛИ ПАРОЛЬ?</div>

                    <div class="login-card__body">
                       

                        <form method="POST" action="{{ route('password.email') }}" class="form-login">
                            @csrf
                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-login__input" name="email" value="{{ old('email') }}" required placeholder="ВВЕДИТЕ ВАШ EMAIL">
                                    
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <a class="form-login__button pull-left" href="{{ URL::previous() }}">
                                        Назад
                                    </a>
                                    <button type="submit" class="form-login__button pull-right">
                                        Отправить
                                    </button>
                                </div>                                
                            </div>
                        </form>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success alert-success_custom">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->has('email'))
                        <div class="alert alert-danger alert-danger_custom">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
