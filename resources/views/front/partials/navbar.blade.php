<nav class="navbar navbar-expand-md fixed-top header-navbar">
  <a class="navbar-brand" href="{{ route('index') }}">
    @if (  isset( $settings->logo ) )
    <img src="./uploads/logo/{{ $settings->logo }}" class="attachment-medium size-medium" alt="" />
    @endif
  </a>
  <div id='fixed-menu' class="fixed-menu">  
    <a href="{{ route('my-account') }}">
      <span class="user-icon"></span>          
      <!-- <i class="fa fa-user-circle-o user-icon" aria-hidden="true"></i>  -->           
      <span class="fixed-menu__title" >ЛИЧНЫЙ КАБИНЕТ</span>
    </a>
    <div  class="fixed-menu__right pull-right">
      <span>МЕНЮ</span>
      <div class="fixed-menu__button" id="button_open_sidebar-menu">
        <div class="inner-button"></div>
      </div>
    </div>
</nav>