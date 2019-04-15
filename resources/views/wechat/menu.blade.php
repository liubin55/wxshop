@extends('adminmaster')
@section('title')
    自定义菜单
@endsection
<link rel="stylesheet" href="{{url('./css/wx-custom.css')}}">
<!-- 条目中可以是任意内容，如：<img src=""> -->
@section('content')

    <div class="container">
        <div class="custom-menu-edit-con">
            <div class="hbox">
                <div class="inner-left">
                    <div class="custom-menu-view-con">
                        <div class="custom-menu-view">
                            <div class="custom-menu-view__title">Zeus-菜单展示</div>
                            <div class="custom-menu-view__body">
                                <div class="weixin-msg-list">
                                    <ul class="msg-con"></ul>
                                </div>
                            </div>
                            <div id="menuMain" class="custom-menu-view__footer">
                                <div class="custom-menu-view__footer__left"></div>
                                <div class="custom-menu-view__footer__right"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 右侧信息填写 -->
                <div class="inner-right">
                    <!-- 代码块 -->
                    <table class="layui-table">
                        <colgroup>
                            <col width="150">
                            <col width="200">
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>菜单名称</th>
                            <th>type类型</th>
                            <th>key值</th>
                            <th>跳转地址</th>
                            <th>是否启动</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="show">
                        @foreach($menuInfo as $v)
                            <tr style="display:none" pid="{{$v['pid']}}" m_id="{{$v['m_id']}}">
                                <td>
                                    <?php echo str_repeat('&nbsp;&nbsp;',$v['level']*3);?>
                                    <a href="javascript:;" class="javes">+</a>{{$v['name']}}
                                </td>
                                <td>{{$v['type']}}</td>
                                <td>{{$v['key']}}</td>
                                <td>{{$v['url']}}</td>
                                @if($v['status']==1)
                                    <td>√</td>
                                @else
                                    <td>×</td>
                                @endif
                                @if($v['status']==1)
                                    <td>
                                        <a href="javascript:;" class="layui-btn status">关闭</a>
                                        <a href="javascript:;"class="del">删除</a>
                                        <a href="{{url('wechat/menuupd')}}/{{$v['m_id']}}">修改</a>
                                    </td>
                                @else
                                    <td>
                                        <a href="javascript:;" class="layui-btn status">开启</a>
                                        <a href="javascript:;"class="del">删除</a>
                                        <a href="{{url('wechat/menuupd')}}/{{$v['m_id']}}">修改</a>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="_token" value="{{csrf_token()}}">
