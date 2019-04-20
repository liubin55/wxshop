@extends('master')
@section("title")
    用户绑定
@endsection
<link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
@section('content')
    <!--触屏版内页头部-->
    <div class="m-block-header" id="div-header">
        <strong id="m-title">绑定</strong>
    </div>
    <div class="wrapper">
        <input name="hidForward" type="hidden" id="hidForward" />
        <div class="registerCon">
            <ul>
                <li class="accAndPwd">
                    <dl>
                        <s class="phone"></s>
                        <input id="userMobile" maxlength="11" type="number" placeholder="请输入绑定的手机号码" value="" />
                        <span class="clear">x</span>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input id="usercode" maxlength="11" type="number" placeholder="请输入您的验证码" value="" />
                        <s class="phone" style="margin-left: 300px" id="senMobile"></s>
                        <span>获取验证码</span>
                    </dl>
                    <dl class="a-set">
                        <i class="gou"></i><p>我已阅读并同意《666潮人购购物协议》</p>
                    </dl>

                </li>
                <li><a id="btnNext" href="javascript:;" class="orangeBtn loginBtn">绑定</a></li>
            </ul>
            <a  href="{{url('register')}}">去注册</a>
        </div>
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        <input type="hidden" id="openid" value="{{$userinfo['openid']}}">
        <div class="layui-layer-move"></div>
        @endsection
        <script src="{{url('js/all.js')}}"></script>
        @section('my-js')
            <script>
                var falg=false;
                //隐藏下面导航
                $(".footer").attr('style','display:none');
                //发送验证码
                $("#senMobile").click(function(){
                    var userMobile=$("#userMobile").val();
                    //console.log(userMobile);
                    if(userMobile==''){
                        layer.msg('手机号不能为空');
                        return false;
                    }else{
                        $.ajax({
                            type:"post",
                            data:{user_name:userMobile,_token:$("#_token").val()},
                            url:"{{url('registerajax')}}",
                        }).done(function (res) {
                            if(res==2){
                                layer.msg("手机号未注册，请注册后绑定");
                                falg=false;
                            }else{
                                falg=true;
                            }
                        })
                        if(!falg==true){
                            return falg;
                        }
                    }

                    var _token=$("#_token").val();
                    $.ajax({
                        type:'post',
                        url:"{{url('sendMobile')}}",
                        data:{userMobile:userMobile,_token:_token},
                    }).done(function (res) {
                        if(res=1){
                            layer.msg('发送成功');
                        }else{
                            layer.msg('发送失败');
                        };
                    })
                })
                $('.registerCon input').bind('keydown',function(){
                    var that = $(this);
                    if(that.val().trim()!=""){

                        that.siblings('span.clear').show();
                        that.siblings('span.clear').click(function(){
                            console.log($(this));

                            that.parents('dl').find('input:visible').val("");
                            $(this).hide();
                        })

                    }else{
                        that.siblings('span.clear').hide();
                    }

                })
                function show(){
                    if($('.registerCon input').attr('type')=='password'){
                        $(this).prev().prev().val($("#passwd").val());
                    }
                }
                function hide(){
                    if($('.registerCon input').attr('type')=='text'){
                        $(this).prev().prev().val($("#passwd").val());
                    }
                }
                $('.registerCon s').bind({click:function(){
                        if($(this).hasClass('eye')){
                            $(this).removeClass('eye').addClass('eyeclose');
                            $(this).prev().prev().prev().val($(this).prev().prev().val());
                            $(this).prev().prev().prev().show();
                            $(this).prev().prev().hide();
                        }else{
                            $(this).removeClass('eyeclose').addClass('eye');
                            $(this).prev().prev().val($(this).prev().prev().prev().val());
                            $(this).prev().prev().show();
                            $(this).prev().prev().prev().hide();
                        }
                    }
                })

                function registertel(){
                    // 手机号失去焦点
                    $('#userMobile').blur(function(){
                        reg=/^1(3[0-9]|4[57]|5[0-35-9]|8[0-9]|7[06-8])\d{8}$/;//验证手机正则(输入前7位至11位)
                        var that = $(this);
                        if( that.val()==""|| that.val()=="请输入您的手机号")
                        {
                            layer.msg('请输入您的手机号！');
                        }
                        else if(that.val().length<11)
                        {
                            layer.msg('您输入的手机号长度有误！');
                        }
                        else if(!reg.test($("#userMobile").val()))
                        {
                            layer.msg('您输入的手机号不存在!');
                        }
                    })


                }
                registertel();
                // 购物协议
                $('dl.a-set i').click(function(){
                    var that= $(this);
                    if(that.hasClass('gou')){
                        that.removeClass('gou').addClass('none');
                        $('#btnNext').css('background','#ddd');

                    }else{
                        that.removeClass('none').addClass('gou');
                        $('#btnNext').css('background','#f22f2f');
                    }

                })
                // 注册提交
                $('#btnNext').click(function(){
                    var userMobile=$('#userMobile').val();
                    var openid=$("#openid").val();
                    var usercode=$('#usercode').val();
                    if($('#userMobile').val()==''){
                        layer.msg('请输入您的手机号！');
                        return false;
                    }else if(usercode==''){
                        layer.msg('请输入您的验证码！');
                        return false;
                    }
                    var _token=$("#_token").val();
                    $.ajax({
                        type:'post',
                        url:"{{url('send/wxloginDo')}}",
                        data:{userMobile:userMobile,openid:openid,usercode:usercode,_token:_token},
                        dataType:'json'
                    }).done(function (res) {
                        if(res.code==1){
                            layer.msg(res.font,{icon:res.code,time:2000},function () {
                                location.href="{{url('../')}}"
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code});
                        };
                    })

                })


            </script>
        @endsection
        <script src="{{url('js/all.js')}}"></script>
