<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\User;

class IsFirstЕntrance
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
            if($curentUser->getClientStatus($curentUser->status_id) == 1) {
                return $next($request);
            } else {
                if(Route::currentRouteName() != 'add_result') {
                    return redirect()->route('add_result');
                } 
                return $next($request);               
            }            
        }

        return redirect('/');
    }
}
