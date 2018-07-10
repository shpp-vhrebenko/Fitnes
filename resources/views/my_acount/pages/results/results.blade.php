@extends('my_acount/layout/front')

@section('header-styles')     
    @parent
    <link rel="stylesheet" href="{{ asset('css/my_account/home_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my_account/media_home_style.css') }}">         
@endsection

@section('header-scripts')
   @parent
@endsection

@section('content')	
	<div class="container-fluid results-container">
		<header class="row results-header">
			<h4 class="results-header__title col-sm-12">Результаты</h4>
			<p class="results-header__description col-sm-12">В разделе результаты вы можете наблюдать за своими достижениями</p>
			<a href="{{ route('add_result') }}" class="results__button pull-left">
				Добавить отчет
			</a>
		</header>
		<div class="row results-content">
			 <div id='grafik-results' class="results-grafik col-md-12">
                <ul class="nav nav-tabs">
					<li class="nav-item" >
	          			<a class="nav-link active grafik-link" href="#tab_1_1" data-toggle="tab1">Вес </a>
	        		</li>
	        		<li class="nav-item" >
	          			<a class="nav-link grafik-link" href="#tab_1_2" data-toggle="tab2">Рост </a>
	        		</li>
	        		<li class="nav-item">
	          			<a class="nav-link grafik-link" href="#tab_1_4" data-toggle="tab4">Грудь </a>
	        		</li>
	        		<li class="nav-item">
	         			<a class="nav-link grafik-link" href="#tab_1_5" data-toggle="tab5">Бедра </a>
	        		</li>
	        		<li class="nav-item">
	          			<a class="nav-link grafik-link" href="#tab_1_3" data-toggle="tab3">Талия </a>
	        		</li>	        
				</ul>
                <canvas id="myChart"></canvas>                
            </div>
            <div class="col-md-12">
            	<div class="row results-items">
                	@foreach ($results as $result)
                	<div class="col-md-6 result-item">
                		<div class="result-item__image">
                			<div class="bg-image" style="background-image: url(/uploads/results/{{$result->image}})" >
                			</div>
                		</div>		
                		<div class="result-item__description">
                			<p><strong>Дата: </strong>{{$result->created_at}}</p>
                			<p><strong>Ваш вес: </strong>{{$result->weight}}</p>
                			<p><strong>Ваш рост: </strong>{{$result->height}}</p>
                			<p><strong>Обхват груди: </strong>{{$result->grud}}</p>
                			<p><strong>Обхват бедер: </strong>{{$result->bedra}}</p>
                			<p><strong>Обхват талии: </strong>{{$result->taliya}}</p>
                		</div>
                		@if($loop->last && $result->id > $id_last_result)                		
        				<div class="result-item__option">                			
					  		<a class="btn-delete-result" href="{{ route('result_delete') }}"
						       onclick="event.preventDefault();
						                     document.getElementById('result-form-delete').submit();">	Удалить последний результат							
							</a>			    
						    <form id="result-form-delete" action="{{ route('result_delete') }}" method="POST" style="display: none;">
						    	<input type="hidden" name="_method" value="delete">
						        @csrf
						        <input type="hidden" name="result_id" value="{{$result->id}}">
						    </form>
                		</div>
     					@endif
                		
                	</div>
                	@endforeach
                </div>
			</div>
		</div> 
	</div>	  
@endsection


