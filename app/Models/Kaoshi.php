<?php
namespace App\Models;

class Kaoshi
{
    /*
     * 获取存储token
     */
    static public function getAccesstoken()
    {
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxeaedc828205cb11f&secret=a35d9a3c056298fabbfdb2b1d0d9e5ec";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        $token=$data['access_token'];
        $time=time()+7000;
        $path=public_path("kaoshi/token.txt");
        $str=file_get_contents($path);
        $data=json_encode(['access_token'=>$token,'time'=>$time]);
        if(empty($str)){
            file_put_contents($path,$data);
        }else{
            $arr=json_decode($str,true);
            if($arr['time']<time()){
                file_put_contents($path,$data);
            }else{
                $token=$arr['access_token'];
            }
        }

        return $token;
    }
}