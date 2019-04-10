<?php

namespace App\Http\Controllers\Share;

use App\Models\OrderDetial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\OrderAddress;
use App\Models\Order;

class ShareController extends Controller
{
    //晒单列表
    public function share()
    {
        return view('share');
    }
    //晒单添加
    public function willshare()
    {
        return view('willshare');
    }
    //晒单详情
    public function sharedetail()
    {
        return view('sharedetail');
    }
    //购买记录
    public function buyrecord()
    {
        //购买记录
        $detialmodel=new OrderDetial;
        $detialinfo=$detialmodel->join('goods','order_detial.goods_id','=','goods.goods_id')
            ->where('user_id',session('user_id'))
            ->get();
        //人气推荐商品列表
        $goodsinfo=Goods::where(['is_new'=>1])->orderBy('goods_score','desc')->get();
        return view('buyrecord',['goodsinfo'=>$goodsinfo],['detialinfo'=>$detialinfo]);

    }
    //订单详情
    public function orderwillsend()
    {
        //订单详情
        $detial=OrderDetial::where(['order_id'=>session('order_id'),'user_id'=>session('user_id')])->get();
        //订单地址
        $address=OrderAddress::where(['order_id'=>session('order_id'),'user_id'=>session('user_id')])->first();
        //订单号
        $order_no=Order::where('order_id',session('order_id'))->value('order_no');
        return view('order-willsend',['detial'=>$detial],['address'=>$address])
            ->with('order_no',$order_no);
    }
    //订单列表
    public function recorddetail()
    {
        return view('recorddetail');
    }
}
