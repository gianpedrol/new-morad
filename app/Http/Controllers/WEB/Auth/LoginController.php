<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use App\Rules\Captcha;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\Navigation;
use App\Models\ManageText;
use App\Models\BreadcrumbImage;
use App\Models\NotificationText;
use App\Models\ValidationText;
use App\Models\GoogleRecaptcha;
use App\Models\SocialLoginInformation;
use Socialite;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest:web')->except('userLogout');
    }

    public function userLoginPage(){
        $banner_image = BreadcrumbImage::find(5)->image;
        $recaptcha_setting = GoogleRecaptcha::first();
        $socialLogin = SocialLoginInformation::first();

        return view('auth.login',compact('banner_image','recaptcha_setting','socialLogin'));
    }

    public function storeLogin(Request $request){

        $rules = [
            'email'=>'required',
            'password'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required'),
            'password.required' => trans('user_validation.Password is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];

        $user=User::where('email',$request->email)->first();
        if($user){
            if($user->status==1){
                if(Hash::check($request->password,$user->password)){
                    if(Auth::guard('web')->attempt($credential,$request->remember)){

                        $notification = trans('user_validation.Login Successfully');

                        return response()->json(['success'=>$notification]);
                    }
                }else{
                   $notification = trans('user_validation.Invalid login information');
                    return response()->json(['error'=>$notification]);
                }

            }else{

                $notification = trans('user_validation.Inactive account');
                return response()->json(['error'=>$notification]);
            }
        }else{
            $notification = trans('user_validation.Email does not exist');
            return response()->json(['error'=>$notification]);
        }
    }


    public function redirectToGoogle(){
        SocialLoginInformation::setGoogleLoginInfo();
        return Socialite::driver('google')->redirect();
    }

    public function googleCallBack(){
        SocialLoginInformation::setGoogleLoginInfo();
        $user = Socialite::driver('google')->user();
        $user = $this->createUser($user,'google');
        auth('web')->login($user);
        return redirect()->route('user.dashboard');
    }

    public function redirectToFacebook(){
        SocialLoginInformation::setFacebookLoginInfo();
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallBack(){
        SocialLoginInformation::setFacebookLoginInfo();
        $user = Socialite::driver('facebook')->user();
        $user = $this->createUser($user,'facebook');
        auth('web')->login($user);
        return redirect()->route('user.dashboard');
    }



    function createUser($getInfo,$provider){
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $getInfo->name,
                'email'    => $getInfo->email,
                'slug' => mt_rand(10000000,99999999),
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'provider_avatar' => $getInfo->avatar,
                'status' => 1,
                'email_verified' => 1,
            ]);
        }
        return $user;
    }

    public function userLogout(){
        Auth::guard('web')->logout();

       $notification = trans('user_validation.Logout Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return Redirect()->route('login')->with($notification);
    }
}
