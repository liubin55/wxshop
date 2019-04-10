<?php

namespace App\Http\Controllers\Address;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Area;

class AddressController extends Controller
{
    /*
     * @content 收货地址
     */
    public function address()
    {
        $addressmodel=new Address;
        $data=$addressmodel->where(['user_id'=>session('user_id'),'address_status'=>1])->get();
        return view('address',['data'=>$data]);
    }
    //添加收货地址
    public function writeaddr()
    {
        return view('writeaddr');
    }

    /*
     * @content 设置默认收货地址
     */
    public function addstatus(Request $request)
    {
        $address_id=$request->address_id;
        $addressmodel=new Address;
        $find=$addressmodel->where(['address_id'=>$address_id])->first();
        $find->address_default=1;
        $res=$find->save();
        if($res){
            Address::where('user_id',session('user_id'))
                ->where('address_id','!=',$address_id)
                ->update(['address_default' => 2]);
            echo 1;
        }else{
            echo 2;
        }
    }

    /*
     * @content 添加地址
     */
    public function writeadddo(Request $request)
    {
        $addressmodel=new Address;
        $addressmodel->address_name=$request->address_name;
        $addressmodel->address_tel=$request->address_tel;
        $addressmodel->address_desc=$request->address_desc;
        if($request->address_default==1){
            Address::where('user_id',session('user_id'))
                ->update(['address_default' => 2]);
        }
        $addressmodel->address_default=$request->address_default;
        $addressmodel->user_id=session('user_id');
        $addressmodel->address_mail=$request->address_mail;
        $res=$addressmodel->save();
        if($res){
            echo json_encode(['font'=>'添加成功','code'=>1]);
        }else{
            echo json_encode(['font'=>'添加失败','code'=>2]);
        }
    }
    
    /*
     * @content 删除地址
     */
    public function adddel(Request $request)
    {
        $addressmodel=new Address();
        $where=[
            'address_id'=>$request->address_id,
            'user_id'=>session('user_id')
        ];
        $arr=$addressmodel->where($where)->first();
        $arr->address_status=2;
        $res=$arr->save();
        if($res){
            echo json_encode(['font'=>"删除成功",'code'=>1]);exit;
        }else{
            echo json_encode(['font'=>"删除失败",'code'=>2]);exit;
        }
    }

    /*
     * @content编辑地址
     */
    public function addedit($id)
    {
        $arr=Address::where(['address_id'=>$id])->first();
        if($arr==''){
            return redirect('address');
        }
        return view('addedit',['arr'=>$arr]);
    }
    /*
     * @content 编辑执行
     */
    public function addeditdo(Request $request)
    {
        $addressmodel=Address::find($request->address_id);
        $addressmodel->address_name=$request->address_name;
        $addressmodel->address_tel=$request->address_tel;
        $addressmodel->address_desc=$request->address_desc;
        if($request->address_default==1){
            Address::where('user_id',session('user_id'))
                ->where('address_id','!=',$request->address_id)
                ->update(['address_default' => 2]);
        }
        $addressmodel->address_default=$request->address_default;
        $addressmodel->address_mail=$request->address_mail;
        $res=$addressmodel->save();
        if($res){
            echo json_encode(['font'=>'修改成功','code'=>1]);
        }else{
            echo json_encode(['font'=>'修改失败','code'=>2]);
        }
    }




//    /*
//     * @content 三级联动
//     */
//    public function addressajax(Request $request)
//    {
//        if(empty($request->id)){
//            echo json_encode(['font'=>'请选择地址','code'=>2]);
//        }
//        //三级联动
//        $areaInfo=$this->province($request->id);
//        echo json_encode(['areaInfo'=>$areaInfo,'code'=>1]);
//    }
//    //三级联动
//    public function province($pid)
//    {
//        //三级联动
//        $areamodel=new Area;
//        $where=[
//            'pid'=>$pid
//        ];
//        $areaInfo=$areamodel->where($where)->get()->toArray();
//        return $areaInfo;
//    }

}
