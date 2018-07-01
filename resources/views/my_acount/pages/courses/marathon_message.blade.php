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
			<h4 class="courses-header__title col-sm-12">{!! $title !!}</h4>
			<div class="courses-header__description col-sm-12">До начала марафона сталось {{ $diffDays }} {{ Lang::choice('messages.days', $diffDays) }}</div>
		</header>		
	</div>	  
@endsection


@section('footer-scripts')    
    @parent     
    <script  src="{{asset('js/my_account.js') }}"></script>
@endsection

@section('footer-modal')
    @parent      
@endsection