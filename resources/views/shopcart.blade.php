@extends('master')
@section('title')
    购物车
@endsection
@section('content')
    <input name="hidUserID" type="hidden" id="hidUserID" value="-1" />
    <div>
        <!--首页头部-->
        <div class="m-block-header">
            <a href="javascript:;" class="m-public-icon m-1yyg-icon"></a>
            <a href="javascript:;" class="m-index-icon">编辑</a>
        </div>
        <!--首页头部 end-->
        <div class="g-Cart-list">
            <ul id="cartBody">
                @foreach($data as $v)
                    <li goods_id="{{$v->goods_id}}">
                        <s class="xuan current"></s>
                        <a class="fl u-Cart-img" href="{{url('shopcontent')}}/{{$v->goods_id}}">
                            <img src="{{url('images/goodsLogo/'.$v->goods_img)}}" border="0" alt="">
                        </a>
                        <div class="u-Cart-r">
                            <a href="/v44/product/12501977.do" class="gray6">{{$v->goods_name}}</a>
                            <span class="gray9">
                            <em>价格：￥<font color="red">{{$v->self_price*$v->buy_number}}</font></em>
                        </span>
                            <div class="num-opt" goods_id="{{$v->goods_id}}">
                                <em class="num-mius dis min"><i></i></em>
                                <input class="text_box" name="num" maxlength="6"  self_price="{{$v->self_price}}" type="text" value="{{$v->buy_number}}" codeid="12501977">
                                <em class="num-add add"><i></i></em>
                            </div>
                            <a href="javascript:;" name="delLink" cid="12501977" isover="0" class="z-del"><s></s></a>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div id="divNone" class="empty "  style="display: none"><s></s><p>您的购物车还是空的哦~</p><a href="{{url('../')}}" class="orangeBtn">立即潮购</a></div>
        </div>
        <div id="mycartpay" class="g-Total-bt g-car-new" style="">
            <dl>
                <dt class="gray6">
                    <s class="quanxuan current"></s>全选
                    <p class="money-total">合计<em class="orange total"><span>￥</span>17.00</em></p>

                </dt>
                <dd>
                    <a href="javascript:;" id="dels" class="orangeBtn w_account remove">删除</a>
                    <a href="javascript:;" id="a_payment" class="orangeBtn w_account">去结算</a>
                </dd>
            </dl>
        </div>
        <div class="hot-recom">
            <div class="title thin-bor-top gray6">
                <span><b class="z-set"></b>人气推荐</span>
                <em></em>
            </div>
            <div class="goods-wrap thin-bor-top">
                <ul id="ulGoodsList" class="goods-list clearfix">
                    @foreach($goodsinfo as $k=>$v)
                        <li id="23558" codeid="12751965" goodsid="23558" codeperiod="28436">
                            <a href="{{url('shopcontent')}}/{{$v->goods_id}}" class="g-pic">
                                <img class="lazy" name="goodsImg" src="{{url('images/goodsLogo/'.$v->goods_img)}}">
                            </a>
                            <p class="g-name">{{$v->goods_name}}</p>
                            <ins class="gray9">价值：￥{{$v->self_price}}</ins>
                            <div class="btn-wrap" name="buyBox" limitbuy="0" surplus="58" totalnum="1625" alreadybuy="1567">
                                <a href="javascript:;" class="buy-btn" codeid="12751965">立即潮购</a>
                                <div class="gRate" goods_id="{{$v->goods_id}}" codeid="12751965" canbuy="58">
                                    <a href="javascript:;"></a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <input type="hidden" id="_token" value="{{csrf_token()}}">
        @endsection
        {{--<!---商品加减算总数---->--}}
        @section('my-js')
            <script>
                //下导航显示颜色
                $("#btnCart").addClass('hover');
                $("#btnCart").parent('li').siblings('li').children('a').removeClass('hover');
                //点击加入购物车
                $(document).on("click",".gRate",function () {
                    var goods_id=$(this).attr("goods_id");
                    var _token=$("#_token").val();
                    $.ajax({
                        type:"post",
                        url:"{{url('cartadd')}}",
                        data:{goods_id:goods_id,_token:_token},
                        dataType:'json'
                    }).done(function (res) {
                        if(res.code==3){
                            layer.msg(res.font,{icon:res.code,time:2000},function () {
                                location.href="{{url('login')}}"
                            })
                        }else{
                            layer.msg(res.font,{icon:res.code})
                        }
                    })
                })
                //点击加减
                $(function () {
                    var falg=false;
                    //点击加
                    $(".add").click(function () {

                        var buy_number=$(this).prev().val();
                        var goods_id=$(this).parent('div').attr('goods_id');
                        var type=1;
                        var _token=$("#_token").val();
                        $.ajax({
                            type:"post",
                            url:"{{url('priceadd')}}",
                            data:{goods_id:goods_id,type:type,buy_number:buy_number,_token:_token},
                            async:false
                        }).done(function (res) {
                            if(res==3){
                                layer.msg("库存不足了，不能点击了哦")
                                falg=false;
                            }else if(res==1){
                                falg=true;
                            }
                        })
                        if(falg==true){
                            var t = $(this).prev();
                            t.val(parseInt(t.val()) + 1);
                            var s=$(this).parents('div').prev('span').children('em').children('font');
                            s.text(parseInt(s.text())+parseInt(t.attr('self_price')));
                            GetCount();
                        };
                    })
                    //点击减
                    $(".min").click(function () {
                        var buy_number=$(this).next().val();
                        var goods_id=$(this).parent('div').attr('goods_id');
                        var type=2;
                        var _token=$("#_token").val();
                        $.ajax({
                            type:"post",
                            url:"{{url('priceadd')}}",
                            data:{goods_id:goods_id,type:type,buy_number:buy_number,_token:_token},
                            async:false
                        }).done(function (res) {
                            if(res==3){
                                layer.msg("已达到数量最低，不能点击了哦")
                                falg=false;
                            }else if(res==1){
                                falg=true;
                            }
                        })
                        if(falg==true){
                            var t = $(this).next();
                            if(t.val()>1){
                                t.val(parseInt(t.val()) - 1);
                                var s=$(this).parents('div').prev('span').children('em').children('font');
                                s.text(parseInt(s.text())-parseInt(t.attr('self_price')));
                                GetCount();
                            }
                        }
                    })
                })
                //删除
                $(document).on('click','.z-del',function () {
                    var _this=$(this);
                    var goods_id=$(this).prev('div').attr('goods_id');
                    var _token=$("#_token").val();
                    layer.confirm('确认删除么', {icon: 3, title:'提示'}, function(index){
                        //do something
                        $.ajax({
                            type:"post",
                            url:"{{url('cartdel')}}",
                            data:{goods_id:goods_id,_token:_token},
                            dataType: 'json'
                        }).done(function (res) {
                            if(res.code==1){
                                _this.parents('li').remove();
                                GetCount();
                            }
                            layer.msg(res.font,{icon:res.code})
                        })
                        layer.close(index);
                    });
                })
                //批删
                $("#dels").click(function () {
                    var _this=$(this);
                    var goods_id='';
                    var _token=$("#_token").val();
                    $(".g-Cart-list .xuan").each(function () {
                        if ($(this).hasClass("current")) {
                            for (var i = 0; i < $(this).length; i++) {
                                goods_id += $(this).parent('li').attr('goods_id')+',';
                            }
                        }
                    });
                    layer.confirm('确认删除所有选中的么', {icon: 3, title:'提示'}, function(index){
                        //do something
                        $.ajax({
                            type:"post",
                            url:"{{url('cartdels')}}",
                            data:{goods_id:goods_id,_token:_token},
                            dataType: 'json'
                        }).done(function (res) {
                            if(res.code==1){
                                $(".g-Cart-list .xuan").each(function () {
                                    if ($(this).hasClass("current")) {
                                        for (var i = 0; i < $(this).length; i++) {
                                            $(this).parent('li').remove();
                                        }
                                    }
                                });
                                GetCount();
                            }
                            layer.msg(res.font,{icon:res.code})
                        })
                        layer.close(index);
                    });

                })

                //结算
                $("#a_payment").click(function () {
                    var _this=$(this);
                    var goods_id='';
                    var _token=$("#_token").val();
                    $(".g-Cart-list .xuan").each(function () {
                        if ($(this).hasClass("current")) {
                            for (var i = 0; i < $(this).length; i++) {
                                goods_id += $(this).parent('li').attr('goods_id')+',';
                            }
                        }
                    });
                    goods_id=goods_id.substr(0,goods_id.length-1);
                    if(goods_id==''){
                        layer.msg("请选择需要结算的商品");
                        return false;
                    }
                    location.href="{{url('payment')}}?goods_id="+goods_id;
                });
                // 全选
                $(".quanxuan").click(function () {
                    if($(this).hasClass('current')){
                        $(this).removeClass('current');

                        $(".g-Cart-list .xuan").each(function () {
                            if ($(this).hasClass("current")) {
                                $(this).removeClass("current");
                            } else {
                                $(this).addClass("current");
                            }
                        });
                        GetCount();
                    }else{
                        $(this).addClass('current');

                        $(".g-Cart-list .xuan").each(function () {
                            $(this).addClass("current");
                            // $(this).next().css({ "background-color": "#3366cc", "color": "#ffffff" });
                        });
                        GetCount();
                    }


                });
                // 单选
                $(".g-Cart-list .xuan").click(function () {
                    if($(this).hasClass('current')){
                        $(this).removeClass('current');
                    }else{
                        $(this).addClass('current');
                    }
                    if($('.g-Cart-list .xuan.current').length==$('#cartBody li').length){
                        $('.quanxuan').addClass('current');
                    }else{
                        $('.quanxuan').removeClass('current');
                    }
                    // $("#total2").html() = GetCount($(this));
                    GetCount();
                    //alert(conts);
                });
                // 已选中的总额
                function GetCount() {
                    var conts = 0;
                    var aa = 0;
                    $(".g-Cart-list .xuan").each(function () {
                        if ($(this).hasClass("current")) {
                            for (var i = 0; i < $(this).length; i++) {
                                conts += parseInt($(this).parents('li').find('input.text_box').parents('div').prev('span').children('em').children('font').text());
                                // aa += 1;
                            }
                        }
                    });

                    $(".total").html('<span>￥</span>'+(conts).toFixed(2));
                }
                GetCount();
            </script>
@endsection