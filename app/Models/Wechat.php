<?php
namespace App\Models;
use App\Models\Submedia;
use Illuminate\Support\Facades\Storage;
class Wechat
{
    /*
     * @content curl模拟post请求http和https
     * $tuling_url 接口地址
     * $post_data 传输的数据
     */
    static public function httpPost($tuling_url,$post_data)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $tuling_url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
       return $data;
    }
    /*
     * @content 天气接口
     * $tianqi_url 接口地址
     */
    static public function httpGet($tianqi_url)
    {
       //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $tianqi_url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
       return $data;
    }
    /*
     * 递归查数据
     */
    static public function getMenu($menu,$pid,$level=0){
        static $arr=[];
        foreach($menu as $k=>$v){
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                self::getMenu($menu,$v['m_id'],$v['level']+1);
            }
        }
        return $arr;
    }
    /*
     * @content 获取天气
     * $keyword接受的消息
     */
    static public function getWeather($keyword)
    {
        //这里用空格取代$keyword中的天气二字。查询天气，发送天气加城市名，如“茂名天气”
        $city = trim(str_replace('天气', '', $keyword));
        $tianqi_url="http://api.map.baidu.com/telematics/v3/weather?location=".$city."&output=json&ak=iraSdkYjE7OX39QL2Y36MFgQecUpVGX3";
        $re=self::httpGet($tianqi_url);
        $data = json_decode($re,true);
        if($data['status']=='success'){
            $cityname=$data['results']['0']['currentCity'];
            $citydate=$data['results']['0']['weather_data']['0']['date'];
            $cityweek=$data['results']['0']['weather_data']['0']['weather'];
            $citytemp=$data['results']['0']['index']['0']['des'];
            $citypm=$data['results']['0']['pm25'];
            $cityfengxiang=$data['results']['0']['weather_data']['0']['wind'];
            $citytype=$data['results']['0']['weather_data']['0']['temperature'];
            $out=$cityname."天气"."\n\r".$citydate."\n\r"."今日温度：".$citytype."\n\r"."天气情况：".$cityweek."\n\r".$citytemp."\n\r"."PM2.5值：".$citypm."\n\r风向风力：".$cityfengxiang;
        }else{
            $out='地球上可没有这种地方哦';
        }
        
        return $out;
    }
    /*
     * @content 图灵机器人
     *  $keyword接受的消息
     *
     * */
    static public function getTuling($keyword)
    {
        $data=[
            'perception'=>[
                'inputText'=>[
                    'text'=>$keyword
                ]
            ],
            'userInfo'=>[
                'apiKey'=>env("TULING_APIKEY"),
                'userId'=>env("TULING_USERID"),
            ],
        ];
        $data=json_encode($data);
        $tuling_url='http://openapi.tuling123.com/openapi/api/v2';
        $re=self::httpPost($tuling_url,$data);
        $msg=json_decode($re,true)['results'][0]['values']['text'];

        return $msg;
    }
    //存储获取微信access_token
    static public function putAccessToken()
    {
        $file=public_path()."/wx/token.txt";
        $str=file_get_contents($file);
        if(!empty($str)){
            $arr=json_decode($str,true);
            if($arr['expire'] > time()){
                $token=$arr['token'];
            }else{
                $data=json_decode(self::getAccessToken(),true);
                $token=$data['access_token'];
                $exprice=time()+7000;
                $data=json_encode(['token'=>$token,'expire'=>$exprice]);
                file_put_contents($file,$data);
            }
        }else{
            $data=json_decode(self::getAccessToken(),true);
            $token=$data['access_token'];
            $exprice=time()+7000;
            $data=json_encode(['token'=>$token,'expire'=>$exprice]);
            file_put_contents($file,$data);
        }
        return $token;
    }
    //通过接口获取微信access_token
    static private function getAccessToken()
    {
        $appid=env("WXAPPID");
        $appsecret=env("WXAPPSECRET");
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $re=file_get_contents($url);
        return $re;
    }
    //获取上传素材指定的文件类型
    static public function getType($type)
    {
        $arr=explode('/',$type);
        $ty=$arr[0];
        $arr_type=[
            'image'=>'image',
            'audio'=>'voice',
            'video'=>'video'
        ];
        return $arr_type[$ty];
    }
    /*
     * @content 上传文件
     */
    static public function getUploads($file)
    {
        //获取上传文件类型
        $type=$file->getClientMimeType();
        //修改成素材指定的文件类型
        $media_type=Wechat::getType($type);
        //获取文件后缀名
        $ext=$file->getClientOriginalExtension();
        //获取临时文件位置
        $path=$file->getRealPath();
        //生成新的文件名
        $newfilename=date('Ymd')."/".mt_rand(100000,9999999).'.'.$ext;
        //移动文件到本地
        Storage::disk('uploads')->put($newfilename,file_get_contents($path));

        return ['media_type'=>$media_type,'newfilename'=>$newfilename];
    }

    //回复图片
    static public function Image($FromUserName,$ToUserName,$time,$type)
    {
        $textTpl="<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                          <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                          </Image>
                        </xml>";
        $msgtype=$type;
        $info=Submedia::where('type',$type)->orderBy('id','desc')->first();
        $media_id=$info->media_id;
        $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$media_id);
        echo $resultStr;
        exit();
    }
    //回复图文
    static public function News($FromUserName,$ToUserName,$time,$type)
    {
        $textTpl="<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                          <ArticleCount>1</ArticleCount>
                          <Articles>
                            <item>
                              <Title><![CDATA[%s]]></Title>
                              <Description><![CDATA[%s]]></Description>
                              <PicUrl><![CDATA[%s]]></PicUrl>
                              <Url><![CDATA[%s]]></Url>
                            </item>
                          </Articles>
                        </xml>";
        $msgtype=$type;
        $info=Submedia::where('type',$type)->orderBy('id','desc')->first();
        $media_id=$info->fileurl;
        $title=$info->title;
        $Description=$info->contents;
        $url=$info->purl;
        $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$title,$Description,$media_id,$url);
        echo $resultStr;
        exit();
    }
    //回复语音
    static public function Voice($FromUserName,$ToUserName,$time,$type)
    {
        $textTpl="<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                           <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                          </Voice>
                        </xml>";
        $msgtype=$type;
        $info=Submedia::where('type',$type)->orderBy('id','desc')->first();
        $media_id=$info->media_id;
        $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$media_id);
        echo $resultStr;
        exit();
    }
    //回复视频
    static public function Video($FromUserName,$ToUserName,$time,$type)
    {
        $textTpl="<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%s</CreateTime>
                          <MsgType><![CDATA[%s]]></MsgType>
                          <Video>
                              <MediaId><![CDATA[%s]]></MediaId>
                              <Title><![CDATA[%s]]></Title>
                              <Description><![CDATA[%s]]></Description>
                          </Video>
                        </xml>";
        $msgtype=$type;
        $info=Submedia::where('type',$type)->orderBy('id','desc')->first();
        $media_id=$info->media_id;
        $title=$info->title;
        $Description=$info->contents;
        $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$media_id,$title,$Description);
        echo $resultStr;
        exit();
    }
    /*
     * @content 回复文本
     */
    static public function Text($FromUserName,$ToUserName,$time,$type)
    {
        $textTpl="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                </xml>";
        $msgtype=$type;
        $info=Submedia::where('type',$type)->orderBy('id','desc')->first();
        $contentStr=$info->contents;//关注回复的内容内容
        $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
        echo $resultStr;
        exit();
    }
}

