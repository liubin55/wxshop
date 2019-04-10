@extends('master')
@section('title')
	我的钱包
@endsection
    <link href="{{url('css/comm.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('css/mywallet.css')}}" rel="stylesheet" type="text/css" />
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@section('content')
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">我的钱包</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>

    <div class="wallet-con">
        <ul class="registerCon clearfix">
  			<li><img src="images/goods1.jpg" alt=""></li>	
  			<li>
  				<h3>账户余额</h3>
  				<p class="red">￥<i>0</i></p>
  			</li>
  			<li class="next-icon"><s></s></li>
        </ul>
        <div class="w-item">
        	 <ul class="w-content clearfix">
	  			<li>
	  				<a href="">网银充值</a>
	  				<s class="fr"></s>
	  			</li>
	  			<li>
	  				<a href="">潮购值&nbsp;<i class="red">107</i>&nbsp;100潮购值=1元</a>
	  				<s class="fr"></s>
	  			</li>
	  			<li>
	  				<a href="">佣金&nbsp;&nbsp;<i class="red">￥0.00</i></a>
	  				<s class="fr"></s>
	  			</li>
	  			<li>
	  				<a href="">邀请成员</a>
	  				<s class="fr"></s>
	  			</li>

	  				
	        </ul>
        	
        </div>
    </div>
@endsection
@section('my-js')
	<script>
		$(".footer").attr('style','display:none');
	</script>
@endsection

