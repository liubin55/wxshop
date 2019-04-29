<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>确认登录</title>
</head>
<body>
    <center>
        <img src="{{url($userinfo['headimgurl'])}}" style="width: 500px;height: 500px" alt=""><br>
        <button id="btn" style="font-size: 100px">确认登录</button>
    </center>
</body>
</html>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    $("#btn").click(function () {
        $.ajax({
            url:"{{url('qrcode/login')}}",
            data:{openid:'{{$userinfo['openid']}}',username:'{{$userinfo['nickname']}}',_token:'{{csrf_token()}}'},
            type:'post'
        }).done(function (res) {
           alert(res);
            WeixinJSBridge.call('closeWindow');
        });
    })
</script>