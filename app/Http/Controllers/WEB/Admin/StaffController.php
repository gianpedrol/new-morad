<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Hash;
class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index(){
        $user=Auth::guard('admin')->user();
        if($user->admin_type==1){
            $staffs=Admin::where('admin_type',2)->get();
            $admins=Admin::all();
            return view('admin.staff',compact('staffs','admins','user'));
        }else if($user->amdin_type==0){
            $staffs=Admin::where('author_id',$user->id)->get();
            $admins=Admin::all();
            return view('admin.staff',compact('staffs','admins','user'));
        }


    }

    public function create(){
        return view('admin.create_staff');
    }


    public function store(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'password' => 'required|min:4',
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'password.required' => trans('admin_validation.Password is required'),
            'password.min' => trans('admin_validation.Password Must be 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=Auth::guard('admin')->user();
        $admin=new Admin();
        $admin->name=$request->name;
        $admin->slug = mt_rand(1000000,9999999);
        $admin->email=$request->email;
        $admin->password=Hash::make($request->password);
        $admin->admin_type=2;
        $admin->author_id=$user->id;
        $admin->status=$request->status;
        $admin->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->route('admin.staff.index')->with($notification);
    }

    public function destroy($id){

        $isAdmin=Admin::find($id);
        if($isAdmin){
            $old_image=$isAdmin->image;
            $isAdmin->delete();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }


            $notification= trans('admin_validation.Delete successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }else{

            $notification= trans('admin_validation.Something Went Wrong');
            return back()->with($notification);
        }
    }

    // manage blog status
    public function changeStatus($id){
        $admin=Admin::find($id);
        if($admin->status==1){
            $admin->status=0;
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $admin->status=1;
            $message= trans('admin_validation.Active Successfully');
        }
        $admin->save();
        return response()->json($message);

    }
}
