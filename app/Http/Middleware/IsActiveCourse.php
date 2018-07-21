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
            if($curentUser->course_id == 0 && Route::currentRouteName() == 'courses_list') {
                return $next($request);
            } else if($curentUser->course_id == 0 && Route::currentRouteName() == 'by_course') {
                return $next($request);
            } else if ($curentUser->course_id == 0) {                
                return redirect()->route('courses_list');                       
            } else if($curentUser->course_id != 0 && Route::currentRouteName() == 'courses_list'){
                return redirect()->route('show_trainings');                
            } else if ($curentUser->course_id != 0){
                $course = Courses::find($curentUser->course_id);
                if(isset($course)) {
                    $whats_app_link = $course->whats_app_link;
                    $faq = strip_tags($course->faq);                    
                    if(isset($whats_app_link)) {
                       $request->session()->put('whats_app_link', $whats_app_link);  
                    } else {
                        $request->session()->forget('whats_app_link');
                    }
                    if(isset($faq) && !empty($faq)) {
                       $request->session()->put('faq', true);  
                    } else {
                        $request->session()->forget('faq');
                    }               
                }
                return $next($request);
            }          
        }

        return redirect('/');
        
    }
}
