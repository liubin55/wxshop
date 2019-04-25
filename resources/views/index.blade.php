@extends('master')
@section('title')
	乐美
@endsection
@section('content')
	<div class="marginB" id="loadingPicBlock">
		<!--首页头部-->
		<div class="m-block-header" >
			<div class="search" id="getLocation"></div>
			<a href="/" class="m-public-icon m-1yyg-icon"></a>
		</div>
		<!--首页头部 end-->

		<!-- 关注微信 -->
		<div id="div_subscribe" class="app-icon-wrapper" style="display: none;">
			<div class="app-icon">
				<a href="javascript:;" class="close-icon"><i class="set-icon"></i></a>
				<a href="javascript:;" class="info-icon">
					<i class="set-icon"></i>
					<div class="info">
						<p>点击关注666潮人购官方微信^_^</p>
					</div>
				</a>
			</div>
		</div>

		<!-- 焦点图 -->
		<div class="hotimg-wrapper">
			<div class="hotimg-top"></div>
			<section id="sliderBox" class="hotimg">
				<ul class="slides" style="width: 600%; transition-duration: 0.4s; transform: translate3d(-828px, 0px, 0px);">
					@foreach($data as $v)
						<li style="width: 414px; float: left; display: block;" class="clone">
							<a href="javascript:;">
								<img src="{{url('images/goodsLogo/'.$v->goods_img)}}" alt="">
							</a>
						</li>
					@endforeach
				</ul>
			</section>
		</div>

		<!--分类-->
		<div class="index-menu thin-bor-top thin-bor-bottom">
			<ul class="menu-list">
				@foreach($cate as $v)
					<li>
						<a href="{{url('cateshops')}}/{{$v->cate_id}}" id="btnNew">
							<i class="xinpin"></i>
							<span class="title">{{$v->cate_name}}</span>
						</a>
					</li>
				@endforeach
			</ul>
		</div>
		<!--导航-->
		<div class="success-tip">
			<div class="left-icon"></div>
			<ul class="right-con">
				<li>
				<span style="color: #4E555E;">
					<a href="{{url('images/0.jpg')}}" style="color: #4E555E;"><span class="username">微信公众号上线了，快点去关注吧!</span></a>
				</span>
				</li>
				<li>
				<span style="color: #4E555E;">
					<a href="javascript:;" style="color: #4E555E;">恭喜<span class="username">啊啊啊</span>获得了<span>iphone7 红色 128G 闪耀你的眼</span></a>
				</span>
				</li>
			</ul>
		</div>
		<!-- 热门推荐 -->
		<div class="line hot">
			<div class="hot-content">
				<i></i>
				<span>最热商品</span>
				<div class="l-left"></div>
				<div class="l-right"></div>
			</div>
		</div>
		<div class="hot-wrapper">
			<ul class="clearfix">
				@foreach($goodshost as $k=>$v)
					<li style="border-right:1px solid #e4e4e4; ">
						<a href="{{url('shopcontent')}}/{{$v->goods_id}}">
							<p class="title">{{$v->goods_name}}</p>
							<p class="subtitle">{{$v->goods_desc}}</p>
							<img src="{{url('images/goodsLogo/'.$v->goods_img)}}" alt="">
						</a>
					</li>
				@endforeach
			</ul>
		</div>
		<!-- 猜你喜欢 -->
		<div class="line guess">
			<div class="hot-content">
				<i></i>
				<span>猜你喜欢</span>
				<div class="l-left"></div>
				<div class="l-right"></div>
			</div>
		</div>
		<!--商品列表-->
		<div class="goods-wrap marginB">
			<ul id="ulGoodsList" class="goods-list clearfix">
				@foreach($goodsinfo as $k=>$v)
					<li id="23558" codeid="12751965" goodsid="23558" codeperiod="28436">
						<a href="{{url('shopcontent')}}/{{$v->goods_id}}" class="g-pic">
							<img class="lazy" name="goodsImg" src="{{url('images/goodsLogo/'.$v->goods_img)}}" width="136" height="136">
						</a>
						<p class="g-name">{{$v->goods_name}}</p>
						<ins class="gray9">价值：￥{{$v->self_price}}</ins>
						<div class="Progress-bar">
							<p class="u-progress">
						<span class="pgbar" style="width: 96.43076923076923%;">
							<span class="pging"></span>
						</span>
							</p>

						</div>
						<div class="btn-wrap" name="buyBox" limitbuy="0" surplus="58" totalnum="1625" alreadybuy="1567">
							<a href="{{url('shopcontent')}}/{{$v->goods_id}}" class="buy-btn" codeid="12751965">立即潮购</a>
							<div class="gRate" goods_id="{{$v->goods_id}}" codeid="12751965" canbuy="58">
								<a href="javascript:;"></a>
							</div>
						</div>
					</li>
				@endforeach
			</ul>
			<div class="loading clearfix"><b></b>正在加载</div>
		</div>
		<input type="hidden" id="_token" value="{{csrf_token()}}">
	</div>
