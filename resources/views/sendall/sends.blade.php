@extends('adminmaster')
@section('title')
    发布群消息
@endsection
@section('content')
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">发布群消息</h2>
        <label class="layui-form-label">选择发送类型：</label>

        <form action="{{url('send/sendall')}}" method="post" enctype="multipart/form-data">
            <select  name="type" id="type_">
                <option value="">请选择</option>
                <option value="标签">标签</option>
                <option value="openid">openid</option>
            </select>


            @csrf
            <div class="layui-form-item">
                <div id="box">



                </div>
            </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">发布内容：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="contents" required lay-verify="required" placeholder="请输入内容" autocomplete="off" class="layui-input">
                    </div>
                </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input  class="layui-btn" type="submit" value="发布">
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('my-js')
    <script>
        $(function(){
            $("#type_").change(function () {
                var _type = $(this).val();
                if (_type == '标签') {
                    $("#box").html(" <label class=\"layui-form-label\">选择标签：</label>\n" +
                        "                    <div class=\"layui-input-inline\"><select  name=\"tags\">\n" +
                        "                @foreach($data as $v)\n" +
                        "                <option value=\"{{$v['id']}}\">{{$v['name']}}</option>\n" +
                        "                @endforeach\n" +
                        "            </select>  </div>")
                }else{
                    $("#box").empty();
                }
            })
        })
    </script>
@endsection