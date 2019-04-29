<?php

namespace App\Http\Controllers\Wechat;

use App\Models\Order;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wechat;
use CURLFile;
use App\Models\Wxmedia;
use App\Models\Submedia;
use App\Models\Goods;
use App\Models\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
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
     * @content 素材列表
     */
    public function medialist(Request $request)
    {
        $page=$request->input('page',1);
        //redis存储缓存
        if(Redis::exists($page)){
            $data=Redis::get($page);
        }else{
            $data=Submedia::paginate(5);;
            $data=encrypt($data);
            Redis::set($page,$data);
            Redis::expire($page,100);
        }
        $data=decrypt($data);
        return view('wechat.medialist',['data'=>$data]);
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
            echo "设置成功";
        }else{
            echo "设置失败";
        }
    }
    /*
     * 微信公众号
     */
    public function index()
    {
        //校验微信签名
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
     * @content 添加自定义菜单
     *
     */

    public function menuadd()
    {
        $menu=Menu::where('pid',0)->get()->toArray();
        return view('wechat.menuadd',['menu'=>$menu]);
    }
    /*
     * @content 执行添加
     */
    public function menuaddDo(Request $request)
    {
        $data=[
            'name'=>$request->input('name'),
            'type'=>$request->input('type',null),
            'pid'=>$request->pid,
            'key'=>$request->input('key',null),
            'url'=>$request->input('url',null)
        ];
        $res=Menu::insert($data);
        if($res){
            echo "<script> alert('添加成功');parent.location.href='/wechat/menuadd'; </script>";
        }else{
            echo "<script> alert('添加失败');parent.location.href='/wechat/menuadd'; </script>";
        }
    }
    /*
     * @content 自定义菜单列表
     */
    public function menu()
    {
        //自定义菜单启动
        $res=$this->menutoken();
        $this->menumatch();
        //查询显示列表
        $data=Menu::all()->toArray();
        $menuInfo=Wechat::getMenu($data,0);
        //获取token
        $token=Wechat::putAccessToken();
        //查询当前自定义菜单
        $url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$token";
        $obj=file_get_contents($url);
        if(Cache::has('data')){
            $obj=Cache::get('data');
        }else{
            $obj=file_get_contents($url);
            Cache::put('data',$obj,1000);
        }
        return view('wechat.menu',['menuInfo'=>$menuInfo],['data'=>$obj]);
    }
    /*
     * @content 删除菜单
     *
     */
    public function menudel(Request $request)
    {
        $res=Menu::where('m_id',$request->m_id)->delete();
        if($res){
            echo json_encode(['font'=>'删除成功','code'=>'1']);
        }else{
            echo json_encode(['font'=>'删除失败','code'=>'2']);
        }
    }
    /*
     * @content 修改菜单
     */
    public function menuupd($id)
    {
        $menu=Menu::where('pid',0)->get()->toArray();
        $data=Menu::where('m_id',$id)->first();
        return view('wechat/menuupd',['data'=>$data],['menu'=>$menu]);
    }
    /*
     * @content执行修改菜单
     */
    public function menuupdDo(Request $request)
    {
        $data=$request->all();
        $res=Menu::where('m_id',$request->m_id)->first();
        if($request->type=='view'){
            $data=[
                'name'=>$request->input('name',null),
                'type'=>$request->input('type',null),
                'key'=>null,
                'url'=>$request->input('url',null),
                'pid'=>$request->input('pid',null)
            ];
        }elseif($request->type=='click'){
            $data=[
                'name'=>$request->input('name',null),
                'type'=>$request->input('type',null),
                'url'=>null,
                'key'=>$request->input('key',null),
                'pid'=>$request->input('pid',null)
            ];
        }else{
            $data=[
                'name'=>$request->input('name',null),
                'type'=>$request->input('type',null),
                'url'=>null,
                'key'=>null,
                'pid'=>0
            ];
        }
        $re=Menu::where('m_id',$request->m_id)->update($data);
        if($re){
            if($res->status==1){
                Cache::flush();
            }
            echo "<script> alert('修改成功');parent.location.href='/wechat/menu'; </script>";
        }else{
            echo "<script> alert('修改失败');parent.location.href='/wechat/menuupd'; </script>";
        }

    }
    /*
     * \@content 修改菜单状态
     */
    public function menustatus(Request $requset)
    {
        $status=$requset->status;
        if($status==1){
            $obj=Menu::where('m_id',$requset->m_id)->first();
            if($obj->pid==0){
                $count=Menu::where('status',1)->where('pid',0)->count();
               if($count>=3){
                   echo json_encode(['font'=>'一级菜单不能超过3个','code'=>'2']);
               }else{
                   $res=Menu::where('m_id',$requset->m_id)->update(['status'=>$status]);
                   if($res){
                       Cache::flush();
                       echo json_encode(['font'=>'开启成功','code'=>'1']);
                   }else{
                       echo json_encode(['font'=>'开启失败','code'=>'2']);
                   }
               }
            }else{
                $arr=Menu::where('m_id',$obj->pid)->first();
                if($arr->status==1){
                    $count=Menu::where('status',1)->where('pid',$requset->m_id)->count();
                    if($count>=5){
                        echo json_encode(['font'=>'二级菜单不能超过5个','code'=>'2']);
                    }else{
                        $res=Menu::where('m_id',$requset->m_id)->update(['status'=>$status]);
                        if($res){
                            Cache::flush();
                            echo json_encode(['font'=>'开启成功','code'=>'1']);
                        }else{
                            echo json_encode(['font'=>'开启失败','code'=>'2']);
                        }
                    }
                }else{
                    echo json_encode(['font'=>'请先开启一级菜单然后再开启此菜单','code'=>'1']);
                }
            }
        }else{
            $res=Menu::where('m_id',$requset->m_id)->update(['status'=>$status]);
            if($res){
                Cache::flush();
                echo json_encode(['font'=>'关闭成功','code'=>'1']);
            }else{
                echo json_encode(['font'=>'关闭失败','code'=>'2']);
            }
        }
    }
    /*
     * @content 自定义菜单接口
     * 
     */
    private function menutoken()
    {
        //获取token
        $token=Wechat::putAccessToken();
        //自定义菜单接口
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
        $data=$this->getdata();
        $obj=Wechat::httpPost($url,$data);
        return $obj;
    }

    /*
     * @content 个性化菜单
     */
    private function menumatch()
    {
        $token=Wechat::putAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=$token";
        $data='{
    "button": [
        {
            "type": "click", 
            "name": "今日歌曲", 
            "key": "V1001_TODAY_MUSIC"
        }, 
        {
            "name": "菜单", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "搜索", 
                    "url": "http://www.soso.com/"
                }, 
                {
                    "type": "miniprogram", 
                    "name": "wxa", 
                    "url": "http://mp.weixin.qq.com", 
                    "appid": "wx286b93c14bbf93aa", 
                    "pagepath": "pages/lunar/index"
                }, 
                {
                    "type": "click", 
                    "name": "赞一下我们", 
                    "key": "V1001_GOOD"
                }
            ]
        }
    ], 
    "matchrule": {
        "sex": "2", 
    }
}';
        $re=Wechat::httpPost($url,$data);
        return $re;
    }
    /*
     * @content 拼接借口需要的data
     */
    private function getdata()
    {
        $menu=Menu::where('status',1)->where('pid',0)->get()->toArray();
        $data=[];
        foreach ($menu as $key => $val){
            $data[$key]['name'] = $val['name'];
            // 有二级菜单的时候 一级不需要链接 留空
            if(empty($val['type'])) {
                // 找二级菜单的信息
                $son =  Menu::where('pid',$val['m_id'])->where('status',1)->get()->toArray();
                if(!empty($son)){
                    foreach ($son as $k =>  $value) {
                        if($value['type']=='view'){
                            $data[$key]['sub_button'][] = [
                                'type' => 'view',
                                'url' => $value['url'],
                                'name' => $value['name'],
                            ];
                        }else if($value['type']=='click'){
                            $data[$key]['sub_button'][] = [
                                'type' => 'click',
                                'key' => $value['key'],
                                'name' => $value['name'],
                            ];
                        }

                    }
                }
            }else{
                if($val['type']=='view'){
                    $data[$key]['type'] = 'view';
                    $data[$key]['url'] = $val['url'];
                }elseif($val['type']=='click'){
                    $data[$key]['type'] = 'click';
                    $data[$key]['key'] = $val['key'];
                }
            }
        }
        rsort($data);
        $data = ['button'=>$data];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }

    /*
     * @content 推送消息
     */
    private function responseMsg()
    {
        //获取微信请求的所有内容
        $postStr=file_get_contents("php://input");
        //所有内容改为对象格式
        $postObj=simplexml_load_string($postStr,'SimpleXMLElement', LIBXML_NOCDATA);
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
        $type=$postObj->MsgType;
        $arr=[];
//        //文本存文件
//        if($type=='text'){
//            $arr[]=[
//                'openid'=>$FromUserName,
//                'content'=>$keyword,
//                 'time'=>$time,
//            ];
//            $arr=json_encode($arr,JSON_UNESCAPED_UNICODE);
//            $filename=public_path()."/recode/".date("Ymd")."/recode.php";
//            file_put_contents($filename,$arr,FILE_APPEND);
//            chown($filename,0777);
//        }
//        //存储图片
//        if($type=='image'){
//            $picurl=$postObj->PicUrl;
//            $img=file_get_contents($picurl);
//            $file=public_path()."/wx/".date("Ymd").'/'.time().'.jpg';
//            $res=file_put_contents($file,$img);
//            chown($file,0777);
//            if($res){
//                $contentStr="存储成功";//回复的内容
//            }else{
//                $contentStr="存储失败";//回复的内容
//            }
//            $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
//            echo $resultStr;
//            exit();
//        }
        //判断是否为事件
        if($postObj->MsgType=='event'){
            if($postObj->Event=='subscribe'){//，关注事件
//                $type=config('wxconfig.subscribe');
//                $types=ucfirst($type);
//                Wechat::$types($FromUserName,$ToUserName,$time,$type);
                $re=Users::where('openid',$FromUserName)->first();
                if(empty($re)){
                    $contentStr="尊敬的用户您好，乐美微商城感谢您的使用，首次关注需要您绑定本网站的账户，以便更方便的为您提供服务 <a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxeaedc828205cb11f&redirect_uri=http%3A%2F%2Fnichousha.xyz%2Fsend%2Fwxlogin&response_type=code&scope=snsapi_userinfo&state=liubin980211#wechat_redirect'>点击绑定</a>";//回复的内容
                }else{
                    $contentStr=$re->user_name."欢迎您回来";
                }
                $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
                echo $resultStr;
                exit();
            }else if($postObj->Event=='CLICK'&&$postObj->EventKey=='GETORDER'){//点击事件
               Wechat::getOrder($FromUserName,$ToUserName,$time);
               exit();
            }
        }
        if($keyword=='最新商品'){
            Wechat::getGoods($FromUserName,$ToUserName,$time);
        }else if (strstr($keyword, "天气")){//发送查看有没有天气两个字
            $contentStr=Wechat::getWeather($keyword);
            $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
            echo $resultStr;
            exit();
        }else if (strstr($keyword, "订单号")){//发送查看有没有订单号
            $num = trim(str_replace('订单号', '', $keyword));
            $data=Order::where('order_no',$num)->first();
            if($data!=''){
                Wechat::gettemplate($FromUserName,$num);
            }else{
                $contentStr="你是想要查询订单号么，查询格式：订单号1234";
                $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
                echo $resultStr;
                exit();
            }
//        }elseif(strstr($keyword, "微信登录")){
//            $appid=env('WXAPPID');
//            $wx_url=urlencode("http://nichousha.xyz/send/wxlogin");
//            $contentStr="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$wx_url&response_type=code&scope=snsapi_userinfo&state=liubin980211#wechat_redirect";
//            $resultStr=sprintf($textTpl,$FromUserName,$ToUserName,$time,$msgtype,$contentStr);
//            echo $resultStr;
//            exit();
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
