@extends('master')
@section("title")
    修改密码
@endsection
<link href="css/login.css" rel="stylesheet" type="text/css" />
<link href="css/findpwd.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="layui/css/layui.css">
<link rel="stylesheet" href="css/modipwd.css">
<script src="js/jquery-1.11.2.min.js"></script>
@section('content')
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title"></strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>



    <div class="wrapper">
        <form class="layui-form" action="">
            <div class="registerCon">
                <ul>
                    <li class="auth"><em>请输入验证码</em></li>
                    <li id="get" style="display: none"><p>我们已向<em class="red">{{$data->user_tel}}</em>发送验证码短信，请查看短信并输入验证码。</p></li>
                    <li>

                        <input type="text" id="usercode" placeholder="请输入验证码" value=""/>

                        <a href="javascript:void(0);" style="margin-left: 220px;" class="sendcode" id="btn">获取验证码</a>
                    </li>
                    <li><a id="findPasswordNextBtn" href="javascript:void(0);" class="orangeBtn">确认</a></li>
                    <li>换了手机号码或遗失？请致电客服解除绑定400-666-2110</li>
                </ul>
            </div>
        </form>
    </div>
<input type="hidden" id="userMobile">
<input type="hidden" id="_token" value="{{csrf_token()}}">
@endsection

@section('my-js')
<script>
    //隐藏下面导航
    $(".footer").attr('style','display:none');
layui.use('layer', function(){
  var layer = layui.layer;
    $("#btn").click(function () {
        $("#get").show();
        var _token=$("#_token").val();
        var userMobile=$("#userMobile").val();
        $.ajax({
            type:'post',
            url:"{{url('sendMobile')}}",
            data:{userMobile:userMobile,_token:_token},
        }).done(function (res) {
            if(res==1){
                layer.msg('发送成功');
            }else{
                layer.msg('发送失败');
            };
        })
    })
    $("#findPasswordNextBtn").click(function () {
        var usercode=$("#usercode").val();
        var _token=$("#_token").val();
        $.ajax({
            type:'post',
            url:"{{url('regauthdo')}}",
            data:{usercode:usercode,_token:_token},
            dataType:'json'
        }).done(function (res) {
            if(res.code==1){
                layer.msg(res.font,{icon:res.code,time:2000},function () {
                    location.href="{{url('userpage')}}"
                });
            }else{
                layer.msg(res.font,{icon:res.code})
            };
        })
    })
});

</script>    
@endsection
    