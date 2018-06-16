<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">      

    <title>Camotec Adminpanel</title>

    @section('header-styles')	
    <link rel="stylesheet" href="{{ asset('css/libs/font-awesome.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/libs/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>			
	@show

	@section('header-scripts')
	<script  src="{{asset('js/lib/jquery-3.3.1.min.js') }}"></script>	
	@show

    
</head>