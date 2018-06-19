@include('my_acount/partials/header')

	@include('my_acount/partials/navbar')
	<main role="main">
		<section class="main">
    		<div class="main__box">    		
    			@include('my_acount/partials/sidebar_menu')
    			<div class="main__content">
				@yield('content')
				</div> 			
 			</div>	 
    	</section>		
	</main>

@include('my_acount/partials/footer')



