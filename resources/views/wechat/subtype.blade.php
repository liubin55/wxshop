@extends('adminmaster')
@section('title')
    微信首次关注回复类型设置
@endsection
<!-- 条目中可以是任意内容，如：<img src=""> -->
@section('content')
    <form class="layui-form" action="">
        选择回复类型：<br>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="radio" class="ratype" name="types" value="text">文本<br>
        <input type="radio" class="ratype"  name="types" value="image">图片<br>
        <input type="radio" class="ratype"  name="types" value="news">图文<br>
        <input type="radio" class="ratype"  name="types" value="voice">语音<br>
        <input type="radio" class="ratype"  name="types" value="voice">音乐<br>
        <input type="radio" class="ratype"  name="types" value="video">视频
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
@endsection
@section('my-js')
    <script>
        $(function () {
            $(".ratype").each(function () {
                var val=$(this).val();
                var type="{{$type}}";
                if(type==val){
                    $(this).attr('checked',true);
                }
            })
        })
        //Demo
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(formDemo)', function(data){
                $.ajax({
                    type:'post',
                    data:data.field,
                    url:"{{url('wechat/subtypeDo')}}"
                    }).done(function (res) {
                        layer.msg(res);
                })
                return false;
            });
        });
    </script>
@endsection


