<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wechat;
use CURLFile;
use App\Models\Wxmedia;
use App\Models\Submedia;
use App\Models\Goods;
class WechatController extends Controller
{
    /*
    * @content 显示上传文件临时素材
    */
    public function uploads()
    {
        return view ('wechat.uploads');
    }
    /*
    * @content 执行上传文件到临时微信素材
    */
    public function uploadsDo(Request $request)
    {
        if($request->hasFile('file')){
            $file=$request->file;
            $arr=Wechat::getUploads($file);
            $newfilename=$arr['newfilename'];
            $media_type=$arr['media_type'];
            //填写上传文件路径
            $filepath=public_path()."/uploads/".$newfilename;
            //获取素材需要的media参数
            $data=array(
                'media'=>new CURLFile(realpath($filepath)),
            );
            //获取access_token
            $token=Wechat::putAccessToken();
            $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=$media_type";
            $re=Wechat::HttpPost($url,$data);
            $data=json_decode($re,true);
            if(isset($data['errcode'])){
                die($data['errmsg']);
            }else{
                $wxmedia=new Wxmedia;
                $wxmedia->media_id=$data['media_id'];
                $wxmedia->type=$request->type;
                $wxmedia->purl=$request->input('purl',null);
                $wxmedia->title=$request->input('title',null);
                $wxmedia->contents=$request->input('contents',null);
                $wxmedia->gettime=time()+25000;
                $wxmedia->image="uploads/".$newfilename;
                $res=$wxmedia->save();
                if($res){
                    echo "<script> alert('添加成功');parent.location.href='/wechat/uploads'; </script>";
                }else{
                    echo "<script> alert('添加失败');parent.location.href='/wechat/uploads'; </script>";
                }
            }
        }else{
            $wxmedia=new Wxmedia;
            $wxmedia->type=$request->type;
            $wxmedia->contents=$request->input('contents',null);
            $wxmedia->gettime=time()+25000;
            $res=$wxmedia->save();
            if($res){
                echo "<script> alert('添加成功');parent.location.href='/wechat/uploads'; </script>";
            }else{
                echo "<script> alert('添加失败');parent.location.href='/wechat/uploads'; </script>";
            }
        }

    }
    /*
    * @content 显示上传文件永久素材
    */
    public function subuploads()
    {
        return view ('wechat.subuploads');
    }
    /*
    * @content 执行上传文件到永久微信素材
    */
    public function subuploadsDo(Request $request)
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
            }else {
                //获取素材需要的media参数
                $data=array(
                    'media'=>new CURLFile(realpath($filepath)),
                );
            }
            $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$media_type";
            $re=Wechat::HttpPost($url,$data);
            $data=json_decode($re,true);
            if(isset($data['errcode'])){
                die($data['errmsg']);
            }else{
                $submedia=new Submedia;
                $submedia->media_id=$data['media_id'];
                if($media_type=='image'){
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
                if($res){
                    echo "<script> alert('添加成功');parent.location.href='/wechat/subuploads'; </script>";
                }else{
                    echo "<script> alert('添加失败');parent.location.href='/wechat/subuploads'; </script>";
                }
            }
        }else{
            $submedia=new Submedia;
            $submedia->type=$request->type;
            $submedia->purl=$request->input('purl',null);
            $submedia->title=$request->input('title',null);
            $submedia->contents=$request->input('contents',null);
            $submedia->gettime=time();
            $res=$submedia->save();
            if($res){
                echo "<script> alert('添加成功');parent.location.href='/wechat/subuploads'; </script>";
            }else{
                echo "<script> alert('添加失败');parent.location.href='/wechat/subuploads'; </script>";
            }
        }

    }
    /*
     * @content 首次关注回复类型设置
     *
     */
    public function subtype()
    {
        $type=config('wxconfig.subscribe');
        return view('wechat.subtype',['type'=>$type]);
    }
    /*
     * @content 修改首次关注类型
     */
    public function subtypeDo(Request $request)
    {
        $type=$request->types;
        $path=config_path()."/wxconfig.php";
        $config['subscribe']=$type;
        $str='<?php return '.var_export($config,true).";?>";
        $res=file_put_contents($path,$str);
        if($res){
            echo "修改成功";
        }else{
            echo "修改失败";
        }
    }
    /*
     * 微信公众号
     */
    public function index()
    {
//        //校验微信签名
//        $echostr=$_GET['echostr'];
//        if($this->checkSignature()){
//            echo $echostr;
//        }
        //推送消息
        $this->responseMsg();
    }
    /*
     * @content 校验微信签名
     */
    private function checkSignature()
    {
        //接收所有参数
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        //定义token参数与微信服务器的一致
        $token='weixin';
        //参数数组化下面分割成加密形式
        $tmpArr = array($token,$timestamp,$nonce);
        //把值作为字符串处理
        sort($tmpArr, SORT_STRING);
        //链接成字符串
        $tmpStr = implode( $tmpArr );
        //shal函数使用美国 Secure Hash 算法 1。可生成或验证报文签名的签名算法。对报文摘要进行签名，
        //而不是对报文进行签名，这样可以提高进程效率，因为报文摘要的大小通常比报文要小很多
        //。数字签名的验证者必须像数字签名的创建者一样，使用相同的散列算法
        $tmpStr = sha1( $tmpStr );
        //判断微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
        if($tmpStr==$signature){
            return true;
        }else{
            return false;
        }
    }

    /*
     * @content 推送消息
     */
    private function responseMsg()
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
                $type=config('wxconfig.subscribe');
                $types=ucfirst($type);
                Wechat::$types($FromUserName,$ToUserName,$time,$type);
            }
        }
        if($keyword=='你好'){
            $contentStr="你好，有什么问题么";//回复的内容
            $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
            echo $resultStr;
            exit();
        }else if (strstr($keyword, "天气")){//发送查看有没有天气两个字
            $contentStr=Wechat::getWeather($keyword);
            $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
            echo $resultStr;
            exit();
        }else if($keyword=="图片"){
            Wechat::Image($FromUserName,$ToUserName,$time,'image');
        }else if($keyword=="图文"){
            Wechat::News($FromUserName,$ToUserName,$time,'news');
        }else if($keyword=="语音"){
            Wechat::Voice($FromUserName,$ToUserName,$time,'voice');
        }else if($keyword=="视频"){
            Wechat::Video($FromUserName,$ToUserName,$time,'video');
        }else{
            $goodsinfo=Goods::where('goods_name',$keyword)->first();
            if(!empty($goodsinfo)){
                //商品名称回复
                $contentStr="http://nichousha.xyz/shopcontent/".$goodsinfo->goods_id;//回复的内容内容
                $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
                echo $resultStr;
                exit();
            }else{
                //图灵机器人自动回复
                $contentStr=Wechat::getTuling($keyword);//回复的内容内容
                $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
                echo $resultStr;
                exit();
            }
        }
    }
}
