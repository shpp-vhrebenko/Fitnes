@extends('front/layout/message')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/home_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/media_home_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')
<section class="oplata oplata-confirm">
    <div class="container oplata__container">
        <div class="row justify-content-center align-items-center ">
            <div class="col-md-6">
            	@if ($message = Session::get('success'))
			    <div class="w3-panel w3-green w3-display-container">
			        <span onclick="this.parentElement.style.display='none'"
			                class="w3-button w3-green w3-large w3-display-topright">&times;</span>
			        <p>{!! $message !!}</p>
			    </div>
			    <?php Session::forget('success');?>
			    @endif
				@if ($message = Session::get('error'))
			    <div class="w3-panel w3-red w3-display-container">
			        <span onclick="this.parentElement.style.display='none'"
			                class="w3-button w3-red w3-large w3-display-topright">&times;</span>
			        <p>{!! $message !!}</p>
			    </div>
			    <?php Session::forget('error');?>
			    @endif
            	<form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form"  action="{{route('pay_to_paypal')}}">
				  {{ csrf_field() }}
				  <h2 class="w3-text-blue">Payment Form</h2>
				  <p>Demo PayPal form - Integrating paypal in laravel</p>
				  <p>      
				  <label class="w3-text-blue"><b>Enter Amount</b></label>
				  <input class="w3-input w3-border" name="amount" type="text"></p>      
				  <button class="w3-btn w3-blue">Pay with PayPal</button></p>
				</form>
            </div>
        </div>
    </div>
</section>            	
@endsection