@section('footer-scripts')    
    @parent     
    <script src="{{asset('js/lib/Chart.min.js')}}"></script>
    <script  src="{{asset('js/my_account.js') }}"></script>
	<script>
		Chart.defaults.global.defaultFontColor = '#f3ccf8';
		var labelsArr = [];
		var dataVes = [];
		var dataRost = [];
		var dataTaliya = [];
		var dataGrud = [];
		var dataBedra = [];

		var labelsResult = labelsArr;
		var dataResult = [];
		var nameLabel = "";
		var namesLabel = [
			"Вес",
			"Рост",
			"Обхват талии",
			"Обхват груди",
			"Обхват бедер",
		]
		var results, ctx, myChart;
		
      	$.ajax({
            url: '{{ route('get_results') }}',
            type: 'post',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
            	if(data.results != undefined) {
            		results = data.results;
            		for (key in results) {
            			var item = results[key];
            			dataVes.push(item.weight);
            			dataRost.push(item.height);
            			dataGrud.push(item.grud);
            			dataBedra.push(item.bedra);								
						dataTaliya.push(item.taliya);
						labelsArr.push(item.created_at);
            		}
            		nameLabel = "Веc";
            		dataResult = dataVes;
            		ctx = document.getElementById("myChart").getContext('2d');
					myChart = new Chart(ctx, {
					    type: 'line',
					    data: {
					        labels: labelsResult,
					        datasets: [{
					            label: nameLabel,
					            fontColor:'#fff',
					            data: dataResult,
					            backgroundColor: [
					                'rgba(255, 205, 255, 0.2)'
					            ],
					            borderColor: [
					                'rgba(255,205,255,1)'
					            ],
					            borderWidth: 1
					        }]
					    },
					    options: {
					    	legend: {
					            labels: {
					                fontColor: 'white'
					            }
					        },
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero:true
					                }
					            }]
					        }
					    }
					});
            	}              	
            },
            error: function (xhr, b, c) {
	            console.log("xhr=" + xhr + " b=" + b + " c=" + c);
	        }
        });	

        $("a.grafik-link").click(function(e) {
			e.preventDefault();
		})
        							
		$('#grafik-results li a[data-toggle="tab1"]').click(function(){			
			$('#grafik-results li a').each(function(){
	            $(this).removeClass('active');
	        });
	        $(this).addClass('active');
			nameLabel = "Вес";
			dataResult = dataVes;
			myChart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: labelsResult,
			        datasets: [{
			            label: nameLabel,
			            fontColor:'#fff',
			            data: dataResult,
			            backgroundColor: [
			                'rgba(255, 205, 255, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,205,255,1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			    	legend: {
			            labels: {
			                fontColor: 'white'
			            }
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		});

		$('#grafik-results li a[data-toggle="tab2"]').click(function(){
			$('#grafik-results li a').each(function(){
	            $(this).removeClass('active');
	        });
	        $(this).addClass('active');
			nameLabel = "Рост";
			dataResult = dataRost;
			myChart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: labelsResult,
			        datasets: [{
			            label: nameLabel,
			            fontColor:'#fff',
			            data: dataResult,
			            backgroundColor: [
			                'rgba(255, 205, 255, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,205,255,1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			    	legend: {
			            labels: {
			                fontColor: 'white'
			            }
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		});

		$('#grafik-results li a[data-toggle="tab3"]').click(function(){
			$('#grafik-results li a').each(function(){
	            $(this).removeClass('active');
	        });
	        $(this).addClass('active');
			nameLabel = "Обхват талии";
			dataResult = dataTaliya;
			myChart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: labelsResult,
			        datasets: [{
			            label: nameLabel,
			            fontColor:'#fff',
			            data: dataResult,
			            backgroundColor: [
			                'rgba(255, 205, 255, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,205,255,1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			    	legend: {
			            labels: {
			                fontColor: 'white'
			            }
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		});

		$('#grafik-results li a[data-toggle="tab4"]').click(function(){
			$('#grafik-results li a').each(function(){
	            $(this).removeClass('active');
	        });
	        $(this).addClass('active');
			nameLabel = "Обхват груди";
			dataResult = dataGrud;
			myChart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: labelsResult,
			        datasets: [{
			            label: nameLabel,
			            fontColor:'#fff',
			            data: dataResult,
			            backgroundColor: [
			                'rgba(255, 205, 255, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,205,255,1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			    	legend: {
			            labels: {
			                fontColor: 'white'
			            }
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		});

		$('#grafik-results li a[data-toggle="tab5"]').click(function(){
			$('#grafik-results li a').each(function(){
	            $(this).removeClass('active');
	        });
	        $(this).addClass('active');
			nameLabel = "Обхват бедер";
			dataResult = dataBedra;
			myChart = new Chart(ctx, {
			    type: 'line',
			    data: {
			        labels: labelsResult,
			        datasets: [{
			            label: nameLabel,
			            fontColor:'#fff',
			            data: dataResult,
			            backgroundColor: [
			                'rgba(255, 205, 255, 0.2)'
			            ],
			            borderColor: [
			                'rgba(255,205,255,1)'
			            ],
			            borderWidth: 1
			        }]
			    },
			    options: {
			    	legend: {
			            labels: {
			                fontColor: 'white'
			            }
			        },
			        scales: {
			            yAxes: [{
			                ticks: {
			                    beginAtZero:true
			                }
			            }]
			        }
			    }
			});
		});					

	</script>
@endsection

@section('footer-modal')
    @parent      
@endsection