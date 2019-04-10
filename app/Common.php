<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  App\Tools\sms\lib\Ucpaas;

class Common extends Model
{

    //生成随机码
    public static function createcode($len)
    {
        $code='';
        for($i=1;$i<=$len;$i++){
            $code.=mt_rand(0,9);
        }
        return $code;
    }
    //云之讯发送验证码
    public static function sendSms($address,$code)
    {
        //填写在开发者控制台首页上的Account Sid
        $options['accountsid']='92eaa3fe07dcff315306d06ea7905130';
        //填写在开发者控制台首页上的Auth Token
        $options['token']='c6a63f29a72fe69075e0fd05694c8d83';

        //初始化 $options必填
        $appid = "3c188ac1bf4c4e0aa7db6a8b7ea6b493";	//应用的ID，可在开发者控制台内的短信产品下查看
        $templateid = "444690";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID

        //以下是发送验证码的信息
        $param = $code; //验证码 多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $mobile = $address; // 手机号
        // $uid =  config('sms.sms_uid');
        $uid =  "";
        $ucpass = new Ucpaas($options);
        $status = $ucpass->SendSms($appid, $templateid, $param, $mobile, $uid);
        if($status) {
            return true;
        }else{
            return false;
        }
    }
}
