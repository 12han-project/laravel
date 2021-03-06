<?php

namespace App\Http\Middleware;

use Closure;

class Login_teacher
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
        if(empty(session('user_info')) || session('user_info')['type'] != 1){
            $request->session()->flush();
            return redirect('/login')->withErrors(['ログインしてください']);
        }

        return $next($request);
    }
}
