<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Document</title>
</head>
<body>
用户名：<input type="text" id="user_name"><br>
密码：<input type="password" id="user_pwd"><br>
<input type="button" value="登录" id="btn">
<input type="hidden" id="_token" value="{{csrf_token()}}">
<a href="{{url('uplogo')}}">去修改密码</a>
</body>
</html>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    $(function () {
        $("#btn").click(function(){
            $.ajax({
                type:'post',
                data:{user_name:$("#user_name").val(),user_pwd:$("#user_pwd").val(),_token:$("#_token").val()},
                url:"{{url('hlogo')}}",
            }).done(function (res) {
                alert(res);
            })
        })
    })
</script>