@extends('adminmaster')
@section('title')
    标签列表
@endsection
@section('content')
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">标签列表</h2>
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
                            <th>标签id</th>
                            <th>标签名字</th>
                            <th>标签人数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="show">
                            @foreach($data as $v)
                            <tr tagsid="{{$v['id']}}">
                                <td>{{$v['id']}}</td>
                                <td>{{$v['name']}}</td>
                                <td>{{$v['count']}}</td>
                                <td><a href="javascript:;" class="layui-btn">删除</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <input type="hidden" id="_token" value="{{csrf_token()}}">
                    </table>
                </div>
            </div>
    </div>
@endsection

@section('my-js')
    <script>
        $(function () {
            $(document).on('click','.layui-btn',function () {
                var _this=$(this);
                var tagsid=$(this).parents('tr').attr('tagsid');
                var _token=$("#_token").val();
                $.ajax({
                    type:'post',
                    data:{tagsid:tagsid,_token:_token},
                    url:"{{url('send/tagsdel')}}",
                    dataType:'json'
                }).done(function (res) {
                    if(res.code==1){
                        layer.msg(res.font);
                        _this.parents('tr').remove();
                    }else{
                        layer.msg(res.font);
                    }
                })
            })
        })
    </script>
@endsection