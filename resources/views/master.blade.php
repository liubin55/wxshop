<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> @yield('title')</title>
    <meta content="app-id=518966501" name="apple-itunes-app" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" name="viewport" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{url('layui/css/layui.css')}}">
    <link href="{{url('css/index.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/goods.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/member.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/cartlist.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/fsgallery.css')}}" rel="stylesheet" charset="utf-8">
    <link rel="stylesheet" href="{{url('css/swiper.min.css')}}">
    <link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
</head>
<body fnav="1" class="g-acc-bg">
@yield('content')
<!--底部-->
<div class="footer clearfix">
    <ul>
        <li class="f_home"><a href="{{url('../')}}" class="hover" id="f_home"><i></i>潮购</a></li>
        <li class="f_announced"><a href="{{url('allshops')}}" id="f_announced"><i></i>所有商品</a></li>
        <li class="f_single"><a id="share" href="{{url("share")}}" ><i></i>晒单</a></li>
        <li class="f_car"><a id="btnCart" href="{{url('shopcart')}}"><i></i>购物车</a></li>
        <li class="f_personal"><a href="{{url('userpage')}}"  id="f_personal"><i></i>我的潮购</a></li>
    </ul>
</div>
</body>
</html>


<script src="{{url('js/jquery-1.8.3.min.js')}}"></script>
<script src="http://cdn.bootcss.com/flexslider/2.6.2/jquery.flexslider.min.js"></script>
<script src="{{url('layui/layui.js')}}"></script>
<script src="{{url('js/all.js')}}"></script>
<script src="{{url('js/index.js')}}"></script>
<script src="{{url('js/lazyload.min.js')}}"></script>
@yield('my-js')