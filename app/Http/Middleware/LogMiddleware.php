<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class LogMiddleware
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
        if(Redis::exists('userinfo')){
            $data=Redis::get('userinfo');
            $data=json_decode($data,JSON_UNESCAPED_UNICODE);
            session(['user_id'=>$data['user_id'],'user_tel'=>$data['user_tel']]);
        }
        if(empty(session('user_id'))){
            return redirect('login');
        }
        return $next($request);
    }
}
