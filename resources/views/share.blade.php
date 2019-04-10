@extends('master')
@section('title')
    晒单
@endsection
    <link rel="stylesheet" href="{{url('css/sm.css')}}">
    <link href="{{url('css/single.css')}}" rel="stylesheet" type="text/css" />
    
@section('content')
    <div class="page-group">
        <div id="page-infinite-scroll-bottom" class="page">
            <!--导航-->
            <div class="column-wrapper">
                <div class="column-inner">
                    <!--首页头部-->
                    <div class="show">
                        <a href="javascript:history.back();" class=""><i></i></a>
                        <a href="javascript:;" >晒单</a>
                    </div>
                </div>
            </div>
            <!--列表内容-->
            <div id="loadingPicBlock" class="wx-show-wrapper">
                <div class="wx-show-inner">
                    <div id="divPostList" class="marginB">
                       <!--  <div class="show-list" postid="421452">
                            <div class="show-head">
                                <a href="/v44/userpage/1010835186" class="show-u blue">厦门市</a>
                                <span class="show-time">刚刚</span>
                            </div>
                            <a href="/v44/post/detail-421452.do">
                                <h3>小米USB插线板</h3>
                            </a>
                            <a href="/v44/post/detail-421452.do">
                                <div class="show-pic">
                                    <ul class="pic-more clearfix">
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135108386.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135109851.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                    </ul>
                                </div>
                                <div class="show-con">
                                    <p name="content">中奖了，心里一个美啊，激动万分，小米USB插板，家庭办公都很实用，设计非常的到位，小巧，不占地方，包装完美，做工非…</p>
                                </div>
                            </a>
                            <div class="opt-wrapper">
                                <ul class="opt-inner">
                                    <li name="wx_zan" postid="421452">
                                        <a href="javascript:;">
                                            <span class="zan wx-new-icon"></span><em>0</em>
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="show-list" postid="421452">
                            <div class="show-head">
                                <a href="/v44/userpage/1010835186" class="show-u blue">厦门市</a>
                                <span class="show-time" data-timeago="2017/7/21 14:0:0">刚刚</span>
                            </div>
                            <a href="/v44/post/detail-421452.do">
                                <h3>小米USB插线板</h3>
                            </a>
                            <a href="/v44/post/detail-421452.do">
                                <div class="show-pic">
                                    <ul class="pic-more clearfix">
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135108386.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135109851.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                    </ul>
                                </div>
                                <div class="show-con">
                                    <p name="content">中奖了，心里一个美啊，激动万分，小米USB插板，家庭办公都很实用，设计非常的到位，小巧，不占地方，包装完美，做工非…</p>
                                </div>
                            </a>
                            <div class="opt-wrapper">
                                <ul class="opt-inner">
                                    <li name="wx_zan" postid="421452">
                                        <a href="javascript:;">
                                            <span class="zan wx-new-icon"></span><em>0</em>
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="show-list" postid="421452">
                            <div class="show-head">
                                <a href="/v44/userpage/1010835186" class="show-u blue">厦门市</a>
                                <span class="show-time"  data-timeago="2017/7/21 14:0:0">刚刚</span>
                            </div>
                            <a href="/v44/post/detail-421452.do">
                                <h3>小米USB插线板</h3>
                            </a>
                            <a href="/v44/post/detail-421452.do">
                                <div class="show-pic">
                                    <ul class="pic-more clearfix">
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135108386.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135109851.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                    </ul>
                                </div>
                                <div class="show-con">
                                    <p name="content">中奖了，心里一个美啊，激动万分，小米USB插板，家庭办公都很实用，设计非常的到位，小巧，不占地方，包装完美，做工非…</p>
                                </div>
                            </a>
                            <div class="opt-wrapper">
                                <ul class="opt-inner">
                                    <li name="wx_zan" postid="421452">
                                        <a href="javascript:;">
                                            <span class="zan wx-new-icon"></span><em>0</em>
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="show-list" postid="421452">
                            <div class="show-head">
                                <a href="/v44/userpage/1010835186" class="show-u blue">厦门市</a>
                                <span class="show-time" data-timeago="2017/7/21 14:0:0">刚刚</span>
                            </div>
                            <a href="/v44/post/detail-421452.do">
                                <h3>小米USB插线板</h3>
                            </a>
                            <a href="/v44/post/detail-421452.do">
                                <div class="show-pic">
                                    <ul class="pic-more clearfix">
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135108386.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135109851.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                        <li>
                                            <img src="https://img.1yyg.net/userpost/small/20170623135112131.jpg">
                                        </li>
                                    </ul>
                                </div>
                                <div class="show-con">
                                    <p name="content">中奖了，心里一个美啊，激动万分，小米USB插板，家庭办公都很实用，设计非常的到位，小巧，不占地方，包装完美，做工非…</p>
                                </div>
                            </a>
                            <div class="opt-wrapper">
                                <ul class="opt-inner">
                                    <li name="wx_zan" postid="421452">
                                        <a href="javascript:;">
                                            <span class="zan wx-new-icon"></span><em>0</em>
                                        </a>
                                    </li>
                                    
                                    
                                </ul>
                            </div>
                        </div>  -->
                    </div>
                    <!-- 无内容时显示 -->
                    <div class="noRecords colorbbb shownocontent" style="display: none">
                        <s class="default"></s>
                        暂时还没有晒单信息哦~
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
<script src="{{url('js/timeago.min.js')}}"></script>
<script src="{{url('js/zepto.js')}}" charset="utf-8"></script>
<script src="{{url('js/sm.js')}}"></script>
<script src="{{url('js/share.js')}}"></script>
@section('my-js')
    <script>
        //下导航显示颜色
        $("#share").addClass('hover');
        $("#share").parent('li').siblings('li').children('a').removeClass('hover');
        if($('#divPostList').children().length==0){
            $('.noRecords').css('display','block');
        }
    </script>
@endsection
