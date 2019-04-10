@extends('adminmaster')
@section('title')
    微信添加永久素材
@endsection
@section('content')
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">添加永久关注回复素材</h2>
        <label class="layui-form-label">选择添加类型：</label>

        <form action="{{url('wechat/subuploadsDo')}}" method="post" enctype="multipart/form-data">
            <select  name="type" id="type_">
                <option value="news">图文</option>
                <option value="image">图片</option>
                <option value="voice">语音</option>
                <option value="video">视频</option>
                <option value="text">文本</option>
                <option value="music">音乐</option>
            </select>
            @csrf
            <div id="box" style="margin: 20px">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题：</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">内容：</label>
                    <div class="layui-input-inline">
                        <input type="text" name="contents" required lay-verify="required" placeholder="请输入内容" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">上传文件</label>
                    <div class="layui-input-block">
                        <input type="file" name="file">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">链接url：</label>
                    <div class="layui-input-inline">
                        <input type="url" name="purl"  required lay-verify="required" placeholder="请输入链接地址" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input  class="layui-btn" type="submit" value="提交">
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
                var _type=$(this).val();
                if(_type=='image'||_type=='voice'||_type=='music'){
                    $("#box").empty();
                    $("#box").html("   <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">上传文件</label>\n" +
                        "                    <div class=\"layui-input-block\">\n" +
                        "                        <input type=\"file\" name=\"file\">\n" +
                        "                    </div>\n" +
                        "                </div>");
                }else if(_type=="text"){
                    $("#box").empty();
                    $("#box").html("<div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">内容：</label>\n" +
                        "                    <div class=\"layui-input-inline\">\n" +
                        "                        <input type=\"text\" name=\"contents\" required lay-verify=\"required\" placeholder=\"请输入内容\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>");
                }else if(_type=="news"){
                    $("#box").empty();
                    $("#box").html("<div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">标题：</label>\n" +
                        "                    <div class=\"layui-input-block\">\n" +
                        "                        <input type=\"text\" name=\"title\" required  lay-verify=\"required\" placeholder=\"请输入标题\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "                <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">内容：</label>\n" +
                        "                    <div class=\"layui-input-inline\">\n" +
                        "                        <input type=\"text\" name=\"contents\" required lay-verify=\"required\" placeholder=\"请输入内容\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "                <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">上传文件</label>\n" +
                        "                    <div class=\"layui-input-block\">\n" +
                        "                        <input type=\"file\" name=\"file\">\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "                <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">链接url：</label>\n" +
                        "                    <div class=\"layui-input-inline\">\n" +
                        "                        <input type=\"url\" name=\"purl\"  required lay-verify=\"required\" placeholder=\"请输入链接地址\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>");
                }else if(_type=='video'){
                    $("#box").empty();
                    $("#box").html("<div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">标题：</label>\n" +
                        "                    <div class=\"layui-input-block\">\n" +
                        "                        <input type=\"text\" name=\"title\" required  lay-verify=\"required\" placeholder=\"请输入标题\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "                <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">内容：</label>\n" +
                        "                    <div class=\"layui-input-inline\">\n" +
                        "                        <input type=\"text\" name=\"contents\" required lay-verify=\"required\" placeholder=\"请输入内容\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                    </div>\n" +
                        "                </div>\n" +
                        "                <div class=\"layui-form-item\">\n" +
                        "                    <label class=\"layui-form-label\">上传文件</label>\n" +
                        "                    <div class=\"layui-input-block\">\n" +
                        "                        <input type=\"file\" name=\"file\">\n" +
                        "                    </div>\n" +
                        "                </div>");
                }
            })
        })
    </script>
@endsection