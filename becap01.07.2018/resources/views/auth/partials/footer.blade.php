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
@include('auth/partials/sidebar_menu')
<div class="modal fade" id="modalContacts" tabindex="-1" role="dialog" aria-labelledby="modalContactsTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-block-contacts" role="document">
        <div class="modal-content block-contacts">
            <button type="button" class="close block-contacts__close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">X</span>
            </button>
            <div class="modal-body">
            	<h5 class="block-contacts__header">Написать письмо</h5>
                <form action="#" class="form-contacts">
                    <div class="form-group">
                        <label for="user-name">имя</label>
                        <input type="text" class="form-control" id="user-name"  placeholder="" required="true">             
                    </div>
                    <div class="form-group">
                        <label for="user-email">Email</label>
                        <input type="email" class="form-control" id="user-email" placeholder="" required="true">                        
                    </div>
                    <div class="form-group form-contacts__text">
                        <label for="user-textMessage">текст сообщения</label>
                        <textarea class="form-control" id="user-textMessage" rows="4"></textarea>
                    </div>
                    <button class="form-contacts__button" id="submitFormContacts">отправить</button>
                </form>
            </div>
          
        </div>
      </div>
    </div>  
@show

</body>
</html>