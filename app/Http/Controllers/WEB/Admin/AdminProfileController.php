<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\BannerImage;
use App\Models\BreadcrumbImage;
use Hash;
use Auth;
use Image;
use Str;
use File;
class AdminProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $admin=Auth::guard('admin')->user();
        $defaultProfile = BannerImage::whereId('15')->first()->image;
        $banner_image = BreadcrumbImage::find(8)->image;
        return view('admin.admin_profile')->with([
            'admin' => $admin,
            'defaultProfile' => $defaultProfile,
            'banner_image' => $banner_image,
        ]);
    }

    public function update(Request $request){
        $admin=Auth::guard('admin')->user();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:admins,email,'.$admin->id,
            'password'=>'confirmed',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'password.confirmed' => trans('admin_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $this->validate($request, $rules);
        $admin=Auth::guard('admin')->user();

        // inset user profile image
        if($request->file('image')){
            $old_image=$admin->image;
            $user_image=$request->image;
            $extention=$user_image->getClientOriginalExtension();
            $image_name= Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;

            Image::make($user_image)
                ->save(public_path().'/'.$image_name);

            $admin->image=$image_name;
            $admin->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->file('banner_image')){
            $old_banner_image=$admin->banner_image;
            $banner_image=$request->banner_image;
            $banner_ext=$banner_image->getClientOriginalExtension();
            $banner_name= 'agent-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$banner_ext;
            $banner_path='uploads/website-images/'.$banner_name;

            Image::make($banner_image)
                ->save(public_path().'/'.$banner_path);

            $admin->banner_image=$banner_path;
            $admin->save();
            if($old_banner_image){
                if(File::exists(public_path().'/'.$old_banner_image)) unlink(public_path().'/'.$old_banner_image);
            }

        }

        if($request->password){
            $admin->password=Hash::make($request->password);
        }
        $admin->name=$request->name;
        $admin->email=$request->email;
        $admin->address=$request->address;
        $admin->phone=$request->phone;
        $admin->website=$request->website;
        $admin->facebook=$request->facebook;
        $admin->twitter=$request->twitter;
        $admin->linkedin=$request->linkedin;
        $admin->whatsapp=$request->whatsapp;
        $admin->about=$request->about;
        $admin->save();

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.profile')->with($notification);


    }
}
