<footer class="footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="copyright pull-left">			      
		        	<a href="https://instagram.com/NGIZERSKAYA" class="copyright__link copyright__link-insta">
			            <i class="fa fa-instagram"></i>
			        </a>      
		        	<a href="https://instagram.com/NGIZERSKAYA" class="copyright__link copyright__link-copyr">© 2018 ANASTASIA GIZERSKAYA</a>		            
			    </div> 
			</div>
			<div class="col-md-6">
				<div id='fixed-contact' class="fixed-contact">
				    <a href='#price' class="anchor-link">
				    	<i class="fa fa-credit-card" aria-hidden="true"></i>
					    <span class="fixe-contact__title">
						    КУПИТЬ КУРС
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
       
</footer>
@section('footer-scripts')	
	<script  src="{{asset('js/lib/popper.min.js') }}"></script>
	<script  src="{{asset('js/lib/bootstrap.min.js') }}"></script>
@show
@section('footer-modal')
<div id='div-menu' class="sidebar-menu">
  <span class='button-close'>X</span>
	<ul id='mainGizMenu'>
		<li><a href='#main' class="anchor-link">Главная</a></li>
	  	<li><a href='#about' class="anchor-link">О проекте</a></li>
	  	<li><a href='#price' class="anchor-link">Пакеты</a></li>
	  	<li><a href='#testamonials' class="anchor-link">Отзывы</a></li>
	  	<li><a href='#results' class="anchor-link">Результаты</a></li>
	  	<li><a href='#faq' class="anchor-link">Вопросы</a></li>
		<li><a href='#contacts' class="anchor-link">Контакты</a></li>
	  	<li><a href='#' id='modalOpen3' class="eModal-3">Конкурс</a></li>
		<li><a href='https://gizerskaya.com/wp-content/uploads/2018/04/ПОЛИТИКА_КОНФИДЕНЦИАЛЬНОСТИ_ИП_Гизерская.pdf' target='_blank'>Политика конфиденциальности</a></li>
	</ul>
<div>
@show


</body>
</html>