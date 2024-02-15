<?php

namespace App\Http\Controllers\WEB\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use Hash;
use App\Models\Footer;

class StaffLoginController extends Controller
{


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::STAFF;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:staff')->except('staffLogout');
    }


    public function staffLoginForm(){
        $copywrite = Footer::first()->copyright;

        return view('staff.login')->with(['copywrite' => $copywrite]);
    }

    public function storeLoginInfo(Request $request){

        $rules = [
            'email'=>'required|email',
            'password'=>'required',
        ];
        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'password.required' => trans('admin_validation.Password is required'),

        ];
        $this->validate($request, $rules, $customMessages);



        $credential=[
            'email'=> $request->email,
            'password'=> $request->password
        ];

        $isAdmin=Admin::where('email',$request->email)->first();
        if($isAdmin){
            if($isAdmin->status==1){
                if($isAdmin->admin_type==2){
                    if(Hash::check($request->password,$isAdmin->password)){
                        if(Auth::guard('staff')->attempt($credential,$request->remember)){

                            $notification= trans('admin_validation.Login Successfully');
                            return response()->json(['success'=>$notification]);
                        }

                        $notification= trans('admin_validation.Something Went Wrong');
                        return response()->json(['error'=>$notification]);
                    }else{
                        $notification= trans('admin_validation.Invalid login information');
                        return response()->json(['error'=>$notification]);
                    }

                }else{
                    $notification= trans('admin_validation.Something Went Wrong');
                    return response()->json(['error'=>$notification]);
                }

            }else{
                $notification= trans('admin_validation.Account is deactive');
                return response()->json(['error'=>$notification]);
            }

        }else{
            $notification= trans('admin_validation.Email not exist');

            return response()->json(['error'=>$notification]);
        }

    }

    public function staffLogout(){
        Auth::guard('staff')->logout();

        $notification= trans('admin_validation.Logout Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('staff.login')->with($notification);
    }

}
