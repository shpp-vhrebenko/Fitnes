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
			<h4 class="item-header__title col-sm-12">Основные правила питания</h4>		
		</header>
		<div class="row item-content">
			{!! $food_regulations !!}
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