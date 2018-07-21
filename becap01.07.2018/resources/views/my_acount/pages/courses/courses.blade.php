@extends('my_acount/layout/front')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/my_account/home_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my_account/media_home_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')	
	<div class="container-fluid">
		<header class="row courses-header">
			<h4 class="courses-header__title col-sm-12">{{$page_title}}</h4>
			<div class="courses-header__description col-sm-12">{{$description}}</div>
		</header>
		<div class="row courses-content">
			<form action="{{ route('by_course') }}" method="post" class="courses-form">
        	{{ csrf_field() }}
	        	<div class="row">                          
	                <div class="form-group col-sm-12" >                                           
	                    <select class="form-control courses-form__input" id="week" name="course_slug" required="true">  
	                        <option value="">-- Не выбрано --</option>   
	                        @if(isset($courses) && count($courses) > 0)
	                            @foreach($courses as $course)                                        
	                                <option value="{{ $course->slug }}">{!! $course->name !!}</option>
	                            @endforeach
	                        @endif        
	                    </select> 
	                    <div class="flash-message">
					      	@if (Session::has('error'))
                        	<div class="alert alert-danger alert-danger_custom" role="alert">
                            	{{ Session::get('error') }}
                        	</div>
                   			@endif 
					    </div>                                 
	                </div>  
	                        
	            </div>
	            <button type="submit" class="btn btn-default courses-form__button">Выбрать</button> 
        	</form>
		</div> 
	</div>	  
@endsection


@section('footer-scripts')      
    <script  src="{{asset('js/my_account.js') }}"></script>
@endsection

@section('footer-modal')
    @parent      
@endsection