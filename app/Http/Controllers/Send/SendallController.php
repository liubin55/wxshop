<?php

namespace App\Http\Controllers\Send;

use App\Models\Submedia;
use App\Models\Users;
use App\Models\Wxmedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wechat;
use CURLFile;

class SendallController extends Controller
{
    /*
     * @content 群发消息添加
     */
    public function send()
    {
        return view('sendall.send');
    }
    /*
     * @content 群消息执行添加
     */
    public function sendDo(Request $request)
    {
        if($request->hasFile('file')){
            $file=$request->file;
            $arr=Wechat::getUploads($file);
            $newfilename=$arr['newfilename'];
            $media_type=$arr['media_type'];
            //填写上传文件路径
            $filepath=public_path()."/uploads/".$newfilename;

            //获取access_token
            $token=Wechat::putAccessToken();
            //判断是否为视频上传
            if($media_type=="video") {
                //获取素材需要的media参数
                $data=array(
                    'media'=>new CURLFile(realpath($filepath)),
                    'description'=>json_encode([
                        'title'=>$request->input('title',null),
                        'introduction'=>$request->input('contents',null)
                    ]),
                );
            }else{
                //获取素材需要的media参数
                $data=array(
                    'media'=>new CURLFile(realpath($filepath)),
                );
            }
            if($request->type=='thumb'){
                $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=thumb";
            }else{
                $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$media_type";
            }
            $re=Wechat::HttpPost($url,$data);
            $data=json_decode($re,true);
            if(isset($data['errcode'])){
                die($data['errmsg']);
            }else{
                $submedia=new Submedia;
                if($request->type=='thumb'){
                    $submedia->media_id=$data['thumb_media_id'];
                }else{
                    $submedia->media_id=$data['media_id'];
                }
                if($request->type=='image'){
                    $submedia->type=$request->type;
                    $submedia->fileurl=$data['url'];
                }else{
                    $submedia->type=$request->type;
                }
                $submedia->purl=$request->input('purl',null);
                $submedia->title=$request->input('title',null);
                $submedia->contents=$request->input('contents',null);
                $submedia->gettime=time();
                $res=$submedia->save();
                if ($res) {
                    echo "<script> alert('添加成功');parent.location.href='/send/send'; </script>";
                } else {
                    echo "<script> alert('添加失败');parent.location.href='/send/send'; </script>";
                }
            }
        }else{
            $submedia=new Submedia;
            $submedia->type=$request->type;
            $submedia->contents=$request->input('contents',null);
            $submedia->gettime=time();
            $res=$submedia->save();
            if ($res) {
                echo "<script> alert('添加成功');parent.location.href='/send/send'; </script>";
            } else {
                echo "<script> alert('添加失败');parent.location.href='/send/send'; </script>";
            }
        }


    }
    /*
     * @content 群消息设置
     */
    public function sendtype()
    {
        $type=config('wxsendconfig.type');
        return view('sendall.sendtype',['type'=>$type]);
    }
    /*
     * @content 群消息执行设置
     */
    public function sendtypeDo(Request $request)
    {
        $type=$request->types;
        $path=config_path()."/wxsendconfig.php";
        $config['type']=$type;
        $str='<?php return '.var_export($config,true).";?>";
        $res=file_put_contents($path,$str);
        if($res){
            echo "设置成功";
        }else{
            echo "设置失败";
        }

    }
    /*
     * @content 添加标签
     */
    public function tagsadd()
    {
        return view('sendall.tagsadd');
    }
    /*
     * @content 创建标签
     *
     */
    public function gettags(Request $request)
    {
        $token=Wechat::putAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$token";
        $data='{   "tag" : {     "name" : "'.$request->contents.'"   } }';
        $re=Wechat::httpPost($url,$data);
        $res=json_decode($re,true);
        if(!array_key_exists('errcode',$res)){
            echo "<script> alert('添加标签成功');parent.location.href='/send/tagsadd'; </script>";
        }else{
            echo "<script> alert('添加标签失败');parent.location.href='/send/tagsadd'; </script>";
        }
    }
    /*
     * @content 打标签
     */
    public function tagsman(Request $request)
    {
        $data=$request->all();
        unset($data['_token']);
        unset($data['tags']);
        $data=array_values($data);
        $data=json_encode([
            "openid_list" =>$data,
            "tagid"=>$request->tags
        ],JSON_UNESCAPED_UNICODE);
        $token=Wechat::putAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=$token";
        $re=Wechat::httpPost($url,$data);
        $res=json_decode($re,true);
        if($res['errcode']==0){
            echo "<script> alert('加入标签成功');parent.location.href='/send/openidlist'; </script>";
        }else{
            echo "<script> alert('加入标签失败');parent.location.href='/send/openidlist'; </script>";
        }
    }
    /*
     * @content 发布群消息
     */
    public function sends()
    {
        //获取标签
        $data=$this->tags();
        return view('sendall.sends',['data'=>$data['tags']]);
    }
    private function tags()
    {
        //获取token
        $token=Wechat::putAccessToken();
        //获取标签
        $url="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$token";
        $data=file_get_contents($url);
        $data=json_decode($data,true);
        return $data;
    }
    //群发消息
    public function  sendall(Request $request)
    {
        $token=Wechat::putAccessToken();
        if($request->type=='标签'){
            //根据标签群发
            $url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$token";
            $data='{
                   "filter":{
                      "is_to_all":false,
                      "tag_id":'.$request->tags.'
                   },
                   "text":{
                      "content":"'.$request->contents.'"
                   },
                    "msgtype":"text"
                }';
        }else if($request->type=='openid'){
            $type=config('wxsendconfig.type');
            $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$token";
            $openid=Wechat::getOpenid();
            switch ($type)
            {
                case 'text':
                    $data=[
                        'touser'=>$openid,
                        'msgtype'=>'text',
                        'text'=>[
                            'content'=>$request->contents
                        ]
                    ];
                    break;
                case 'mpnews':
                    $media_id=Wechat::getMediaid();
                    $data=[
                        "touser"=>$openid,
                        "mpnews"=>[
                            "media_id"=>$media_id
                        ],
                        "msgtype"=>"mpnews",
                        "send_ignore_reprint"=>0
                    ];
                    break;
                case 'image':
                    $info=Submedia::where('type','image')->orderBy('id','desc')->first();
                    $media_id=$info['media_id'];
                    $data=[
                        "touser"=>$openid,
                        "image"=>[
                            "media_id"=>$media_id
                        ],
                        "msgtype"=>"image",
                    ];
                    break;
                case 'voice':
                    $info=Submedia::where('type','voice')->orderBy('id','desc')->first();
                    $media_id=$info['media_id'];
                    $data=[
                        "touser"=>$openid,
                        "voice"=>[
                            "media_id"=>$media_id
                        ],
                        "msgtype"=>"voice",
                    ];
                    break;
                case 'mpvideo':
                    $info=Submedia::where('type','video')->orderBy('id','desc')->first();
                    $media_id=$info['media_id'];
                    $data=[
                        "touser"=>$openid,
                        "mpvideo"=>[
                            "media_id"=>$media_id,
                            "title"=>"标题",
                            "description"=>"内容"
                        ],
                        "msgtype"=>"mpvideo"
                    ];
                    break;
                default:
                    //表达式的值不等于时执行的代码;
            }
            $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        }else{
            echo "<script> alert('请选择发送类型');parent.location.href='/send/sends'; </script>";
        }
        $re=Wechat::httpPost($url,$data);
        $res=json_decode($re,true);
        if($res['errcode']==0){
            echo "<script> alert('发布成功');parent.location.href='/send/sends'; </script>";
        }else{
            echo "<script> alert('发布失败');parent.location.href='/send/sends'; </script>";
        }
    }

    //计划任务群发
    public function sendweatherall()
    {
        $token=Wechat::putAccessToken();
        $data=Wechat::getWeather("北京");
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$token";
        $openid=Wechat::getOpenid();
        $data=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>[
                'content'=>$data
            ]
        ];
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        $re=Wechat::httpPost($url,$data);
    }
    /*
     *
     * @content openid 列表
     */
    public function openidlist()
    {
        //获取标签
        $data=$this->tags();
        //openid列表
        $openid=Wechat::getOpenid();
        $token=Wechat::putAccessToken();
        static $info=[];
        foreach ($openid as $v){
            $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$v";
            $info[]=json_decode(file_get_contents($url),true);
        }
        return view('sendall.openidlist',['info'=>$info],['tags'=>$data['tags']]);
    }
    /*
     * @标签列表
     */
    public function tagslist()
    {
        //获取标签
        $data=$this->tags();
        return view('sendall.tagslist',['data'=>$data['tags']]);
    }
    /*
     * @content 删除标签
     */
    public function tagsdel(Request $request)
    {
        $id=$request->tagsid;
        $token=Wechat::putAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=$token";
        $data='{   "tag":{        "id" : '.$id.'   } }';
        $re=Wechat::httpPost($url,$data);
        $res=json_decode($re,true);
        if($res['errcode']==0){
            echo json_encode(['font'=>'删除成功','code'=>1]);
        }else{
            echo json_encode(['font'=>'删除失败','code'=>2]);
        }
    }


    /*
     * @content 微信登录
     */
    public function wxlogin(Request $request)
    {
        //网站和第三方建立连接
        //用户发起请求，选择登录方式
        //网站请求第三方服务提供商
        //第三方请求用户授权
        //用户授权成功
        //第三方返回code给网站
        //网站根据code生成access_token
        //网站使用token请求第三方服务提供商
        //第三方返回非关键的用户信息
        //成功
        //获取code
        $code=$request->code;
        //获取access_token
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
        //判断是否绑定
        $re=Users::where('openid',$userinfo['openid'])->first();
        if(empty($re)){
            return view('wechat.wxregister',['userinfo'=>$userinfo]);
        }else{
            session(['user_id'=>$re->user_id,'user_name'=>$re->user_name]);
            return redirect('/');
        }
    }
    /*
     * @绑定用户
     */
    public function wxloginDo(Request $request)
    {
        $user_tel=$request->userMobile;
        $openid=$request->openid;
        $res=Users::where('user_tel',$user_tel)->update(['openid'=>$openid]);
        if($res){
            echo json_encode(['font'=>'绑定成功','code'=>1]);
        }else{
            echo json_encode(['font'=>'绑定失败','code'=>2]);
        }
    }



