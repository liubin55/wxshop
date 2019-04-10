<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tools\alipay\wappay\service\AlipayTradeService;
use App\Tools\alipay\wappay\buildermodel\AlipayTradeWapPayContentBuilder;
use App\Models\Goods;
class AlipayController extends Controller
{
    //支付宝付款
    public function alipay()
    {
        header("Content-type: text/html; charset=utf-8");
        $config=config('alipay');
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = session('order_no');

            //订单名称，必填
            $subject = session('goods_name');

            //付款金额，必填
            $total_amount = session('order_amount');

            //商品描述，可空
            $body =session('goods_desc');

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;
        }
    //同步回调
    public function re()
    {
        //人气推荐商品列表
        $goodsinfo=Goods::where(['is_new'=>1])->orderBy('goods_score','desc')->get();
        return view('paysuccess',['goodsinfo'=>$goodsinfo]);
    }
    //异步回调
    public function ontify()
    {
        echo 2;
    }

}
