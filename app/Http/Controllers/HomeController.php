<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;
use App\Social;
use App\Courses;
use App\Marathons;

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
        $cours = Courses::where('is_active', true)->firstOrFail();    
        $marathon = Marathons::where('is_active', true)->firstOrFail();    
        return view('front.home', compact(['settings', 'instagram', 'cours', 'marathon']));
    }
}
