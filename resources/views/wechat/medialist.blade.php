@extends('adminmaster')
@section('title')
    素材列表
@endsection
<link rel="stylesheet" href="{{url('css/app.css')}}">
@section('content')
    <h2 style="color:burlywood">永久素材列表</h2><br>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <table class="layui-table">
                <thead>
                <tr>
                    <th>素材id</th>
                    <th>素材类型</th>
                    <th>素材标题</th>
                    <th>素材内容</th>
                    <th>media_id</th>
                    <th>图片素材地址</th>
                    <th>素材跳转地址</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->type}}</td>
                        <td>{{$v->title}}</td>
                        <td>{{$v->contents}}</td>
                        <td>{{substr($v->media_id,0,10)}}</td>
                        <td>{{substr($v->fileurl,0,20)}}</td>
                        <td>{{$v->purl}}</td>
                    </tr>
                @endforeach
                    </tbody>
            </table>
            {{$data->links()}}
        </div>
    </div>
@endsection
@section('my-js')
@endsection