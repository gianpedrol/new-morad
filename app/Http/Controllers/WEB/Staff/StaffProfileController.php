<?php
namespace App\Http\Controllers\WEB\Staff;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use App\Models\BannerImage;
use Image;
use Hash;
use File;
use Str;

class StaffProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    public function profile(){
        $admin = Auth::guard('staff')->user();
        $default_profile=BannerImage::find(15)->image;

        return view('staff.profile',compact('admin','default_profile'))->with([
            'admin' => $admin,
            'default_profile' => $default_profile,
        ]);
    }

    public function updateProfile(Request $request){

        $rules = [
            'name'=>'required',
            'email'=>'required',
            'password'=>'confirmed',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'password.required' => trans('admin_validation.Password is required'),

        ];
        $this->validate($request, $rules, $customMessages);

        $admin=Auth::guard('staff')->user();

        // inset user profile image
        if($request->file('image')){
            $old_image=$admin->image;
            $user_image=$request->image;
            $extention=$user_image->getClientOriginalExtension();
            $image_name= Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/custom-images/'.$image_name;

            Image::make($user_image)
                ->save(public_path().'/'.$image_name);


            $admin->image=$image_name;
            $admin->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        if($request->password){
            $admin->name=$request->name;
            $admin->email=$request->email;
            $admin->password=Hash::make($request->password);
            $admin->save();
        }else{
            $admin->name=$request->name;
            $admin->email=$request->email;
            $admin->save();
        }


        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');

        return redirect()->back()->with($notification);


    }
}
