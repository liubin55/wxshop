<?php

namespace App\Http\Middleware;

use Closure;
use App\Tools\Wxjs;
use Illuminate\Support\Facades\Redis;
class JSSDK
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
        $jssdk = new Wxjs(env("WXAPPID"), env("WXAPPSECRET"));
        $signPackage = json_encode($jssdk->GetSignPackage(),JSON_UNESCAPED_UNICODE);
        Redis::set('jssdk',$signPackage);
//        $wxconfig=['signPackage'=>$signPackage];
//        $request->merge($wxconfig);
        return $next($request);
    }
}
