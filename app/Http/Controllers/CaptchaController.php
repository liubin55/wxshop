<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\Captcha;

class CaptchaController extends Controller
{
    /*生成验证码 验证码图片
     *
     * $code 验证码
     * verify->doimg 图片
     * */
    public function create()
    {
        $verify=new Captcha();
        $code=$verify->getCode();
        session(['verifycode'=>$code]);
        return $verify->doimg();
    }
}
