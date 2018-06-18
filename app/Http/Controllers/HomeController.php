<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;
use App\Social;

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
        $instagram = Social::firstOrFail();             
        $settings = Settings::first();  
        return view('front.home', compact(['settings', 'instagram']));
    }
}
