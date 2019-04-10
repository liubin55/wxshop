<?php

namespace App\Http\Controllers\Cart;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Address;
use App\Models\OrderDetial;
use App\Models\OrderAddress;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /*
     * @content 加入购物车
     */
    public function cartadd(Request $request)
    {
        if(empty(session('user_id'))){
            echo json_encode(['font'=>"请登录后操作",'code'=>3]);exit;
        }
        $cartmode=new Cart;
        $goodsmodel=new Goods;
        $goodsInfo=$goodsmodel->where('goods_id',$request->goods_id)->first();
        $arr=$cartmode->where(['goods_id'=>$request->goods_id,'user_id'=>session('user_id'),'cart_status'=>1])->first();
        if(empty($arr)){
            if($goodsInfo->goods_num>=1){
                $data['goods_id']=$request->goods_id;
                $data['user_id']=session('user_id');
                $data['buy_number']=1;
                $data['create_time']=time();
                $res=$cartmode->insert($data);
                if($res){
                    echo json_encode(['font'=>"添加成功",'code'=>1]);exit;
                }else{
                    echo json_encode(['font'=>"添加失败",'code'=>2]);exit;
                }
            }else{
                echo json_encode(['font'=>"库存不足,加入失败",'code'=>2]);exit;
            }
        }else{
            if($goodsInfo->goods_num>$arr->buy_number){
                $arr->buy_number=$arr->buy_number+1;
                $res=$arr->save();
                if($res){
                    echo json_encode(['font'=>"添加成功",'code'=>1]);exit;
                }else{
                    echo json_encode(['font'=>"添加失败",'code'=>2]);exit;
                }
            }else{
                echo json_encode(['font'=>"库存不足,加入失败",'code'=>2]);exit;
            }

        }
    }

    /*
 * @contetn 购物车列表
 */
    public function shopcart()
    {
        $cartmodel=new Cart;
        $goodsmodel=new Goods;
        $goodsinfo=$goodsmodel->where('is_hot',1)->get();
        $data=$cartmodel
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->where(['user_id'=>session('user_id'),'cart_status'=>1])
            ->orderBy('cart.create_time','desc')
            ->get();
        return view('shopcart',['data'=>$data],['goodsinfo'=>$goodsinfo]);
    }

    /*
     * @content 修改库存
     */
    public function priceadd(Request $request)
    {
        $cartmodel=new Cart;
        $where=[
            'user_id'=>session('user_id'),
            'goods_id'=>$request->goods_id,
            'cart_status'=>1
        ];
        $arr=$cartmodel->where($where)->first();
        $goodsmodel=new Goods;
        $goodsInfo=$goodsmodel->where('goods_id',$request->goods_id)->first();
        if($request->type==1){
            $arr->buy_number=$arr->buy_number+1;
        }else{
            $arr->buy_number=$arr->buy_number-1;
        }
        if($goodsInfo->goods_num>$request->buy_number&&$request->type==1){
            $res=$arr->save();
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else if($request->type==2&&$request->buy_number>1){
            $res=$arr->save();
            if($res){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }

    }

    //单删
    public function cartdel(Request $request)
    {
        $cartmodel=new Cart;
        $where=[
            'goods_id'=>$request->goods_id,
            'user_id'=>session('user_id')
        ];
        $res=Cart::where($where)->update(['cart_status' => 2]);
        if($res){
            echo json_encode(['font'=>"删除成功",'code'=>1]);exit;
        }else{
            echo json_encode(['font'=>"删除失败",'code'=>2]);exit;
        }
    }
    //批删
    public function cartdels(Request $request)
    {
        $goods_id=explode(',',rtrim($request->goods_id,','));
        $cartmodel=new Cart;
        $res=Cart::where('user_id',session('user_id'))
            ->whereIn('goods_id',$goods_id)
            ->update(['cart_status' => 2]);
        if($res){
            echo json_encode(['font'=>"删除成功",'code'=>1]);exit;
        }else{
            echo json_encode(['font'=>"删除失败",'code'=>2]);exit;
        }
    }
    //结算页面
    public function payment(Request $request)
    {
        $id = $request->only('goods_id');
        $goods_id=explode(',',$id['goods_id']);
        $cartmodel=new Cart;
        $data=$cartmodel
            ->join('goods','cart.goods_id','=','goods.goods_id')
            ->whereIn('cart.goods_id',$goods_id)
            ->where(['user_id'=>session('user_id'),'cart_status'=>1])
            ->get();
        $priceNum=0;
        foreach ($data as $k=>$v){
            $priceNum+=$v['self_price']*$v['buy_number'];
        }
        return view('payment',['data'=>$data],['priceNum'=>$priceNum]);
    }
    //生成订单
    public function orderform(Request $request)
    {
        $goods_id=explode(',',$request->goods_id);
        if(empty($goods_id)){
            echo json_encode(['font'=>'请选择结算的商品','code'=>1]);exit;
        }
        //获取默认的地址
        $address_id=Address::where(['user_id'=>session('user_id'),'address_default'=>1])->value('address_id');
        //开启事务
        DB::beginTransaction();
        try{
        //中间逻辑代码 DB::commit();
            //把订单信息存入订单表
            //生成订单号
            $cartmodel=new Cart;
            $cartInfo=$cartmodel
                ->join('goods','cart.goods_id','=','goods.goods_id')
                ->whereIn('cart.goods_id',$goods_id)
                ->where(['user_id'=>session('user_id'),'cart_status'=>1])
                ->get()

                ->toArray();
            $order_amount=0;
            foreach ($cartInfo as $k=>$v) {
                $order_amount+= $v['self_price'] * $v['buy_number'];
            }
            $ordermodel=new Order;
            $ordermodel->order_no=$this->OrderNo();
            $ordermodel->user_id=session('user_id');
            $ordermodel->pay_type=3;
            $ordermodel->order_amount=$order_amount;
            $res=$ordermodel->save();
            //获取订单id
            $order_id=$ordermodel->order_id;
            if(!$res){
                throw new \Exception("订单信息添加失败");
            }
            //把订单商品信息存入订单详情表
            $cart=[];
            foreach ($cartInfo as $k=>$v) {
                session(['goods_name'=>$v['goods_name'],'goods_desc'=>$v['goods_desc']]);
                $cart[]=array_only($v,['goods_id','buy_number','self_price','goods_name','goods_img']);
            }
            foreach ($cart as $k=>$v){
                $cart[$k]['user_id'] = session('user_id');
                $cart[$k]['order_id'] = $order_id;
                $cart[$k]['create_time']=time();
                $cart[$k]['update_time']=time();
            }
            $orderDetial_model = new OrderDetial;
            $res2 = $orderDetial_model->insert($cart);
            if (!$res2) {
                throw new \Exception("订单详细信息添加失败");
            }
            //订单收获地址 存入订单收货地址
            $addressInfo=Address::where(['user_id'=>session('user_id'),'address_default'=>1])->first();
            if(empty($addressInfo)){
                throw new  \Exception("收货地址不存在");
            }
            $addressInfo=$addressInfo->toArray();
            $addressInfo=array_only($addressInfo,['address_name','address_tel','address_desc','address_mail']);
            $addressInfo['user_id']=session('user_id');
            $addressInfo['order_id']=$order_id;
            $addressInfo['create_time']=time();
            $addressInfo['update_time']=time();
            $orderAddress_model=new OrderAddress;
            $res3=$orderAddress_model->insert($addressInfo);
            if (!$res3) {
                throw new \Exception("收货地址添加失败");
            }
            //清空当前用户的购物车数据（购物车表）
            $res4=Cart::where('user_id',session('user_id'))
                ->whereIn('goods_id',$goods_id)
                ->update(['cart_status' => 2]);
            if (!$res4) {
                throw new \Exception("购物车清空失败");
            }
            //减少商品库存（商品表)
            $goods_model=new Goods;
            foreach ($cartInfo as $k=>$v){
                $goodsWhere=[
                    'goods_id'=>$v['goods_id']
                ];
                $info=[
                    'goods_num'=>$v['goods_num']-$v['buy_number']
                ];
                $res5=$goods_model->where($goodsWhere)->update($info);
                if(!$res5){
                    throw new \Exception("库存结算失败");
                }
            }
            DB::commit();
            session([
                'order_id'=>$order_id,
                'order_amount'=>$order_amount,
                'order_no'=>$this->OrderNo(),
            ]);
            echo json_encode(['font'=>'提交成功','code'=>1]);
        }catch (\Exception $e) {
        //接收异常处理并回滚 DB::rollBack();
            DB::rollBack();
            echo json_encode(['font'=>$e->getMessage(),'code'=>2]);
        }
    }
    //订单号
    public function OrderNo()
    {
        return time().rand(1111,9999);
    }

}
