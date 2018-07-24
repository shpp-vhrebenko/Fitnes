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
		    	motivations_images = data.motivations_images;
		    	period_motivation = data.period_motivation;
		    	last_id = data.last_id;
		    	interval = data.interval; 
		    	if(data.show_motivation) {	
		    		var indexImage = Math.floor(Math.random() * 10);
					var image_src = motivations_images[indexImage];		    			
					showMotivation(motivations[data.last_id], image_src);
				}					
		    	startMotivation(motivations, motivations_images, period_motivation, last_id, interval);
		    },
		    error: function (xhr, b, c) {
		        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
		    }	    
		}); 

		function startMotivation(motivations, motivations_images, period_motivation, last_id, interval) {			
			setInterval(function() {				
				$.ajax({
				    url: '{{ route('is_show_motivation') }}',
				    type: 'post',
				    data: {
				      _token: $('meta[name="csrf-token"]').attr('content'),
				    },
				    success: function (data) {	    	
				    	if(data.show_motivation) {		
				    		var indexImage = Math.floor(Math.random() * 10);
							var image_src = motivations_images[indexImage];			
							showMotivation(motivations[data.last_id], image_src);
						}				      	
				    },
				    error: function (xhr, b, c) {
				        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
				    }	    
				});					
									  	
			}, interval);
		}

		function showMotivation(message, image_src) {				
			$('#modalMotivations').modal('hide');
			var motivationsMessage = $( "#motivationsMessage" );
			var motivationsImage = $("#edinorog-img");
			$('#modalMotivations').find(motivationsMessage).text(message);
			motivationsImage.css('background-image', 'url(' + image_src + ')');
			$('#modalMotivations').modal('show');
		}
		
	});
</script>
@show
@section('footer-modal')
<div id="modalMotivations" class="edinorog modal modal-motivation" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="cloud-wrap">
			<div id="motivationsMessage" class="cloud"></div>
				<div class="arrow-cloud"></div>
			<div class="edinorog-img" id="edinorog-img"></div> 
		</div>
	</div>
</div>
@include('my_acount/partials/sidebar_menu')
@show

</body>
</html>