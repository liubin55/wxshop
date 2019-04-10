@extends('adminmaster')
@section('title')
    乐美微商城后台
@endsection
<!-- 条目中可以是任意内容，如：<img src=""> -->
@section('content')
    <audio src="{{url('images/K.D - 溯.mp3')}}" autoplay="autoplay" controls="controls" loop="loop"></audio>
    <div class="layui-carousel" id="test1" >
        <div carousel-item>
            @foreach($goods as $v)
            <div><img src="{{url("images/goodsLogo/".$v->goods_img)}}" alt="" width="1200px" height="280px"></div>
                @endforeach
        </div>
    </div>
    <hr class="layui-bg-red">
    <div style="width: 500px;float: left;border: #1b4b72 2px solid;padding: 20px">
    <ul class="layui-timeline">
        @foreach($goods as $v)
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
            <div class="layui-timeline-content layui-text">
                <h3 class="layui-timeline-title">{{$v->create_time}}</h3>
                <p>
                    在黎明之前，黑暗相伴。
                    <br>想要抓住，却始终触摸不到。
                    <br>无论它能走多远，抑或如何支撑？至少我曾倾注全心，无怨无悔 <i class="layui-icon"></i>
                </p>
            </div>
        </li>
        @endforeach
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
            <div class="layui-timeline-content layui-text">
                <div class="layui-timeline-title">过去</div>
            </div>
        </li>
    </ul>
    </div>
    <div style="float: left;border: #00F7DE 2px solid;margin-left: 100px;margin-top: 50px;padding: 20px">
        <h2 style="color: #1b4b72;margin-top: 20px;margin-bottom: 20px">Admin后台人员添加</h2>
        <div style="margin: 20px">
            <form class="layui-form" action="">
                @csrf
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" name="title" required  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所在城市</label>
            <div class="layui-input-block">
                <select name="city" lay-verify="required">
                    <option value=""></option>
                    <option value="0">北京</option>
                    <option value="1">上海</option>
                    <option value="2">广州</option>
                    <option value="3">深圳</option>
                    <option value="4">杭州</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">爱好</label>
            <div class="layui-input-block">
                <input type="checkbox" name="like[write]" title="写作">
                <input type="checkbox" name="like[read]" title="阅读" checked>
                <input type="checkbox" name="like[dai]" title="发呆">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value="男" title="男">
                <input type="radio" name="sex" value="女" title="女" checked>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">个人简介</label>
            <div class="layui-input-block">
                <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
        </div>
    </div>
@endsection
@section('my-js')
    <script>
        layui.use('carousel', function(){
            var carousel = layui.carousel;
            //建造实例
            carousel.render({
                elem: '#test1'
                ,width: '100%' //设置容器宽度
                ,arrow: 'always' //始终显示箭头
                //,anim: 'updown' //切换动画方式
            });

        });
        layui.use('form', function(){
            var form = layui.form;

            //监听提交
            form.on('submit(formDemo)', function(data){
                layer.msg(JSON.stringify(data.field));
                return false;
            });
        });
    </script>
@endsection


