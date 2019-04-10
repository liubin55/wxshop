@extends('master')
@section('title')
    添加晒单
@endsection
    <link rel="stylesheet" href="{{url('acss/common.css')}}">
    <link rel="stylesheet" href="{{url('acss/index.css')}}">
    <link rel="stylesheet" href="{{url('css/willshare.css')}}">

    <script src="{{url('js/zepto.js')}}" charset="utf-8"></script>
    <script src="{{url('js/imgUp.js')}}"></script>
@section('content')
    
<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">晒单</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon">提交</a>
</div>
<div class="sharecon">
  <div class="shareimg clearfix">
    <img src="images/goods1.jpg" alt="">
    <span>(第<i>345</i>潮购)小米/mi红米手机Note4X全网通</span>
  </div>
  <div class="sharecontent">
    <input type="text" placeholder="请输入标题，不少于5个字哦！">
    <textarea name="" id="" cols="30" rows="10" placeholder="来吧，表达一下您激动的心情，不少于20字哦！"></textarea>
  </div>
  <div class="z_photo upimg-div clear">
     <section class="z_file fl">
      <img src="images/upload.png" class="add-img">
      <input type="file" name="file" id="file" class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple="">
     </section>
  </div>
</div>
@endsection

@section('my-js')
<script>
    $(".footer").attr('style','display:none');
</script>
@endsection
