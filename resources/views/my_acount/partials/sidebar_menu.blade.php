<div class="main__sidebar">
	<div id="profile-sidebar" class="profile-sidebar">         
	  	<div class="profile-sidebar__username">			            
	        <span>{{ Auth::user()->name }}</span>			            		      			
		</div>				        
	  	<div class="profile-sidebar__usermenu usermenu">
	        <ul>
	        	<li class="">
	          		<a class="usermenu__link" href="{{ route('index') }}">
	            		Главная
	            	</a>
	         	</li>
	            <li class="@if(Request::url() == route('show_results')) active @elseif(Request::url() == route('add_result')) active @endif">
		            <a class="usermenu__link" href="{{ route('show_results') }}">
		            	Результаты
		            </a>
	            </li>
	            <li class="@if(Request::is('my-account/trainings', 'my-account/training/*')) active @endif">
		            <a class="usermenu__link" href="{{ route('show_trainings') }}">Тренировки</a>
	            </li>	
	            @if(isset($categories) && count($categories) > 0)
	                @foreach($categories as $category) 
		                @if($category->id != 1 && $category->is_active != 0)
		                <li class="@if(Request::is('my-account/category/'.$category->slug, 'my-account/category/'.$category->slug.'/*')) active @elseif(Request::is('my-account/'.$category->slug.'/regulations/*')) active @endif">
		                    <a class="usermenu__link" href="{{route('show_category_items', $category->slug)}}" .="">		{{$category->name}}
							</a>                       
		                </li>		                
		                @endif    
	                @endforeach
	            @endif	
	            @if(Session::has('faq'))							
			    <li class="@if(Request::url() == route('show_faq')) active @endif">
			  		<a class="usermenu__link" href="{{ route('show_faq') }}">				
						FAQ
					</a>
			    </li>
			    @endif
			    @if(Session::has('whats_app_link'))			    
			    <li class=" ">
			  		<a class="usermenu__link" href="{{Session::get('whats_app_link')}}" target="_blank">						
						Чат
					</a>
			    </li>
			    @endif			    
			    <li class=" ">
			  		<a class="usermenu__link" href="{{ route('logout') }}"
				       onclick="event.preventDefault();
				                     document.getElementById('logout-form').submit();">
						Выйти
					</a>
			    </li>			    
			    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			        @csrf
			    </form>
	    	</ul>
		</div>	
	</div>
</div>	