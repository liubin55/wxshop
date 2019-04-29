<?php

namespace App\Http\Controllers\Qrcode;

use App\Models\Users;
use App\Models\Wxcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class QrcodeController extends Controller
{
    //创建二维码
    public function createcode()
    {
        include public_path()."/phpqrcode.php";
        $userid=md5(time().mt_rand(111111,999999));
        $url="http://nichousha.xyz/qrcode/wxlogin/".$userid;
        @unlink(public_path()."/qrcode.png");
        \QRcode::png($url,public_path()."/qrcode.png");
        return view('qrcode.createcode',['userid'=>$userid]);
    }

    //登录
    public function wxlogin($id)
    {
        $appid=env('WXAPPID');
        $wx_url=urlencode("http://nichousha.xyz/qrcode/wxloginDo");
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$wx_url&response_type=code&scope=snsapi_userinfo&state=$id#wechat_redirect";

        return redirect($url);
    }

    //第三方授权
    public function wxloginDo(Request $request)
    {
        $code=$request->code;
        $userid=$request->state;
        $appid=env("WXAPPID");
        $appsecret=env("WXAPPSECRET");
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        $access_token=$data['access_token'];
        $openid=$data['openid'];
        //获取用户数据
        $url_user="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo=file_get_contents($url_user);
        $userinfo=json_decode($userinfo,JSON_UNESCAPED_UNICODE);
        $data=[
            'userid'=>$userid,
            'openid'=>$userinfo['openid'],
            'status'=>2
        ];
        $res=Wxcode::insert($data);
        return view('qrcode.wxloginDo',['userinfo'=>$userinfo]);
    }

    //查看状态
    public function status(Request $request)
    {
        $userid=$request->userid;
        $info=Wxcode::where('userid',$userid)->first();
        if(empty($info)){
            return 1;
        }else{
            return $info->status;
        }
    }
    //确认登录
    public function login(Request $request)
    {
        $openid=$request->openid;
        $info=Wxcode::where('openid',$openid)->update(['status'=>3]);
        if($info){
            $data=Users::where('openid',$openid)->first();
            if(empty($data)){
                $users=new Users;
                $users->openid=$request->openid;
                $users->user_name=$request->username;
                $users->save();
                $arr=[
                    'user_id'=>$users->user_id,
                    'user_tel'=>$request->openid
                ];
            }else{
                $arr=[
                    'user_id'=>$data->user_id,
                    'user_tel'=>$data['user_tel']
                ];
            }

            Redis::set('userinfo',json_encode($arr,true));
            return "登陆成功";
        }else{
            return "登录失败";
        }
    }

}
