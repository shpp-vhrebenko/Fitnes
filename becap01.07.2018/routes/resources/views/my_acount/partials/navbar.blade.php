<nav class="navbar navbar-expand-md fixed-top header-navbar">
  <a class="navbar-brand" href="{{ route('index') }}">
    <img src="/uploads/logo/{{$settings->logo}}" class="attachment-medium size-medium" alt="" />
  </a>
  @if(Auth::check())
  <div id="open-sidebar" class="navbar__open-sidebar">
    <i class="fa fa-bars"></i>
    МЕНЮ
  </div>  
  <div id='fixed-menu' class="fixed-menu">
    @if(Auth::user()->hasRole(1) == true))
    <a href="{{ route('admin') }}" class="fixed-menu__link">   
      Перейти в админбар    
    </a>
    @endif
  	<span class="fixed-menu__user-name">  		
        {{ Auth::user()->name }}      
  	</span>
	  <a class="fixe-menu__logout fa fa-sign-out" href="{{ route('logout') }}"
       onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">       
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>  	
  </div>  
  @endif    
</nav>