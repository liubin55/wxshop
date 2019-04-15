<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{url('js/layui/css/layui.css')}}">
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo"><a href="{{url('admin')}}"><span style="color:#fbfbfb">乐美微商城后台</span></a></div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href="">开发者文档</a></li>
            <li class="layui-nav-item"><a href="">控制台</a></li>
            <li class="layui-nav-item"><a href="">用户个人中心</a></li>
            <li class="layui-nav-item">
                <a href="javascript:;">其它系统</a>
                <dl class="layui-nav-child">
                    <dd><a href="">邮件管理</a></dd>
                    <dd><a href="">消息管理</a></dd>
                    <dd><a href="">授权管理</a></dd>
                </dl>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
                    贤心
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">安全设置</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="">退了</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a href="javascript:;">管理员管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">管理员列表</a></dd>
                        <dd><a href="javascript:;">管理员个人信息修改</a></dd>
                        <dd><a href="javascript:;">管理员回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">订单管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">订单列表</a></dd>
                        <dd><a href="javascript:;">修改订单</a></dd>
                        <dd><a href="javascript:;">订单回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">商品管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">商品列表</a></dd>
                        <dd><a href="javascript:;">修改商品</a></dd>
                        <dd><a href="javascript:;">商品回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">品牌管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">品牌列表</a></dd>
                        <dd><a href="javascript:;">品牌修改</a></dd>
                        <dd><a href="javascript:;">品牌回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a class="" href="javascript:;">分类管理</a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;">分类列表</a></dd>
                        <dd><a href="javascript:;">分类修改</a></dd>
                        <dd><a href="javascript:;">分类回收站</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item">
                    <a href="javascript:;">微信公众号</a>
                    <dl class="layui-nav-child">
                        <dd><a href="{{url('wechat/uploads')}}">临时素材添加</a></dd>
                        <dd><a href="{{url('wechat/subtype')}}">首次关注回复类型设置</a></dd>
                        <dd><a href="{{url('wechat/subuploads')}}">首次关注回复内容设置</a></dd>
                        <dd><a href="javascript:;" id="btn">自定义菜单</a></dd>
                        <dd><a href="{{url('wechat/menuadd')}}">添加自定义菜单</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">@yield('content')</div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        © layui.com - 底部固定区域
    </div>
</div>
<script src="{{url('js/layui/layui.js')}}"></script>
<script src="{{url('js/jquery-1.11.2.min.js')}}"></script>
<script>
    //JavaScript代码区域
    layui.use(['element','layer'], function(){
        var element = layui.element;
        var layer=layui.layer;
        $("#btn").click(function () {
            layer.confirm('是否开启自定义菜单', {icon: 3, title:'提示'}, function(index){
                //do something
                location.href="{{url('wechat/menu')}}"
                layer.close(index);
            });
        })

    });
   
</script>
@yield('my-js')
</body>
</html>
