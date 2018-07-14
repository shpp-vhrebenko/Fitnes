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
				<button class="fixed-contact__button pull-right" data-toggle="modal" data-target="#modalContacts">
					<span class="button__title">написать письмо</span>	
					<i class="fa fa-envelope" aria-hidden="true" ></i>						
				</button>				
			</div>
		</div>
	</div>
       
</footer>
@section('footer-scripts')	
	<script  src="{{asset('js/lib/popper.min.js') }}"></script>
	<script  src="{{asset('js/lib/bootstrap.min.js') }}"></script>
@show
@section('footer-modal')
@show

</body>
</html>