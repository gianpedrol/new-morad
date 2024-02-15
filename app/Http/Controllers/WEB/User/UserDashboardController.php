<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyReview;
use App\Models\Wishlist;
use App\Models\ManageText;
use App\Models\Order;
use App\Models\Setting;
use Auth;
class UserDashboardController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function dashboard(){

        $user=Auth::guard('web')->user();
        $properties=Property::where(['user_id'=>$user->id])->get();
        $publishProperty=0;
        $expiredProperty=0;
        $clientReviews=0;
        $myReviews=PropertyReview::where('user_id',$user->id)->count();
        $wishlists=Wishlist::where('user_id',$user->id)->get();
        $orders=Order::where(['user_id'=>$user->id])->get();
        $activeOrder=Order::where(['user_id'=>$user->id,'status'=>1])->first();
        $currency=Setting::first()->currency_icon;
        foreach($properties as $property){
            if($property->status==1){
                if($property->expired_date==null){
                    $publishProperty +=1;
                }elseif($property->expired_date >= date('Y-m-d')){
                    $publishProperty +=1;
                }else{
                    $expiredProperty +=1;
                }
            }else{
                $expiredProperty +=1;
            }

            $clientReviews+= $property->reviews->count();
        }

        return view('user.dashboard')->with([
            'expiredProperty' => $expiredProperty,
            'publishProperty' => $publishProperty,
            'myReviews' => $myReviews,
            'clientReviews' => $clientReviews,
            'wishlists' => $wishlists,
            'orders' => $orders,
            'activeOrder' => $activeOrder,
            'currency' => $currency,
        ]);
    }
}
