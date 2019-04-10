@extends('master')
@section('title')
    订单详情
@endsection
    <link href="{{url('css/buyrecord.css')}}" rel="stylesheet" type="text/css">
 @section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header" style="display: block;">
    <strong id="m-title">订单详情</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
<div class="status">
    <img src="images/daifahuo.png" alt="">
</div>
<div class="userinfo">
    <div class="express-status">
        <i></i><span>待发货</span>
    </div>
    <div class="express-bottom">
        <ul class="clearfix">
            <li class="position"><s></s></li>
            <li class="info">
                <div class="clearfix">
                    <span class="user fl">收货人：{{$address->address_name}}</span>
                    <span class="tel fr">{{$address->address_tel}}</span>
                </div>
                <p>{{$address->address_desc}}</p>
            </li>
        </ul>
    </div>
</div>
<div class="getshop">
    @foreach($detial as $k=>$v)
    <div class="shopsimg fl">
        <img src="{{url('images/goodsLogo/'.$v->goods_img)}}" alt="">
    </div>
    <div class="shopsinfo">
        <h3>{{$v->goods_name}}</h3>
        <p class="price">价值：￥<i>{{$v->self_price}}</i></p>
        <p>订单号：{{$order_no}}</p>
    </div>
    @endforeach
    <div class="hot-line">
        <i></i><span>客服热线：400-666-2110</span>
    </div>
</div>
@endsection
@section('my-js')
    <script>
        $(".footer").attr('style','display:none');
    </script>
    @endsection