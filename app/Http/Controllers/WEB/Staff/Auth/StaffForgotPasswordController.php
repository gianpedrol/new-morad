<?php

namespace App\Http\Controllers\WEB\Staff\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Mail\ForgetPassword;
use App\Mail\StaffForgetPassword;
use Str;
use Mail;
use Hash;
use Auth;
use App\Models\EmailTemplate;
use App\Models\Footer;
use App\Helpers\MailHelper;

class StaffForgotPasswordController extends Controller
{
   public function forgetPassword(){
        $copyright = Footer::first()->copyright;
        return view('staff.forget_password')->with(['copyright' => $copyright]);
   }

   public function sendForgetEmail(Request $request){
        $rules = [
            'email'=>'required'
        ];

        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        MailHelper::setMailConfig();
        $admin=Admin::where('email',$request->email)->first();
        if($admin){
            $admin->forget_password_token = random_int(100000, 999999);
            $admin->save();

            $template=EmailTemplate::where('id',1)->first();
            $message=$template->description;
            $subject=$template->subject;
            $message=str_replace('{{name}}',$admin->name,$message);

            Mail::to($admin->email)->send(new StaffForgetPassword($admin,$message,$subject));


            $notification= trans('admin_validation.Password reset link send to your email');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return back()->with($notification);
        }else {

            $notification= trans('admin_validation.Email does not exist');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return Redirect()->back()->with($notification);
        }

   }

   public function resetPassword($token){
        $admin=Admin::where('forget_password_token',$token)->first();
        if($admin){

            return view('staff.reset_password',compact('admin','token'));
        }else{
            $notification= trans('admin_validation.Invalid Token');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return Redirect()->route('staff.forget-password')->with($notification);
        }
   }


   public function storeResetData(Request $request,$token){
        $rules = [
            'email'=>'required',
            'password'=>'required|confirmed|min:4'
        ];
        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'password.required' => trans('admin_validation.Password is required'),
            'password.confirmed' => trans('admin_validation.Confirm password deos not match'),
            'password.min' => trans('admin_validation.Password must be 4 characters'),
        ];

        $this->validate($request, $rules, $customMessages);

        $admin=Admin::where('forget_password_token',$token)->first();
        if($admin->email==$request->email){
            $admin->password=Hash::make($request->password);
            $admin->forget_password_token=null;
            $admin->save();

            $notification= trans('admin_validation.Password Reset Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return Redirect()->route('staff.login')->with($notification);

        }else{
            $notification= trans('admin_validation.Email does not exist');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return back()->with($notification);
        }
   }


}
