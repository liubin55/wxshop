@extends('master')
@section('title')
    潮购记录
@endsection
    <link rel="stylesheet" href="{{url('css/buyrecord.css')}}">
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">潮购记录</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="buycart"></i></a>
</div>
@if($detialinfo=='')
    <div class="recordwrapp" style="display: none">
    @else
    <div class="recordwrapp" style="display: block">
    @endif
    @foreach($detialinfo as $v)
    <div class="buyrecord-con clearfix">
        <div class="record-img fl">
            <img src="{{url('images/goodsLogo/'.$v->goods_img)}}" alt="">
        </div>
        <div class="record-con fl">
            <h3>{{$v->goods_name}}</h3>
            <p class="winner">价格<i>：￥{{$v->self_price}}</i></p>
            <div class="clearfix">
                <div class="win-wrapp fl">
                    <p class="w-time">{{$v->update_time}}</p>
                    <p class="w-chao">已购买</p>
                </div>
                <div class="fr"></div>
            </div>


        </div>
    </div>
        @endforeach
</div>

<div class="nocontent">
    <div class="m_buylist m_get">
        <ul id="ul_list">
                <div class="noRecords colorbbb clearfix" style="display:block">
                        <s class="default"></s>您还没有购买商品哦~
                </div>
            <div class="hot-recom">
                <div class="title thin-bor-top gray6">
                    <span><b class="z-set"></b>人气推荐</span>
                    <em></em>
                </div>
                <div class="goods-wrap thin-bor-top">
                    <ul class="goods-list clearfix">
                        @foreach($goodsinfo as $v)
                        <li>
                            <a href="{{url('shopcontent')}}/{{$v->goods_id}}" class="g-pic">
                                <img src="{{url('images/goodsLogo/'.$v->goods_img)}}" width="136" height="136">
                            </a>
                            <p class="g-name">
                                <a href="{{url('shopcontent')}}/{{$v->goods_id}}">
                                    {{$v->goods_name}}
                                </a>
                            </p>
                            <ins class="gray9">价值:￥{{$v->self_price}}</ins>
                            <div class="btn-wrap">
                                <div class="Progress-bar">
                                    <p class="u-progress">
                                        <span class="pgbar" style="width:1%;">
                                            <span class="pging"></span>
                                        </span>
                                    </p>
                                </div>
                                <div class="gRate" goods_id="{{$v->goods_id}}" data-productid="23458">
                                    <a href="javascript:;" class=""><s></s></a>
                                </div>
                            </div>
                        </li>
                            @endforeach
                    </ul>
                </div>
            </div>
        </ul>
    </div>
</div>
<input type="hidden" id="_token" value="{{csrf_token()}}">
@endsection
@section('my-js')
    <script>
        $(function () {
            $(".footer").attr('style','display:none');
            layui.use('layer',function () {
                var layer=layui.layer;
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
            })
        })

    </script>
@endsection