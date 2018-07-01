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
			<h4 class="category-header__title col-sm-12">{{$category->name}}</h4>
			<div class="category-header__description col-sm-12">{!! $category->description !!}</div>
		</header>
		<div class="row category-content">
			@foreach($items as $item)
				<div class="col-md-4 col-sm-12 item">
					<a class="item__image" href="{{route('show_item', ['slug' => $category->slug, 'id' => $item->id])}}" style="background-image: url('/uploads/items/{{$item->image}}')">						
					</a>
					<div class="item__title">					
						<a href="{{route('show_item', ['slug' => $category->slug, 'id' => $item->id])}}">{{ str_limit($item->title, $limit = 60, $end = '...') }}</a>
					</div>
				</div>
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