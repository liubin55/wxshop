@extends('master')
@section('title')
   安全设置
@endsection
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/safeset.css')}}" rel="stylesheet" type="text/css" />
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">安全设置</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li>
                    <em class="login"></em>
                    <a href="">登录密码</a>
                    <s class="fr"></s>
                    <span class="fr"><a href="{{url('resetpassword')}}">修改</a></span>
                </li>
                <li>
                    <em class="pay"></em>
                    <a href="">支付密码</a>
                    <s class="fr"></s>
                    <span class="fr">已开启</span>
                </li>
                <li>
                    <em class="card"></em>
                    <a href="">银行卡</a>
                    <s class="fr"></s>
                </li>           
            </ul>     
        </div>
    </div>
@endsection
@section('my-js')
    <script>
        $(".footer").attr('style','display:none');
    </script>
@endsection