@extends('master')
@section("title")
    注册
@endsection
<link href="{{url('css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('css/vccode.css')}}" rel="stylesheet" type="text/css" />
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">注册</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
    <div class="wrapper">
        <input name="hidForward" type="hidden" id="hidForward" />
        <div class="registerCon">
            <ul>
                <li class="accAndPwd">
                    <dl>
                        <s class="phone"></s>
                        <input id="userMobile" maxlength="11" type="number" placeholder="请输入您的手机号码" value="" />
                        <span class="clear">x</span>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input class="pwd" maxlength="11" type="password" placeholder="6-16位数字、字母组成" value="" />
                        <input class="pwd" maxlength="11" type="text" placeholder="6-16位数字、字母组成" value="" style="display: none" />
                        <span class="mr clear">x</span>
                        <s class="eyeclose"></s>
                    </dl>
                    <dl>
                        <s class="password"></s>
                        <input class="conpwd" maxlength="11" type="password" placeholder="请确认密码" value="" />
                        <input class="conpwd" maxlength="11" type="text" placeholder="请确认密码" value="" style="display: none" />
                        <span class="mr clear">x</span>
                        <s class="eyeclose"></s>
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
                <li><a id="btnNext" href="javascript:;" class="orangeBtn loginBtn">注册</a></li>
            </ul>
        </div>
        <input type="hidden" id="_token" value="{{csrf_token()}}">

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
                if(res==1){
                    layer.msg("手机号已存在");
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
        // 密码失去焦点
        $('.pwd').blur(function(){
            reg=/^[0-9a-zA-Z]{6,16}$/;
            var that = $(this);
            if( that.val()==""|| that.val()=="6-16位数字或字母组成")  
            {   
                layer.msg('请设置您的密码！');
            }else if(!reg.test($(".pwd").val())){   
                layer.msg('请输入6-16位数字或字母组成的密码!');
            }
        })

        // 重复输入密码失去焦点时
        $('.conpwd').blur(function(){
            var that = $(this);
            var pwd1 = $('.pwd').val();
            var pwd2 = that.val();
            if(pwd1!=pwd2){
                layer.msg('您俩次输入的密码不一致哦！');
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
        var pwd=$('.pwd').val();
        var conpwd=$('.conpwd').val();
        var usercode=$('#usercode').val();
    	if($('#userMobile').val()==''){
    		layer.msg('请输入您的手机号！');
    		return false;
    	}else if($('.pwd').val()==''){
    		layer.msg('请输入您的密码!');
            return false;
    	}else if($('.conpwd').val()==''){
    		layer.msg('请您再次输入密码！');
            return false;
        }else if(pwd!=conpwd){
            layer.msg('确认密码与密码不一致！');
            return false;
        }else if(usercode==''){
            layer.msg('请输入您的验证码！');
            return false;
        }
        var _token=$("#_token").val();
        $.ajax({
            type:'post',
            url:"{{url('registerDo')}}",
            data:{userMobile:userMobile,pwd:pwd,conpwd:conpwd,usercode:usercode,_token:_token},
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
