<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="csrf-token" content="{{ csrf_token() }}">    
	@if (  isset( $settings->keywords ) )<meta name="keywords" content="{!! $settings->keywords !!}">@endif
	@if (  isset( $settings->description )  )<meta name="description" content="{!! $settings->description !!}">@endif
	@if (  isset( $settings->title ) )<title>{!! $settings->title !!}</title>@endif	

	@if (  isset( $settings->favicon ) )
	<link rel="icon" type="image/png" sizes="16x16" href="/uploads/favicon/{{ $settings->favicon }}">
	@endif
	@section('header-styles')	
	<link rel="stylesheet" href="{{ asset('css/libs/font-awesome.min.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/libs/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/libs/slick-theme.css') }}">  	
	<link rel="stylesheet" href="{{ asset('css/layout_style.css') }}">
	<link rel="stylesheet" href="{{ asset('css/media_layout_style.css') }}">			
	@show

	@section('header-scripts')
	<script  src="{{asset('js/lib/jquery-3.3.1.min.js') }}"></script>	
	@show
	
</head>	
