@extends('adminmaster')
@section('title')
    openid列表
@endsection
@section('content')
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">openid列表</h2>
        <form action="{{url('send/tagsman')}}" method="post">
            @csrf
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <table class="layui-table">
                        <colgroup>
                            <col width="150">
                            <col width="200">
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th></th>
                            <th>openid</th>
                            <th>微信名</th>
                            <th>头像</th>
                            <th>所在地</th>
                            <th>关注时间</th>
                        </tr>
                        </thead>
                        <tbody id="show">
                        @foreach($info as $k=>$v)
                            <tr>
                                <td><input type="checkbox" name="{{$k}}" value="{{$v['openid']}}"></td>
                                <td>{{$v['openid']}}</td>
                                <td>{{$v['nickname']}}</td>
                                <td><img src="{{$v['headimgurl']}}" alt="" width="50px" height="50px"></td>
                                <td>{{$v['country']}}</td>
                                <td>{{date('Y-m-d',$v['subscribe_time'])}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    选择标签：<select name="tags" id="">
                        @foreach($tags as $v)
                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input  class="layui-btn" type="submit" value="加入标签">
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('my-js')
@endsection