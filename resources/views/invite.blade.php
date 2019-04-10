@extends('master')
@section('title')
	邀请有奖
@endsection
    <link rel="stylesheet" href="{{url('css/invite.css')}}">
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">邀请有奖</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>
<div class="invit-box">
	<div class="invit-wrapp">
		<div class="invit-top">
			<s></s>
			<p>邀请好友参与得7%消费返佣！每天分享朋友圈及微博，累计可获得100潮购值</p>
		</div>
		<div class="invit-middle">
			<img src="images/ewm.png" alt="">
		</div>
		<div class="-mob-share-ui-button -mob-share-open " style="text-align: center;color: beige">分享</div>
		<div class="-mob-share-ui -mob-share-ui-theme -mob-share-ui-theme-slide-bottom" style="display:none">
		    <ul class="-mob-share-list">
		        <li class="-mob-share-weibo"><p>新浪微博</p></li>
		        <li class="-mob-share-qq"><p>QQ好友</p></li>
		        <li class="-mob-share-qzone"><p>QQ空间</p></li>
		        
		    </ul>
		    <div class="-mob-share-close">取消</div>
		</div>
		<div class="-mob-share-ui-bg"></div>
	</div>
		
</div>
@endsection
@section('my-js')
	<script>
		$(".footer").attr('style','display:none');
		mobShare.config( {

			appkey: '1fdd78296fa81', // appkey
			params: {
				url: 'http://www.666crg.com/mobile/invitereg?user_id=10050072', // 分享链接
				// title: '中奖了', // 分享标题
				description: '#潮购就来666潮人购#我的天呐！这里的iPhone 7最低只要1块钱，邀请好友一起抢还有现金奖励呢！ ' // 分享内容
			}

		} );
	</script>
@endsection
