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
                        <form method="POST" id="reset-password-form" action="{{ route('password.email') }}" class="form-login">
                            @csrf
                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control form-login__input" name="email" value="{{ old('email') }}" required placeholder="ВВЕДИТЕ ВАШ EMAIL">                             
                                </div>
                            </div>

                            <div class="form-group row justify-content-center">
                                <div class="col-md-8">
                                    <a class="form-login__button pull-left" href="{{ route('login') }}">
                                        Назад
                                    </a>
                                    <button type="submit" class="form-login__button pull-right">
                                        Отправить
                                    </button>
                                </div>                                
                            </div>
                        </form>
                    </div>
                    <div class="alerts" id="alerts">
                        @if (session('success'))
                            <div class="alert alert-success alert-success_custom">
                                {{ session('success') }}
                            </div>
                        @endif                        
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
            $("#reset-password-form").validate({
                rules: {                    
                    email:  {                         
                        required: true,
                        email: true,
                        remote : {
                            url: '{{ route('validate_email_user') }}',
                            type: 'post',
                            data: {
                                email: function()
                                {
                                    return $('#reset-password-form :input[name="email"]').val();
                                },
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                status_email: 'noIssetEmail',
                            },
                         
                        }
                    }
                },
                messages:{                   
                    email:{                        
                        required: "Поле не заполнено",
                        email: "Необходимо указать Email",
                        remote: 'Мы не можем найти пользователя с этим адресом электронной почты.'    
                    }       
                },
                errorPlacement: function(error, element) { 
                    if (element.attr("name") == "email") {
                        $("#alerts").empty();
                        error.appendTo($("#alerts")); 
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
