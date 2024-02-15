<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserForgetPassword;
use Str;
use Mail;
use Hash;
use Auth;
use App\Rules\Captcha;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\GoogleRecaptcha;
use App\Models\BreadcrumbImage;
use App\Models\Navigation;
use App\Models\ManageText;
use App\Models\EmailTemplate;
use App\Models\NotificationText;
use App\Models\ValidationText;
use App\Helpers\MailHelper;
class ForgotPasswordController extends Controller
{


    public function forgetPassForm(){
        $banner_image = BreadcrumbImage::find(5)->image;
        $recaptcha_setting = GoogleRecaptcha::first();

        return view('auth.forget')->with([
            'banner_image' => $banner_image,
            'recaptcha_setting' => $recaptcha_setting,
        ]);

    }
   public function sendForgetEmail(Request $request){
        $rules = [
            'email'=>'required|email',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=User::where('email',$request->email)->first();

        MailHelper::setMailConfig();
        if($user){
            $user->forget_password_token=Str::random(100);
            $user->save();
            $template=EmailTemplate::where('id',1)->first();
            $message=$template->description;
            $subject=$template->subject;
            $message=str_replace('{{name}}',$user->name,$message);
            Mail::to($user->email)->send(new UserForgetPassword($message,$subject,$user));

            $notification = trans('user_validation.Forget password link send your email');
            return response()->json(['success'=>$notification]);

        }else{
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['error'=>$notification]);
        }

   }

   public function resetPassword($token){
        $user=User::where('forget_password_token',$token)->first();
        if($user){
            $banner_image = BreadcrumbImage::find(5)->image;
            $recaptcha_setting = GoogleRecaptcha::first();


            return view('auth.reset_password',compact('user','token','recaptcha_setting','banner_image'));
        }else{
            $notification = trans('user_validation.Invalid token');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return Redirect()->route('forget.password')->with($notification);
        }
   }


   public function storeResetData(Request $request,$token){
        $rules = [
            'email'=>'required|email',
            'password'=>'required|confirmed|min:3',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
            'password.min' => trans('user_validation.Password must be 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=User::where('forget_password_token',$token)->first();
        if($user->email==$request->email){
            $user->password=Hash::make($request->password);
            $user->forget_password_token=null;
            $user->save();

            $notification = trans('user_validation.Password Reset Successfully');
            return response()->json(['success'=>$notification]);

        }else{
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['error'=>$notification]);
        }
   }


}
