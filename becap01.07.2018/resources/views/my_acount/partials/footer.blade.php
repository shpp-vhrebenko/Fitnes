@section('footer-scripts')	
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
	jQuery(document).ready(function($) {
		$.ajax({
		    url: '{{ route('get_motivations') }}',
		    type: 'post',
		    data: {
		      _token: $('meta[name="csrf-token"]').attr('content'),
		    },
		    success: function (data) {    	
		    	motivations = data.motivations;
		    	period_motivation = data.period_motivation;
		    	last_id = data.last_id;
		    	interval = data.interval; 
		    	if(data.show_motivation) {				    			
					showMotivation(motivations[data.last_id]);
				}	
		    	startMotivation(motivations, period_motivation, last_id, interval);
		    },
		    error: function (xhr, b, c) {
		        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
		    }	    
		});	 

		function startMotivation(motivations, period_motivation, last_id, interval) {					
			setInterval(function() {				
				$.ajax({
				    url: '{{ route('is_show_motivation') }}',
				    type: 'post',
				    data: {
				      _token: $('meta[name="csrf-token"]').attr('content'),
				    },
				    success: function (data) {	    	
				    	if(data.show_motivation) {				    			
							showMotivation(motivations[data.last_id]);
						}				      	
				    },
				    error: function (xhr, b, c) {
				        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
				    }	    
				});					
									  	
			}, interval);
		}

		function showMotivation(message) {
			$('#modalMotivations').modal('hide');
			var motivationsMessage = $( "#motivationsMessage" );
			$('#modalMotivations').find(motivationsMessage).text(message);
			$('#modalMotivations').modal('show');
		}
		
	});
</script>
@show
@section('footer-modal')
<!--<div class="modal" tabindex="-1" role="dialog" id="modalMotivations" class="modal-motivation">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="motivationsMessage"></p>
      </div>      
    </div>
  </div>
</div>-->

<div id="modalMotivations" class="edinorog modal modal-motivation" tabindex="-1" role="dialog">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="cloud-wrap">
<div id="motivationsMessage" class="cloud"></div>
<div class="arrow-cloud"></div>
<div class="edinorog-img"></div> 
</div>
</div>


</div>

@include('my_acount/partials/sidebar_menu')
@show

</body>
</html>