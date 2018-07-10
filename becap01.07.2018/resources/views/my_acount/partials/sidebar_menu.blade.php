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
	            <li class="@if(Request::url() == route('show_results')) active @endif">
		            <a class="usermenu__link" href="{{ route('show_results') }}">
		            	Результаты
		            </a>
	            </li>
	            <li class="@if(Request::url() == route('show_trainings')) active @endif">
		            <a class="usermenu__link" href="{{ route('show_trainings') }}">Тренировки</a>
	            </li>	
	            @if(isset($categories) && count($categories) > 0)
	                @foreach($categories as $category) 
		                @if($category->id != 1 && $category->is_active != 0)
		                <li class="@if(Request::url() == route('show_category_items', $category->slug)) active @endif">
		                    <a class="usermenu__link" href="{{route('show_category_items', $category->slug)}}" .="">		{{$category->name}}
							</a>                       
		                </li>
		                @endif    
	                @endforeach
	            @endif							
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
	</div>
</div>	