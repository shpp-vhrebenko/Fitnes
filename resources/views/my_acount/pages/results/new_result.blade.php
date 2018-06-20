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
	<div class="container-fluid results-container">
		<header class="row results-header">
			<h4 class="results-header__title col-sm-12">Добавление отчета</h4>
			<p class="results-header__description col-sm-12">Для добавления отчета заполните поля.</p>
		</header>
		<div class="row results-content results-content_bg">
			<form 
				@if(Route::is('edit_result')) 
				action="{{ route('update_result', $result->id) }}"
				@else 
				action="{{ route('result_store') }}"
				@endif
				method="post"
				enctype="multipart/form-data"
				class="row col-sm-12 result-form">
        	{{ csrf_field() }}
            @if(Route::is('edit_result')) <input type="hidden" name="_method" value="put"> @endif
            	<div class="col-md-2 result-form__body">   
            		@if(isset($result->image)) 
            		<label class="btn btn-default result-form__button">
 						Загрузить фото
                        <input class="filestyle" 
                        value="/uploads/results/{{$result->image}}"              
                        name="result[image]"
                        type="file"
                        id="image"
                        hidden>
                    </label>    
                    @else
                    <label class="btn btn-default result-form__button">
 						Загрузить фото
                        <input class="filestyle"                                      
                        name="result[image]"
                        type="file"
                        id="image"
                        hidden>
                    </label>                                             
                    @endif             
                    <div class="form-group">                           	
                		<input type="number" class="form-control result-form__input" id="result_weight" name="result[weight]" value="{{ isset($result) ? $result->weight : old('result[weight]') }}" placeholder="Ваш вес">  
                	</div>
                	<div class="form-group">                           	
                		<input type="number" class="form-control result-form__input" id="result_height" name="result[height]" value="{{ isset($result) ? $result->height : old('result[height]') }}" placeholder="Ваш рост">  
                	</div> 
                	<div class="form-group">                           	
                		<input type="number" class="form-control result-form__input" id="result_grud" name="result[grud]" value="{{ isset($result) ? $result->grud : old('result[grud]') }}" placeholder="Обхват груди">  
                	</div>
                	<div class="form-group">                           	
                		<input type="number" class="form-control result-form__input" id="result_bedra" name="result[bedra]" value="{{ isset($result) ? $result->bedra : old('result[bedra]') }}" placeholder="Обхват бедер">  
                	</div> 
                	<div class="form-group">                           	
                		<input type="number" class="form-control result-form__input" id="result_taliya" name="result[taliya]" value="{{ isset($result) ? $result->taliya : old('result[taliya]') }}" placeholder="Обхват талии">  
                	</div> 
                	<button type="submit" class="btn btn-default result-form__button">@if(Route::is('edit_result')) Изменить @else Сохранить @endif</button>      
                </div>
                <div class="col-md-4 result-image">                	
            		@if(isset($result->image))                       
                    <img src="/uploads/results/{{$result->image}}" class="img-fluid">      
                    @endif                	
                </div>
                <div class="col-md-4 result-message">
                	@if (Session::has('success'))
				        <div class="alert alert-success alert-success_custom" role="alert">
				            {{ Session::get('success') }}
				        </div>
				    @endif
				    @if($errors)
				        @foreach ($errors->all() as $error)
				            <div class="alert alert-danger alert-danger_custom" role="alert">
				                {{ $error }}
				            </div>
				        @endforeach
				    @endif
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