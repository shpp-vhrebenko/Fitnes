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
			<h4 class="category-header__title col-sm-12">{{$page_title}}</h4>
			<div class="category-header__description col-sm-12">{{$description}}</div>			
		</header>
		<div class="row category-content">
			@foreach($trainingItems as $item)
				@if (isset($item))
				<div class="col-md-4 col-sm-12 item">
					
					<a class="item__image" href="{{route('show_training', ['course_slug' => $item->course_slug,'slug' => $item->slug])}}" style="background-image: url('/uploads/items/{{$item->image}}')" >			
					</a>
					<div class="item__title">
						<p>Курс тренировок</p>				
						<a href="{{route('show_training', ['course_slug' => $item->course_slug, 'slug' => $item->slug])}}">
							@if(isset($item->title)) 
							{!!$item->title!!}
							@else 
							День {{$item->day}} | Неделя {{ceil($item->day/7)}}
							@endif
						</a>
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
    <script>
    	jQuery(document).ready(function($) {
    		@if($notification)
    			$('#modalNotification').modal('show');
    			$("#button-modalNotification-close").on("click", function(){
				    $.ajax({
			            url: '{{ route('check_user_notification') }}',
			            type: 'post',
			            data: {
			              _token: $('meta[name="csrf-token"]').attr('content')		              
			            },
			            success: function (data) {
			            	$('#modalNotification').modal('hide');
			            },
			            error: function (xhr, b, c) {
				            console.log("xhr=" + xhr + " b=" + b + " c=" + c);
				        }
					});
				});	
    		@endif
    	});
    	
    </script>
@endsection

@section('footer-modal')
    @parent 
    @if($notification['message'])
    <div class="modal fade" id="modalNotification" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle">Важное сообщение</h5>
		        <button class="close" id="button-modalNotification-close" class="close" type="button">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        {!! $notification['message'] !!}
		      </div>
		      <div class="modal-footer">    
		      	 
		      </div>
		    </div>
	  	</div>
	</div>    
	@endif 
@endsection