@endsection

@section('my-js')
	<script>
		$(function () {
			$('.hotimg').flexslider({
				directionNav: false,   //是否显示左右控制按钮
				controlNav: true,   //是否显示底部切换按钮
				pauseOnAction: false,  //手动切换后是否继续自动轮播,继续(false),停止(true),默认true
				animation: 'slide',   //淡入淡出(fade)或滑动(slide),默认fade
				slideshowSpeed: 3000,  //自动轮播间隔时间(毫秒),默认5000ms
				animationSpeed: 150,   //轮播效果切换时间,默认600ms
				direction: 'horizontal',  //设置滑动方向:左右horizontal或者上下vertical,需设置animation: "slide",默认horizontal
				randomize: false,   //是否随机幻切换
				animationLoop: true   //是否循环滚动
			});
			setTimeout($('.flexslider img').fadeIn());

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
		});

		jQuery(document).ready(function() {
			$("img.lazy").lazyload({
				placeholder : "images/loading2.gif",
				effect: "fadeIn",
			});

			// 返回顶部点击事件
			$('#div_fastnav #li_menu').click(
					function(){
						if($('.sub-nav').css('display')=='none'){
							$('.sub-nav').css('display','block');
						}else{
							$('.sub-nav').css('display','none');
						}

					}
			)
			$("#li_top").click(function(){
				$('html,body').animate({scrollTop:0},300);
				return false;
			});

			$(window).scroll(function(){
				if($(window).scrollTop()>200){
					$('#li_top').css('display','block');
				}else{
					$('#li_top').css('display','none');
				}

			})


		});
	</script>

	<script>
		/*
         * 注意：
         * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
         * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
         * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
         *
         * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
         * 邮箱地址：weixin-open@qq.com
         * 邮件主题：【微信JS-SDK反馈】具体问题
         * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
         */
		wx.config({
			debug: false,
			appId: "{{$signPackage['appId']}}",
			timestamp: "{{$signPackage['timestamp']}}",
			nonceStr: "{{$signPackage['nonceStr']}}",
			signature: "{{$signPackage['signature']}}",
			jsApiList: [
				// 所有要调用的 API 都要加到这个列表中
				'onMenuShareTimeline',//分享到朋友圈
				'onMenuShareAppMessage',//分享给朋友”
				'onMenuShareQQ',//分享到QQ
				'onMenuShareWeibo',//分享到腾讯微博
				'onMenuShareQZone',//分享到QQ空间
				'getLocation',//获取地理位置
				'openLocation',//打开当前位置
			]
		});
		//点击获取地理位置
		document.querySelector('#getLocation').onclick = function () {
			wx.getLocation({
				success: function (res) {
					wx.openLocation({
						latitude: res.latitude,// 纬度，浮点数，范围为90 ~ -90
						longitude: res.longitude,// 经度，浮点数，范围为180 ~ -180。
						name: res.speed,// 位置名
						address: res.accuracy, // 地址详情说明
						scale: 14, // 地图缩放级别,整形值,范围从1~28。默认为最大
						infoUrl: 'http://weixin.qq.com'// 在查看位置界面底部显示的超链接,可点击跳转
					});
					//alert(JSON.stringify(res));
				},
				cancel: function (res) {
					alert('获取失败');
				}
			});
		};
		wx.ready(function () {
			// 在这里调用 API
			wx.onMenuShareTimeline({
				title: document.title, // 分享标题
				link: document.URL, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: "http://mmbiz.qpic.cn/mmbiz_jpg/IvGPicSwdbXOxia4BUW4ibm2Sm0EXRWSqNxW3zFPyMJfjkU0o48nXw3ZkgZnibCxYHRJtT2DXSmIV6ykntBJNSkc6w/0?wx_fmt=jpeg", // 分享图标
				success: function () {
					// 用户点击了分享后执行的回调函数
					layer.msg("分享成功")
				},
			});
			wx.onMenuShareAppMessage({
				title: document.title, // 分享标题
				desc: "乐美微商城", // 分享描述
				link: document.URL, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
				imgUrl: "http://mmbiz.qpic.cn/mmbiz_jpg/IvGPicSwdbXOxia4BUW4ibm2Sm0EXRWSqNxW3zFPyMJfjkU0o48nXw3ZkgZnibCxYHRJtT2DXSmIV6ykntBJNSkc6w/0?wx_fmt=jpeg", // 分享图标
				type: '', // 分享类型,music、video或link，不填默认为link
				dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				success: function () {
					// 用户点击了分享后执行的回调函数
					layer.msg("分享成功")
				}
			});
			wx.onMenuShareQQ({
				title: document.title, // 分享标题
				desc: '乐美微商城', // 分享描述
				link: document.URL, // 分享链接
				imgUrl: "http://mmbiz.qpic.cn/mmbiz_jpg/IvGPicSwdbXOxia4BUW4ibm2Sm0EXRWSqNxW3zFPyMJfjkU0o48nXw3ZkgZnibCxYHRJtT2DXSmIV6ykntBJNSkc6w/0?wx_fmt=jpeg", // 分享图标
				success: function () {
				// 用户确认分享后执行的回调函数
					layer.msg("分享成功")
				},
				cancel: function () {
				// 用户取消分享后执行的回调函数
					layer.msg("取消成功")
				}
			});
			wx.onMenuShareWeibo({
				title: document.title, // 分享标题
				desc: '乐美微商城', // 分享描述
				link: document.URL, // 分享链接
				imgUrl: "http://mmbiz.qpic.cn/mmbiz_jpg/IvGPicSwdbXOxia4BUW4ibm2Sm0EXRWSqNxW3zFPyMJfjkU0o48nXw3ZkgZnibCxYHRJtT2DXSmIV6ykntBJNSkc6w/0?wx_fmt=jpeg", // 分享图标
				success: function () {
					// 用户确认分享后执行的回调函数
					layer.msg("分享成功")
				},
				cancel: function () {
					// 用户取消分享后执行的回调函数
					layer.msg("取消成功")
				}
			});
			wx.onMenuShareQZone({
				title: document.title, // 分享标题
				desc: '乐美微商城', // 分享描述
				link: document.URL, // 分享链接
				imgUrl: "http://mmbiz.qpic.cn/mmbiz_jpg/IvGPicSwdbXOxia4BUW4ibm2Sm0EXRWSqNxW3zFPyMJfjkU0o48nXw3ZkgZnibCxYHRJtT2DXSmIV6ykntBJNSkc6w/0?wx_fmt=jpeg", // 分享图标
				success: function () {
					// 用户确认分享后执行的回调函数
					layer.msg("分享成功")
				},
				cancel: function () {
					// 用户取消分享后执行的回调函数
					layer.msg("取消成功")
				}
			});
		});
	</script>
@endsection