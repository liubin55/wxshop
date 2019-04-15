@extends('adminmaster')
@section('title')
    添加自定义菜单
@endsection
<link rel="stylesheet" href="{{url('css/customize.css')}}">
<!-- 条目中可以是任意内容，如：<img src=""> -->
@section('content')
        <form action="{{url('wechat/menuupdDo')}}" method="post">
            <input type="hidden" name="m_id" value="{{$data['m_id']}}">
            @csrf
            <div class="layui-form-item">
                <label class="layui-form-label">菜单名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name"  value="{{$data->name}}" required  lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">选择菜单</label>
                <div class="layui-input-block">
                    @if($data->type=='click')
                    <select name="type"  id="types">
                        <option value="">--请选择--</option>
                        <option value="click" selected>click</option>
                        <option value="view">view</option>
                    </select>
                </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">菜单Key值</label>
                        <div class="layui-input-block">
                            <input type="text" name="key"  value="{{$data->key}}" readonly="readonly" placeholder="请输入菜单Key值" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                        @elseif($data->type=='view')
                        <select name="type"  id="types">
                            <option value="">--请选择--</option>
                            <option value="click">click</option>
                            <option value="view" selected>view</option>
                        </select>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转地址</label>
                    <div class="layui-input-block">
                        <input type="url" name="url"  value="{{$data->url}}" readonly="readonly"  placeholder="请输入跳转地址" autocomplete="off" class="layui-input">
                    </div>
                </div>
                @else
                <select name="type"  id="types">
                    <option value="">--请选择--</option>
                    <option value="click">click</option>
                    <option value="view">view</option>
                </select>
                </div>
                        @endif
            </div>
            <div id="box"></div>
            <div class="layui-form-item box">
                <label class="layui-form-label">选择菜单</label>
                <div class="layui-input-block">
                    @if($data->pid==0)
                    <select name="pid" lay-verify="required">
                        <option value="">--请选择--</option>
                        <option value="0" selected>一级菜单</option>
                        @foreach($menu as $v)
                            <option value="{{$v['m_id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                        @else
                        <select name="pid" lay-verify="required">
                            <option value="">--请选择--</option>
                            <option value="0" selected>一级菜单</option>
                            @foreach($menu as $v)
                                @if($data->pid==$v['m_id'])
                                <option value="{{$v['m_id']}}" selected>{{$v['name']}}</option>
                                @else
                                    <option value="{{$v['m_id']}}">{{$v['name']}}</option>
                                @endif
                                    @endforeach
                        </select>
                        @endif
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="submit" value="提交" class="layui-btn">
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
@endsection



    {{--<marquee behavior="scroll" direction="left"><span style="font-size: 40px;color: red">找什么呢!!!</span></marquee>--}}
@section('my-js')
    <script>
        $(function () {
            $("#types").change(function () {
                var types=$(this).val();
                if(types=='click'){
                    $("#box").html("<div class=\"layui-form-item\">\n" +
                        "                <label class=\"layui-form-label\">新的菜单Key值</label>\n" +
                        "                <div class=\"layui-input-block\">\n" +
                        "                    <input type=\"text\" name=\"key\" required  lay-verify=\"required\" placeholder=\"请输入菜单Key值\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                </div>\n" +
                        "            </div>")
                }else if(types=='view'){
                    $("#box").html("<div class=\"layui-form-item\">\n" +
                        "                <label class=\"layui-form-label\">新的跳转链接</label>\n" +
                        "                <div class=\"layui-input-block\">\n" +
                        "                    <input type=\"url\" name=\"url\" required  lay-verify=\"required\" placeholder=\"请输入跳转链接\" autocomplete=\"off\" class=\"layui-input\">\n" +
                        "                </div>\n" +
                        "            </div>")
                }else{
                    $("#box").remove();
                }

            });
        })
    </script>
@endsection


