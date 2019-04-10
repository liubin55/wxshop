<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Document</title>
    <style>
        li {
            float: left;list-style: none;margin-left: 5%;
        }
    </style>
</head>
<body>
<div id="box">
<input type="text" id="search" value="{{$search}}"><input type="button" value="搜索" id="btn"><br>
    @foreach($goods as $v)
        {{$v->goods_name}},<br>
    @endforeach
    {{$goods->appends(['search' => $search])->links()}}
    <input type="hidden" id="_token" value="{{csrf_token()}}">
</div>
</body>
</html>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    $(function () {
        $("#btn").click(function(){
            $.ajax({
                type:'post',
                data:{search:$("#search").val(),_token:$("#_token").val()},
                url:"{{url('search')}}",
            }).done(function (res) {
                $("#box").html(res);
            })
        })
    })
</script>