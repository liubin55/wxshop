<?php

namespace App\Http\Controllers\Kaoshi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kaoshi;
use App\Models\Users;
use App\Models\Wechat;

class KaoshiController extends Controller
{
    //
    public function check()
    {
        //校验签名
//        if($this->checkSignature()){
//          echo $_GET['echostr'];
//        };
        //关注
        $this->responseMsg();
        //菜单
        //$this->menukaoshi();
    }
    private function  responseMsg()
    {
        //获取微信请求的所有内容
        $postStr=file_get_contents("php://input");
        //所有内容改为对象格式
        $postObj=simplexml_load_string($postStr);
        $FromUserName=$postObj->FromUserName;//请求消息的用户
        $ToUserName=$postObj->ToUserName;//“我”公众号id
        $keyword=$postObj->Content;//输入的内容
        $time=time();//时间戳
        $msgtype='text';//消息类型：文本
        $textTpl="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                </xml>";
        //判断是否为事件
        if($postObj->MsgType=='event'){
            if($postObj->Event=='subscribe'){//，关注事件
                $user=new Users;
                $user->user_name=$FromUserName;
                $user->user_code=$ToUserName;
                $user->save();
                $contentStr="已收到用户信息";//回复的内容
                $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
                echo $resultStr;
            }
        }
    }
    /*
     *@content 校验签名
     */
    private function checkSignature()
    {
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        $token='kaoshi';
        $tmpArr = array($token,$timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if($tmpStr==$signature){
            return true;
        }else{
            return false;
        }
    }
    private function menukaoshi()
    {
        $token=Kaoshi::getAccesstoken();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
        $data=[
            'button'=>[
                [
                    'name'=>'菜单',
                    'sub_button'=>[
                        [
                            'type'=>'click',
                            'name'=>'点击',
                            'key'=>'dianji'
                        ],[
                            'type'=>'view',
                            'name'=>'微商城',
                            'url'=>"http://nichousha.xyz"
                        ]
                    ]
                ],
                [
                    'name'=>'微信',
                    'sub_button'=>[
                        [
                            "type"=>"pic_sysphoto",
                            "name"=>"系统拍照发图",
                            "key"=>"rselfmenu_1_0",
                        ],[
                            "name"=>"发送位置",
                            "type"=>"location_select",
                            "key"=>"rselfmenu_2_0"
                        ]
                    ]
                ]
            ]
        ];
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        $res=Wechat::httpPost($url,$data);
        dd($res);
    }


}
