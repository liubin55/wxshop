<div class="good-list-inner">
<div id="pullrefresh" class="good-list-box  mui-content mui-scroll-wrapper">
    <div class="goodList mui-scroll">
        <ul id="ulGoodsList" class="mui-table-view mui-table-view-chevron">
            @foreach($data as $v)
            <li id="23468">
                <a href="{{url('shopcontent')}}/{{$v->goods_id}}">
                <span class="gList_l fl">
                    {{--data-original  只显示5张图--}}
                    <img class="lazy" src="{{url('images/goodsLogo/'.$v->goods_img)}}">
                </span>
                </a>
                <div class="gList_r">
                    <a href="{{url('shopcontent')}}/{{$v->goods_id}}">
                        <h3 class="gray6">{{$v->goods_name}}</h3>
                    </a>
                    <em class="gray9">价值：￥{{$v->self_price}}</em>
                    <div class="gRate">
                        <div class="Progress-bar">
                            <p class="u-progress">
                                <span style="width: 91.91286930395593%;" class="pgbar">
                                    <span class="pging"></span>
                                </span>
                            </p>
                            <ul class="Pro-bar-li">
                                <li class="P-bar01"><em>7342</em>已参与</li>
                                <li class="P-bar02"><em>7988</em>总需人次</li>
                                <li class="P-bar03"><em>{{$v->goods_num}}</em>剩余</li>
                            </ul>
                        </div>
                        <a codeid="12785750" goods_id="{{$v->goods_id}}" class="carts" canbuy="646"><s></s></a>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>

</div>