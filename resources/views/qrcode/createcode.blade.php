<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>二维码</title>
</head>
<body>
<center>
    <img src="{{url('qrcode.png')}}" alt=""><br>
    <span id="news"></span><br>
    <span id="notice"></span>
</center>
</body>
</html>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    $(document).ready(function () {
        times=setInterval(getstatus,3000);
        num=60;
        setInterval(getnews,1000);
    })
    //提示消息
    function getnews() {
        if(num>0){
            str="请扫码，二维码"+num+"s后失效";
            num--;
        }else{
            str="二维码已失效";
            clearInterval(times);
            $("#notice").text('');
            $("img").attr('src','{{url('losecode.png')}}')
        }
        $("#news").text(str);
    }
    //获取状态
    function getstatus() {
        $.ajax({
            url:"{{url('qrcode/status')}}"+"/"+Math.random(),
            data:{userid:'{{$userid}}',_token:'{{csrf_token()}}'},
            type:'post'
        }).done(function (res) {
            if(res==1){
                $("#notice").text('等待扫码');
            }else if(res==2){
                $("#notice").text("扫码成功，请确认登录");
            }else if(res==3){
                location.href="{{url('/')}}";
            }
        })
    }
</script>