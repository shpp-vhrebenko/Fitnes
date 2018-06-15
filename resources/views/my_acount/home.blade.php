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
    <section class="main">
    	<div class="main__box">    		
    			<div class="main__sidebar">
      			<!-- BEGIN PROFILE SIDEBAR -->
      				<div id="profile-sidebar" class="profile-sidebar">         
			          	<div class="profile-userpic text-center" id="profile_image_main">
				            <div id="profile-data-menu">
					            <span class="nick">
					            Дэниел
					            </span>  
			      			</div>
			        	</div>
				        <!-- END SIDEBAR USERPIC -->
				        <!-- SIDEBAR USER TITLE -->
				       
				       	<!-- END SIDEBAR USER TITLE -->
				       	<!-- SIDEBAR BUTTONS -->
				       
				      	<!-- END SIDEBAR BUTTONS -->
				      	<!-- SIDEBAR MENU -->
				      	<div class="profile-sidebar__usermenu usermenu">
				            <ul>
					        	<li class="">
					          		<a class="usermenu__link" href="https://gizerskaya.com/">
					            		Главная
					            	</a>
					         	</li>
					            <li class=" ">
						            <a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=records">		            	
						            	Результаты
						            </a>
					            </li>	
								<li class="active ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=training-plans" .="">				
										Тренировки
									</a>
							  	</li>
								<li class=" ">
								    <a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=diet-plans" .="">					
										Питание
									</a>
								</li>
						  		<li class=" ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=statii" .="">						
										Интересные статьи
									</a>
							    </li>
							    <li class=" ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=statii" .="">						
										Рецепты
									</a>
							    </li>
							    <li class=" ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=statii" .="">						
										FAQ
									</a>
							    </li>
							    <li class=" ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=statii" .="">						
										Чат
									</a>
							    </li>
							    <li class=" ">
							  		<a class="usermenu__link" href="https://gizerskaya.com/my-account/?&amp;profile=statii" .="">						
										Выйти
									</a>
							    </li>
					    	</ul>
				  		</div>
				  		<!-- END SIDEBAR MENU -->
 					</div>
 				</div>	
				<div class="main__content">
				</div> 			
 		</div>	 
    </section>
@endsection


@section('footer-scripts')    
    @parent     
    <script  src="{{asset('js/my_account.js') }}"></script>
@endsection

@section('footer-modal')
    @parent      
@endsection