//本地随机数
//    public function randmun()
//    {
////        $arr=[];
////        for ($i=0;$i<=10000;$i++){
////            $arr[]=$this->random(5);
////            array_unique($arr);
////        }
////
////        print_r($arr);
//        ############### 1 生成任意不重复五位数10000个 #######################
//        $min = 10000;
//        $max = 100000;
//        for($min;$min<=$max;$min++){
//            $array[] = $min;
//        }
//        shuffle($array);
//         $array = array_slice($array,0,10000);
//         ############### 2 将生成的数存入文件中，每个文件存储1000个 #######################
//        $num = count($array)/10;
//         for($i = 1;$i<=10;$i++){
//             if($i == 1){
//                $start = 0;
//             }else{
//                $start = ($i-1)*1000;
//             }
//            file_put_contents('D:\text'.$i.'.txt',implode("\r\n",array_slice($array,$start,$num)));
//         }
//        ############### 3 查找一个数字在不在文件中，如果在给出在哪个文件里，不在给出提示 #######################
//        $num = '45850'; //要查询的 五位数字
//         $fileName = false;
//         for($i = 1;$i<=10;$i++){
//             $file = file_get_contents('D:\text'.$i.'.txt');
//             if(strpos($file,$num) !== false){
//                $fileName = 'text'.$i;
//             }
//         }
//         if($fileName){
//
//            echo '存在<br>';
//            echo $fileName.'.txt';
//         }else{
//             echo '不存在';
//         }
//    }

}
