<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{$title}}</title>
	@if (  isset( $settings->favicon ) )
	<link rel="icon" type="image/png" sizes="16x16" href="/uploads/favicon/{{ $settings->favicon }}">
	@endif
	
	@section('header-styles')	
	<link rel="stylesheet" href="{{ asset('css/libs/font-awesome.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/libs/animate.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/libs/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/slick-theme.css') }}">  	
	<link rel="stylesheet" href="{{ asset('css/my_account/layout_style.css') }}">
	<link rel="stylesheet" href="{{ asset('css/my_account/media_layout_style.css') }}">			
	@show

	@section('header-scripts')
	<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
	@show
	
</head>	


