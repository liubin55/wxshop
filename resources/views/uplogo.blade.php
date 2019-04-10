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
旧密码：<input type="text" id="user_pwd"><br>
密码：<input type="password" id="new_pwd"><br>
<input type="button" value="修改" id="btn">
<input type="hidden" id="_token" value="{{csrf_token()}}">
<a href="{{url('hlogo')}}">去登录</a>
</body>
</html>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    $(function () {
        $("#btn").click(function(){
            $.ajax({
                type:'post',
                data:{new_pwd:$("#new_pwd").val(),user_pwd:$("#user_pwd").val(),_token:$("#_token").val()},
                url:"{{url('uplogo')}}",
            }).done(function (res) {
                alert(res);
            })
        })
    })
</script>