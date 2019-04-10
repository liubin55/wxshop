@extends('master')
@section('title')
    设置
@endsection
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    @section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">设置</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li>
                    <a href="{{url('edituser')}}">编辑个人资料</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="{{url('invite')}}">邀请有奖</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="{{url('safeset')}}">安全设置</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">客服热线（9:00-17:00）</a>
                    <s class="fr"></s>
                    <span class="fr">400-666-2110</span>
                </li>           
            </ul>     
        </div>
        <div class="quit">
            <a href="{{url('quit')}}">退出登录</a>
        </div>
    </div>
@endsection
@section('my-js')
<script>
    $(".footer").attr('style','display:none');
</script>
@endsection