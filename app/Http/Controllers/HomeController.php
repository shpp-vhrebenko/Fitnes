<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Settings;
use App\Social;
use App\Courses;
use App\Marathons;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $settings = Settings::first();     
        view()->share(compact([ 'settings'])); 
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
        $cours = Courses::where('is_active', true)->where('type', 'cours')->firstOrFail();    
        $marathon = Courses::where('is_active', true)->where('type', 'marathon')->firstOrFail();    
        return view('front.home', compact(['settings', 'instagram', 'cours', 'marathon']));
    }

    public function register_user($id_cours)
    {      
        $course = Courses::where('id', $id_cours)->firstOrFail();  
        return view('auth.register', compact(['course']));
    }

    public function validate_email_user(Request $request)
    {
        $curEmail = $request->get('email');
        $users = User::where('email', $curEmail)->get();
        $response = array(
            'status' => 'success'                     
        );
         
        
           
       
            $response['result'] = $users->count();
        
        return response()->json($response);      
    }

    public function user_store() {
        
    }
}
