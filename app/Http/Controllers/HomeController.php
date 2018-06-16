<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::first();  
        return view('front.home', compact(['settings']));
    }
}
