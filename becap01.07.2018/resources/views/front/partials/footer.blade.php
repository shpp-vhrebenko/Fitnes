<footer class="footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="copyright pull-left">			      
		        	<a href="{{ $instagram->link }}" class="copyright__link copyright__link-insta">
			            <i class="fa fa-instagram"></i>
			        </a>      
		        	<a href="{{ $instagram->link }}" class="copyright__link copyright__link-copyr">© 2018 ANASTASIA GIZERSKAYA</a>		            
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@show
@section('footer-modal')
@include('front/partials/sidebar_menu')
@show

</body>
</html>