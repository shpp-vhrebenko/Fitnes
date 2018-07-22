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
				<div id="training-action">
					@if(isset($traininig_status) && $traininig_status)
					<p class="training-done">
					Тренировка завершина
					</p>
					@else
					<button type="button" class="training__button pull-left" id="doneTraining" data-training-indification="{{$item->id}}">
						Завершить тренировку
					</button>
					@endif	
				</div>			
			</div>			
		</div>  
	</div>	 
@endsection


@section('footer-scripts')    
    @parent     
    <script  src="{{asset('js/my_account.js') }}"></script>
    <script>
    	jQuery(document).ready(function($) {

    		$("#doneTraining").click(function(){

    			var $this = $(this);
    			var training_id = $this.data('trainingIndification');    			
			    $.ajax({
				    url: '{{ route('set_training_status') }}',
				    type: 'post',
				    data: {
				      _token: $('meta[name="csrf-token"]').attr('content'),
				      training_id: training_id,
				    },
				    success: function (data) {  
				    	if(data.is_done != undefined && data.is_done == 1) {
				    		$this.hide();
				    		var trainingDone = $('<p>').addClass('training-done').text('Тренировка завершина');
				    		trainingDone.prependTo('#training-action');
				    	}
				    	
				    },
				    error: function (xhr, b, c) {
				        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
				    }	    
				});
			});			
		});
    </script>
@endsection

@section('footer-modal')
    @parent      
@endsection