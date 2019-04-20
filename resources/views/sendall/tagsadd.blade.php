@extends('adminmaster')
@section('title')
    添加标签
@endsection
@section('content')
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">标签添加</h2>

        <form action="{{url('send/gettags')}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="layui-form-item">
                <label class="layui-form-label">标签名：</label>
                <div class="layui-input-inline">
                    <input type="text" name="contents" required lay-verify="required" placeholder="请输入标签名称" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input  class="layui-btn" type="submit" value="添加">
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('my-js')
@endsection