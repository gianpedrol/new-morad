<?php

namespace App\Http\Controllers\WEB\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use App\Mail\AdminForgetPasswordForWeb;
use App\Helpers\MailHelper;
use App\Models\Admin;
use App\Models\EmailTemplate;
use Str;
use Mail;
use Hash;
use Auth;
use App\Models\Setting;
class AdminForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function forgetPassword(){
        $setting = Setting::first();
       return view('admin.auth.forget',compact('setting'));
   }


   public function sendForgetEmail(Request $request){

        $rules = [
            'email'=>'required'
        ];

        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        MailHelper::setMailConfig();
        $admin=Admin::where('email',$request->email)->first();
        if($admin){
            $admin->forget_password_link = Str::random(100);
            $admin->save();

            $template=EmailTemplate::where('id',1)->first();
            $message=$template->description;
            $subject=$template->subject;
            $message=str_replace('{{name}}',$admin->name,$message);

            Mail::to($admin->email)->send(new AdminForgetPasswordForWeb($admin,$message,$subject));

            $notification = trans('admin_validation.Forget password link send your email');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else {
            $notification= trans('admin_validation.email does not exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }


    public function resetPassword($token){
        $admin=Admin::where('forget_password_link',$token)->first();
        if($admin){
            $setting = Setting::first();
            return view('admin.auth.reset-pass')->with([
                'admin' => $admin,
                'token' => $token,
                'setting' => $setting
            ]);
        }else{
            $notification= trans('admin_validation.invalid token');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.login')->with($notification);
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
            'password.confirmed' => trans('admin_validation.Password deos not match'),
            'password.min' => trans('admin_validation.Password must be 4 characters'),
        ];
        $this->validate($request, $rules,$customMessages);

        $admin=Admin::where(['forget_password_link' => $token, 'email' => $request->email])->first();
        if($admin){
            if($admin->email==$request->email){
                $admin->password = Hash::make($request->password);
                $admin->forget_password_link = null;
                $admin->save();



                $notification= trans('admin_validation.Password Reset Successfully');
                $notification = array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->route('admin.login')->with($notification);
            }else{
                $notification= trans('admin_validation.Something went wrong');
                $notification = array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }
        }else{
            $notification= trans('admin_validation.Email or token does not exist');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }


}
