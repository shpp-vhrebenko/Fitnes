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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    @if(Session::has('message_success'))
    <script>
        jQuery(document).ready(function($) {
            $('#modalUserMessage').modal('show')
        });
    </script>
    @endif 
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
                <form action="{{route('user_message')}}" class="form-contacts" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">имя</label>
                        <input type="text" name="name" class="form-control" id="name"  placeholder="" required="true">             
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="" required="true">                        
                    </div>
                    <div class="form-group form-contacts__text">
                        <label for="textMessage">текст сообщения</label>
                        <textarea name="message" class="form-control" id="textMessage" rows="4"></textarea>
                    </div>
                    <button class="form-contacts__button" id="submitFormContacts">отправить</button>
                </form>
            </div>          
        </div>
      </div>
    </div>
    <div class="modal fade" id="modalUserMessage" tabindex="-1" role="dialog" aria-labelledby="modalContactsTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-block-contacts" role="document">
        <div class="modal-content block-contacts">
            <button type="button" class="close block-contacts__close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">X</span>
            </button>
            <div class="modal-body">
                <h5 class="block-contacts__header">Сообщение успешно отправлено!</h5>      
            </div>          
        </div>
      </div>
    </div>      
@show

</body>
</html>