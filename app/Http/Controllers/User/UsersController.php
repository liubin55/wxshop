<?php

namespace App\Http\Controllers\User;

use App\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //登录
    public function login()
    {
        return view('login');
    }
    //登录
    public function loginDo(Request $request)
    {
        $usermodel=new Users;
        if(empty($request->txtAccount)){
            echo json_encode(['font'=>'账户不能为空', 'code'=>2]);exit;
        }
        if(empty($request->txtPassword)){
            echo json_encode(['font'=>'密码不能为空', 'code'=>2]);exit;
        }
        if(empty($request->verifycode)){
            echo json_encode(['font'=>'验证码不能为空', 'code'=>2]);exit;
        }else if($request->verifycode!=session('verifycode')){
            echo json_encode(['font'=>'验证码错误', 'code'=>2]);exit;
        }
        $where=[
            'user_tel'=>$request->txtAccount
        ];
        $data=$usermodel->where($where)->first();
        if(!empty($data)){
            $etime=60-ceil((time()-$data->error_time)/60);
            if(decrypt($data->user_pwd)==$request->txtPassword){
                if((time()-$data->error_time)<3600&&$data->error_num>=3){
                    echo json_encode(['font'=>'密码已锁定，你还有'.$etime.'分钟可登陆', 'code'=>2]);
                }else{
                    $data->error_num=0;
                    $data->error_time=null;
                    $data->save();
                    // 存储数据到 session...
                    session(['user_id' =>$data->user_id,'user_name'=>$data->user_tel]);
                    echo json_encode(['font'=>'登陆成功', 'code'=>1]);
                }
            }else{
                if(time()-$data->error_time>3600&&$data->error_num>=3){
                    $data->error_num=1;
                    $data->error_time=time();
                    $data->save();
                    echo json_encode(['font'=>'密码错误，你还可以输入2次', 'code'=>2]);
                }else{
                    if($data->error_num>=3){
                        echo json_encode(['font'=>'密码已锁定，你还有'.$etime.'分钟可登陆', 'code'=>2]);
                    }else{
                        $data->error_num=$data->error_num+1;
                        $data->error_time=time();
                        $data->save();
                        $arr=$usermodel->where($where)->first();
                        $num=3-$arr->error_num;
                        echo json_encode(['font'=>'密码已锁定，你还可以输入'.$num.'次', 'code'=>2]);
                    }
                }
            }
        }else{
            echo json_encode(['font'=>'账号密码错误', 'code'=>2]);exit;
        }
    }
    //注册
    public function register()
    {
        return view('register');
    }
    /*
     * @content 唯一性
     */
    public function registerajax(Request $request)
    {
        $data=Users::where('user_tel',$request->user_name)->first();
        if(!empty($data)){
            echo 1;
        }else{
            echo 2;
        }
    }
    //注册成功
    public function registerDo(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'userMobile'=>[
                'required',
                'unique:users,user_tel',
                'regex:/^1(3[0-9]|4[57]|5[0-35-9]|8[0-9]|7[06-8])\d{8}$/'
            ],
            'pwd'=>[
                'required',
                'min:6',
                'max:12',
                'regex:/^[a-zA-Z0-9]{6,12}$/'
            ],
            'conpwd'=>'required|same:pwd',
            'usercode'=>'required'
        ],[
            'userMobile.required'=>'手机号不能为空',
            'userMobile.unique'=>'手机号已存在',
            'userMobile.regex'=>'手机号格式不正确',
            'pwd.required'=>'密码不能为空',
            'pwd.min'=>'密码最少6位',
            'pwd.max'=>'密码最长12位',
            'pwd.regex'=>'密码由数字字母组成',
            'conpwd.required'=>'确认密码不能为空',
            'conpwd.same'=>'确认密码与密码不一致',
            'usercode.required'=>'验证码不能为空'

        ]);
       if($validate->fails()){
            $errors  = $validate->errors()->getMessages();
            foreach ($errors as $v){
               $str =  implode('&&',$v);
            }
            echo json_encode(['font'=>$str,'code'=>2]);exit;
        }
        $userMobile=$request->userMobile;
        $usercode=$request->usercode;
        $userpwd=$request->pwd;
        $code=cache('code');
        if($code==''){
            echo json_encode(['font'=>'验证码已过期，请从新获取','code'=>2]);exit;
        }else if($code==$usercode){
            $address=session('usertel');
            if($address==$userMobile){
                $usermodel=new Users;
                $usermodel->user_tel=$userMobile;
                $usermodel->user_pwd=encrypt($userpwd);
                $usermodel->user_code=$usercode;
                $res=$usermodel->save();
                if($res){
                    session(['user_id'=>$usermodel->user_id,'user_name'=>$userMobile]);
                    echo json_encode(['font'=>'注册成功','code'=>1]);
                }else{
                    echo json_encode(['font'=>'注册失败','code'=>2]);exit;
                }
            }else{
                echo json_encode(['font'=>'手机号错误','code'=>2]);exit;
            }
        }else{
            echo json_encode(['font'=>'验证码错误','code'=>2]);exit;
        }
    }

    //发短信
    public function sendMobile(Request $request)
    {
        $address=$request->userMobile;
        $code=Common::createcode(4);
        $res=Common::sendSms($address,$code);
        if($res){
            cache(['code'=>$code], 30);
            session(['usertel'=>$address]);
            echo 1;
        }else{
            echo 2;
        }
    }