@endsection
{{--<marquee behavior="scroll" direction="left"><span style="font-size: 40px;color: red">找什么呢!!!</span></marquee>--}}
@section('my-js')
    <script>
        $(function () {
            //页面加载显示主分类
            $("#show").children("tr[pid=0]").show();
            //给＋号点击事件，做伸缩
            $(document).on('click','.javes',function(){
                var _this=$(this);
                //获取当前点击的值，用于判断
                var content=_this.text();
                //获取当前点击的祖父tr的cate_id
                var m_id=_this.parents('tr').attr('m_id');
                //判断+号
                if(content=="+"){
                    //定义父类id于id相同的tr
                    var _children=$("#show").children("tr[pid="+m_id+"]");
                    //判断长度，如果下面有就继续展示子级
                    _children.show();
                    _children.children('td').children("a[class='javes']").remove();
                    _this.text('-');
                }else{
                    //-号的时候调用trHide函数，把点击获取到的cate_id传过去
                    trHide(m_id);
                    _this.text("+");
                }
            })
            //递归隐藏伸缩
            function trHide(m_id){
                //获取所有与点击的祖父级的cate_id相等的父类pid的tr
                var _tr=$("#show").children("tr[pid="+m_id+"]");
                //each循环 来获取自身的cate_id，并通过递归传值继续查询，直到没有为止
                _tr.each(function(index){
                    var c_id=$(this).attr('m_id');
                    trHide(c_id);
                })
                //隐藏所有子级
                _tr.hide();
                //把隐藏的子级中的-号改为+号
                _tr.find("a[class='javes']").text('+');
            }

            //点击开启或关闭
            $(document).on('click','.status',function () {
                var _this=$(this);
                var m_id=_this.parents('tr').attr('m_id');
                var _token=$("#_token").val();
                var text_status=_this.text();
                var status='';
                if(text_status=='关闭'){
                    status=2;
                }else if(text_status=='开启'){
                    status=1;
                }
                $.ajax({
                    type:'post',
                    data:{m_id:m_id,_token:_token,status:status},
                    url:"{{url('wechat/menustatus')}}",
                    dataType:'json'
                }).done(function (res) {
                    if(res.code==1){
                        layer.msg(res.font,{time:2000},function () {
                            location.href="{{url('wechat/menu')}}"
                        });
                    }else{
                        layer.msg(res.font);
                    }
                })
            })
            //点击删除
            $(document).on('click','.del',function () {
                var _this=$(this);
                var m_id=_this.parents('tr').attr('m_id');
                var _token=$("#_token").val();
                layer.confirm('确定要删除自定义菜单', {icon: 3, title:'提示'}, function(index){
                    //do something
                    $.ajax({
                        type:'post',
                        data:{m_id:m_id,_token:_token},
                        url:"{{url('wechat/menudel')}}",
                        dataType:'json'
                    }).done(function (res) {
                        if(res.code==1){
                            layer.msg(res.font);
                            _this.parents('tr').remove();
                        }else{
                            layer.msg(res.font);
                        }
                    })
                    layer.close(index);
                });
            })
            // var obj = {
            //   "menu": {
            //     "button": [{
            //       "type": "click",
            //       "name": "今日歌曲",
            //       "key": "你好",
            //       "sub_button": []
            //     }, {
            //       "name": "菜单2",
            //       "sub_button": [{
            //         "type": "view",
            //         "name": "搜索",
            //         "url": "http:\/\/www.soso.com\/",
            //         "sub_button": []
            //       }, {
            //         "type": "miniprogram",
            //         "name": "test",
            //         "url": "http:\/\/mp.weixin.qq.com",
            //         "sub_button": [],
            //         "appid": "wx286b93c14bbf93aa",
            //         "pagepath": "pages\/lunar\/index"
            //       }]
            //     }, {
            //       "name": "菜单3",
            //       "sub_button": [{
            //         "type": "view",
            //         "name": "百度",
            //         "url": "http:\/\/www.baidu.com\/",
            //         "sub_button": []
            //       }]
            //     }]
            //   }
            // };
            var obj ={!! $data !!}; //由控制器传过来
            //显示自定义按钮组
            var button = obj.menu.button; //一级菜单[]
            var menu = '<div class="custom-menu-view__menu"><div class="text-ellipsis"></div></div>'; //显示小键盘
            var customBtns = $('.custom-menu-view__footer__right'); //显示菜单
            showMenu();
            //显示第一级菜单
            function showMenu() {
                if (button.length == 1) {
                    appendMenu(button.length);
                    showBtn();
                    $('.custom-menu-view__menu').css({
                        width: '50%',
                    });
                }
                if (button.length == 2) {
                    appendMenu(button.length);
                    showBtn();
                    $('.custom-menu-view__menu').css({
                        width: '33.3333%',
                    });
                }
                if (button.length == 3) {
                    appendMenu(button.length);
                    showBtn();
                    $('.custom-menu-view__menu').css({
                        width: '33.3333%',
                    });
                }
            }
            //显示子菜单
            function showBtn() {
                for (var i = 0; i < button.length; i++) {
                    var text = button[i].name;
                    var list = document.createElement('ul');
                    list.className = "custom-menu-view__menu__sub";
                    $('.custom-menu-view__menu')[i].childNodes[0].innerHTML = text;
                    $('.custom-menu-view__menu')[i].appendChild(list);
                    for (var j = 0; j < button[i].sub_button.length; j++) {
                        var text = button[i].sub_button[j].name;
                        var li = document.createElement("li");
                        var tt = document.createTextNode(text);
                        var div = document.createElement('div');
                        li.id = 'sub_' + i + '_' + j; //设置二级菜单id
                        div.appendChild(tt);
                        li.appendChild(div);
                        $('.custom-menu-view__menu__sub')[i].appendChild(li);
                    }
                }
            }
            //显示添加的菜单
            function appendMenu(num) {
                var menuDiv = document.createElement('div');
                var mDiv = document.createElement('div');
                var mi = document.createElement('i');
                mDiv.appendChild(mi);
                menuDiv.appendChild(mDiv)
                switch (num) {
                    case 1:
                        customBtns.append(menu);
                        customBtns.append(menuDiv);
                        break;
                    case 2:
                        customBtns.append(menu);
                        customBtns.append(menu);
                        customBtns.append(menuDiv);
                        break;
                    case 3:
                        customBtns.append(menu);
                        customBtns.append(menu);
                        customBtns.append(menu);
                        break;
                }
            }
        })
    </script>
@endsection


