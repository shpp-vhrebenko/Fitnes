<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\User;
use App\Courses;

class IsActiveCourse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       
        if (Auth::user()) {
            $curentUser = User::find(Auth::id());                      
            if($curentUser->course_id || Route::currentRouteName() == 'courses_list') {
                $course = Courses::find($curentUser->course_id);
                $whats_app_link = $course->whats_app_link;
                $request->session()->put('whats_app_link', $whats_app_link);
                return $next($request);
            } else {                
                return redirect()->route('courses_list');                       
            }            
        }

        return redirect('/');
        
    }
}
