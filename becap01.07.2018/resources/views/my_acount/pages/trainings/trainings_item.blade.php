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
		<header class="row item-header">
			<h4 class="item-header__title col-sm-12">
			@if(isset($item->title)) 
				{!!$item->title!!}
			@else 
			День {{$item->day}} | Неделя {{ceil($item->day/7)}}
			@endif
			</h4>		
		</header>
		<div class="row item-content ">
			<div class="col-12 flex-direction-column">
				{!! $item->text !!}
			</div>			
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