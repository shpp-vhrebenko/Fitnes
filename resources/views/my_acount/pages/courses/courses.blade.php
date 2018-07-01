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
			<h4 class="courses-header__title col-sm-12">{{$title}}</h4>
			<div class="courses-header__description col-sm-12">{{$description}}</div>
		</header>
		<div class="row courses-content">
			<form action="{{ route('by_course') }}" method="post">
        	{{ csrf_field() }}
        	 <div class="row">                          
                <div class="form-group col-sm-6" >                    
                    <div class="col-sm-6">                            
                        <select class="form-control" id="week" name="course_id">  
                            <option value="0">-- Не выбрано --</option>   
                            @if(isset($courses) && count($courses) > 0)
                                @foreach($courses as $course)                                        
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            @endif        
                        </select>
                    </div>                 
                </div>  
                <button type="submit" class="btn btn-success pull-right">Выбрать</button>               
            </div>
        	</form>
		</div> 
	</div>	  
@endsection


@section('footer-scripts')    
    @parent     
    <script  src="{{asset('js/my_account.js') }}"></script>
@endsection

@section('footer-modal')
    @parent      
@endsection