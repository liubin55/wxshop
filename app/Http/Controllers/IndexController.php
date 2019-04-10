<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Cate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    /*
     * @content 主页
     */
    public function index()
    {
        //轮播图
        $goodsmodel=new Goods;
        $data=$goodsmodel->orderBy('create_time','desc')->select('goods_img')->paginate(5);
        //最热商品
        $goodshost=$goodsmodel->where(['is_hot'=>1])->orderBy('create_time','desc')->paginate(2);
        //猜你喜欢商品列表
        $goodsinfo=$goodsmodel->where(['is_new'=>1])->orderBy('update_time','desc')->get();
        //分类
        $catemodel=new Cate;
        $cate=$catemodel->where('pid','=',0)->get();
        return view('index',['data'=>$data],['goodshost'=>$goodshost])
            ->with('goodsinfo',$goodsinfo)
            ->with('cate',$cate);
    }
    //上个月A卷搜索分页缓存做
    public function search(Request $request)
    {
        $search=$request->search;
        $page=$request->input('page',1);
        $key=$search.$page;
        //redis存储缓存
        if(Redis::exists($key)){
           $goods=Redis::get($key);
        }else{
           $goods=Goods::where('goods_name','like',"%$search%")->paginate(10);
           $goods=encrypt($goods);
           Redis::set($key,$goods);
           Redis::expire($key,100);

         }
        $goods=decrypt($goods);
        //memcache存储缓存
//        if(Cache::has($search.$page)){
//            $goods=Cache::get($search.$page);
//        }else{
//            $goods=Goods::where('goods_name','like',"%$search%")->paginate(10);
//            Cache::put($search.$page,$goods,1000);
//        }
       return view('search',['goods'=>$goods],['search'=>$search]);
    }


    //上个月B卷登录缓存做
    public function hlogo(Request $request)
    {
        if($request->post()&&$request->ajax()){
            $user_pwd=$request->user_pwd;
            $user_name=$request->user_name;
            //memcache查询缓存
//            if(Cache::has($user_name)){
//                $res=Cache::get($user_name);
//            }else{
//                $res=Users::where(['user_name'=>$user_name])->first();
//                Cache::put($user_name,$res,100);
//            }
            //redis查询缓存
            if(Redis::exists($user_name)){
                $res=Redis::get($user_name);
            }else{
                $res=Users::where(['user_name'=>$user_name])->first();
                $res=encrypt($res);
                Redis::set($user_name,$res);
                Redis::expire($user_name,100);
            }
            $res=decrypt($res);
            if(empty($res)){
                echo "账户错误";
            }else{
                if(decrypt($res->user_pwd)==$user_pwd){
                    session(['users_id'=>$res->user_id,'user_name'=>$user_name]);
                    echo "登录成功";
                }else{
                    echo "登录失败";
                }
            }

        }else{
            return view('hlogo');
        }
    }
    //上个月B卷登录缓存做修改密码
    public function uplogo(Request $request){
        if($request->post()&&$request->ajax()){
            $user=Users::find(session('users_id'));
            if(decrypt($user->user_pwd)==$request->user_pwd){
                $user->user_pwd=encrypt($request->new_pwd);
                $res=$user->save();
                if($res){
                    //redis清空键值
                    Redis::del(session('user_name'));
                    //memcache清空
                    //Cache::flush();
                    echo "修改成功";
                }else{
                    echo "修改失败";
                }
            }else{
                echo "原密码错误";
            }

        }else{
            return view('uplogo');
        }
    }
}
