<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Navigation;
use App\Models\ManageText;
use App\Models\PaymentAccount;
use App\Models\BannerImage;
use App\NotificationText;
use App\Models\Setting;
use Auth;
use Illuminate\Pagination\Paginator;
class UserOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(){
        Paginator::useBootstrap();
        $user=Auth::guard('web')->user();
        $orders = Order::with('package')->where(['user_id'=>$user->id])->orderBy('id','desc')->paginate(10);
        $currency = Setting::first()->currency_icon;

        return view('user.order',compact('orders','currency'));
    }


    public function show($id){
        $user=Auth::guard('web')->user();
        $order=Order::where(['user_id'=>$user->id,'id'=>$id])->first();
        if($order){
            $currency = Setting::first();
            $logo = Setting::first()->logo;

            return view('user.show_order')->with([
                'order' => $order,
                'currency' => $currency,
                'logo' => $logo,
            ]);
        }else{
            $notification = trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('user.dashboard')->with($notification);
        }

    }
}
