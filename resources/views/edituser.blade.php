@extends('master')
@section('title')
    编辑个人资料
@endsection
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">编辑个人资料</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="javascript:;" class="m-index-icon">保存</a>
</div>

    <div class="wallet-con">
        <div class="w-item">
            <ul class="w-content clearfix">
                <li class="headimg">
                    <a href="">头像</a>
                    <s class="fr"></s>
                    <span class="img fr"></span>
                </li>
                <li>
                    <a href="">昵称</a>
                    <s class="fr"></s>
                    <span class="fr">{{$data->user_name}}</span>
                </li>
                <li>
                    <a href="">我的主页</a>
                    <s class="fr"></s>
                </li>
                <li>
                    <a href="">手机号码</a>
                    <span class="fr">{{$data->user_tel}}</span>
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
        layui.use('layer',function () {
            var layer=layui.layer;
        })
    </script>
@endsection
