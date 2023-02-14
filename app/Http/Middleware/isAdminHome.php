<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;

class isAdminHome
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
        //dd($request->route('id'));
        $id =  $request->route('id');
        if(Auth::guest()){
            return $next($request);
        }elseif(Auth::check()){
            //if(!Auth::user()->isAdminHome($id))
            //{
                return $next($request);
           //}
        }
        
        //return redirect('/backend');
    }
}
