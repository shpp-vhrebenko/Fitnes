<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">      

    <title>GA-Adminpanel</title>

    @section('header-styles')	
    <link rel="stylesheet" href="{{ asset('css/libs/font-awesome.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/libs/animate.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">  
	<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}"/>			
	@show

	@section('header-scripts')
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script> 
	@show

    
</head>