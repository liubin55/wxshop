<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//主页
route::any('/',"IndexController@index");
//搜索分页缓存
route::any('search',"IndexController@search");
//登录缓存
route::any('hlogo',"IndexController@hlogo");
//修改密码缓存
route::any('uplogo',"IndexController@uplogo");
//路由组user
Route::prefix('/')->group(function(){
    route::any('login','User\UsersController@login');
    route::any('register','User\UsersController@register');
    route::post('registerajax','User\UsersController@registerajax');
    route::post('registerDo','User\UsersController@registerDo');
    route::post('loginDo','User\UsersController@loginDo');
    route::post('sendMobile','User\UsersController@sendMobile');
    route::get('resetpassword','User\UsersController@resetpassword')->middleware('logs');
    route::post('resetpassworddo','User\UsersController@resetpassworddo')->middleware('logs');
    route::get('regauth','User\UsersController@regauth')->middleware('logs');
    route::post('regauthdo','User\UsersController@regauthdo')->middleware('logs');
    route::get('userpage','User\UsersController@userpage')->middleware('logs');
    route::get('set','User\UsersController@set')->middleware('logs');
    route::get('edituser','User\UsersController@edituser')->middleware('logs');
    route::get('safeset','User\UsersController@safeset')->middleware('logs');
    route::get('mywallet','User\UsersController@mywallet')->middleware('logs');
    route::get('invite','User\UsersController@invite')->middleware('logs');
    route::get('quit','User\UsersController@quit')->middleware('logs');

});
//路由组goods
Route::prefix('/')->group(function(){
    route::post('cateshop','Goods\GoodsController@cateshop');
    route::get('cateshops/{id?}','Goods\GoodsController@cateshops');
    route::post('sortshop','Goods\GoodsController@sortshop');
    route::any('allshops','Goods\GoodsController@allshops');
    route::any('shopcontent/{id?}','Goods\GoodsController@shopcontent');
    route::any('goodsearch','Goods\GoodsController@goodsearch');
});
//路由组cart
Route::prefix('/')->group(function(){
    route::any('cartadd','Cart\CartController@cartadd');
    route::get('shopcart','Cart\CartController@shopcart')->middleware('logs');
    route::post('priceadd','Cart\CartController@priceadd')->middleware('logs');
    route::post('cartdel','Cart\CartController@cartdel')->middleware('logs');
    route::post('cartdels','Cart\CartController@cartdels')->middleware('logs');
    route::get('payment/{id?}','Cart\CartController@payment')->middleware('logs');
    route::post('orderform','Cart\CartController@orderform')->middleware('logs');
});
//验证码图片登录
route::any('verify/create','CaptchaController@create');
//路由组address
Route::group(['middleware'=>'logs','prefix'=>'/'],function () {
    route::get('address','Address\AddressController@address');
    route::get('writeaddr','Address\AddressController@writeaddr');
    route::post('addstatus','Address\AddressController@addstatus');
    route::post('addressajax','Address\AddressController@addressajax');
    route::post('writeadddo','Address\AddressController@writeadddo');
    route::post('adddel','Address\AddressController@adddel');
    route::get('addedit/{id?}','Address\AddressController@addedit');
    route::post('addeditdo','Address\AddressController@addeditdo');
});
//路由组晒单share
Route::group(['middleware'=>'logs','prefix'=>'/'],function () {
    route::get('share','Share\ShareController@share');
    route::get('willshare','Share\ShareController@willshare');
    route::get('sharedetail','Share\ShareController@sharedetail');
    route::get('buyrecord','Share\ShareController@buyrecord');
    route::get('orderwillsend','Share\ShareController@orderwillsend');
    route::get('recorddetail','Share\ShareController@recorddetail');
});
//路由组支付pay
Route::group(['middleware'=>'logs','prefix'=>'/'],function () {
    route::get('alipay','AlipayController@alipay');
    route::any('return','AlipayController@re');
    route::any('ontify','AlipayController@ontify');
});
//路由组 微信公众号
Route::prefix('wechat')->group(function (){
    route::any('/','Wechat\WechatController@index');
    route::get('uploads','Wechat\WechatController@uploads');
    route::post('uploadsDo','Wechat\WechatController@uploadsDo');
    route::get('subuploads','Wechat\WechatController@subuploads');
    route::post('subuploadsDo','Wechat\WechatController@subuploadsDo');
    route::get('subtype','Wechat\WechatController@subtype');
    route::post('subtypeDo','Wechat\WechatController@subtypeDo');
    route::get('menuadd','Wechat\WechatController@menuadd');
    route::post('menuaddDo','Wechat\WechatController@menuaddDo');
    route::get('menu','Wechat\WechatController@menu');
    route::post('menustatus','Wechat\WechatController@menustatus');
    route::get('menuupd/{id?}','Wechat\WechatController@menuupd');
    route::post('menuupdDo','Wechat\WechatController@menuupdDo');
    route::post('menudel','Wechat\WechatController@menudel');
});
//后台路由
Route::prefix('admin')->group(function (){
    route::get('/','Admin\AdminController@index');
});


Route::prefix("kaoshi")->group(function (){
   route::any('token','Kaoshi\KaoshiController@check');
});