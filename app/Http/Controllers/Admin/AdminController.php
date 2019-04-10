<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
class AdminController extends Controller
{
    /*
     * @content后台主页
     */
    public function index()
    {
        $goods=Goods::orderBy('create_time','desc')->paginate(5);
        return view('admin.index',['goods'=>$goods]);
    }
}
