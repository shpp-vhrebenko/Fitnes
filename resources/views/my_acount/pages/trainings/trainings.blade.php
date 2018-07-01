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
		<header class="row category-header">
			<h4 class="category-header__title col-sm-12">{{$title}}</h4>
			<div class="category-header__description col-sm-12">{{$description}}</div>
		</header>
		<div class="row category-content">
			@foreach($trainingItems as $item)
				@if (isset($item))
				<div class="col-md-4 col-sm-12 item">
					
					<a class="item__image" href="{{route('show_training', ['id' => $item->id])}}" style="background-image: url('/uploads/items/{{$item->image}}')">			
					</a>
					<div class="item__title">
						<p>Курс тренировок</p>				
						<a href="{{route('show_training', ['slug' => $item->slug])}}">День {{$item->day}} | Неделя {{ceil($item->day/7)}}</a>
					</div>
				</div>
				@endif
			@endforeach
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