//    /** 阿里云发送短信 */
//    private function sendMobile($mobile){
//        $host = env("MOBILE_HOST");
//        $path = env("MOBILE_PATH");
//        $method = "POST";
//        $appcode = env("MOBILE_APPCODE");
//        $headers = array();
//        $code=Common::createcode(4);
//        session(['code'=>$code,'mobile'=>$mobile,'sendtime'=>time()]);
//        array_push($headers, "Authorization:APPCODE " . $appcode);
//        $querys = "content=【创信】你的验证码是：".$code."，3分钟内有效！&mobile=".$mobile;
//        $bodys = "";
//        $url = $host . $path . "?" . $querys;
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($curl, CURLOPT_FAILONERROR, false);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HEADER, true);
//        if (1 == strpos("$".$host, "https://"))
//        {
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        }
//        var_dump(curl_exec($curl));
//
//    }
    /*
     * @content 我的潮购
     */
    public function userpage()
    {
        $usermodel=new Users;
        $user=$usermodel->where('user_id',session('user_id'))->first();
        return view('userpage',['user'=>$user]);
    }
    /*
 * @content 设置
 */
    public function set()
    {
        return view('set');
    }

    /*
     * @content 编辑个人资料
     */
    public function edituser()
    {
        $data=Users::where('user_id',session('user_id'))->first();
        return view('edituser',['data'=>$data]);
    }
    /*
     * @content 安全设置
     */
    public function safeset()
    {
        return view('safeset');
    }

    /*
     * @content 重置密码
     */
    public function resetpassword()
    {
        return view('resetpassword');
    }
    /*
     * @content 重置密码执行
     */
    public function resetpassworddo(Request $request)
    {
        $data=Users::where('user_id',session('user_id'))->first();
        if(decrypt($data->user_pwd)==$request->pwd){
            session(['user_pwd'=>$request->newpwd]);
            echo 1;
        }else{
            echo 2;
        }
    }
    /*
 * @content 重置密码执行
 */
    public function regauthdo(Request $request)
    {

        $code=cache('code');
        if($code==$request->usercode){
            $data=Users::where('user_id',session('user_id'))->first();
            $data->user_pwd=encrypt(session('user_pwd'));
            $res=$data->save();
            if($res){
                echo json_encode(['font'=>'修改成功','code'=>1]);
            }else{
                echo json_encode(['font'=>'修改失败','code'=>2]);
            }
        }else{
            echo json_encode(['font'=>'验证码错误','code'=>2]);
        }
    }


    public function regauth()
    {
        $data=Users::where('user_id',session('user_id'))->first();
        $data->user_tel=str_replace(substr($data->user_tel,3,4),'****',$data->user_tel);
        return view('regauth',['data'=>$data]);
    }
    /*
     * @content 我的钱包
     */
    public function mywallet()
    {
        return view('mywallet');
    }

    /*
     * @content 分享
     */
    public function invite()
    {
        return view('invite');
    }

    /*
    * @content 退出
    */
    public function quit(Request $request)
    {
        $request->session()->flush();
        return redirect('login');
    }

}
