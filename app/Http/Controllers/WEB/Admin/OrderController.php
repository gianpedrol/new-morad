<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Package;
use App\Models\Property;
use App\Mail\PaymentAccept;
use App\Models\EmailTemplate;
use Mail;
use App\Helpers\MailHelper;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $orders = Order::with('user','package')->orderBy('id','desc')->get();
        $title = trans('admin_validation.All Orders');
        $currency = Setting::first()->currency_icon;

        return view('admin.order')->with([
            'title' => $title,
            'orders' => $orders,
            'currency' => $currency,
        ]);

    }

    public function pendingOrder(){
        $orders = Order::with('user','package')->orderBy('id','desc')->where('payment_status',0)->get();
        $title = trans('admin_validation.Pending Payment');
        $currency = Setting::first()->currency_icon;

        return view('admin.order')->with([
            'title' => $title,
            'orders' => $orders,
            'currency' => $currency,
        ]);
    }


    public function pendingRenewOrder(){
        $orders = Order::with('user','package')->orderBy('id','desc')->where('renew_payment_status',0)->get();
        $title = trans('admin.Pending Renew Payment');
        $currency = Setting::first()->currency_icon;

        return view('admin.pending_renew')->with([
            'title' => $title,
            'orders' => $orders,
            'currency' => $currency,
        ]);
    }





    public function renew_show($id){
        $order = Order::with('user','package')->find($id);
        $currency = Setting::first()->currency_icon;
        return view('admin.renew_show')->with([
            'order' => $order,
            'currency' => $currency,
        ]);
    }

    public function show($id){
        $order = Order::with('user','package')->find($id);
        $currency = Setting::first()->currency_icon;
        return view('admin.show_order')->with([
            'order' => $order,
            'currency' => $currency,
        ]);
    }


    public function approve_renew_payment($id){
        $setting = Setting::first();
        $order=Order::find($id);
        $user=$order->user;

        $package=Package::find($order->package_id);

        $current_package= Order::find($id);


        $current_expired_date  = $current_package->expired_date;
        if($current_expired_date != null){
            $next_due_date = date('Y-m-d', strtotime($current_expired_date. ' +'.$package->number_of_days.' days'));
            $current_package->expired_date = $package->number_of_days ==-1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = 1;
            $current_package->save();
        }else{
            $current_expired_date = date('Y-m-d');
            $next_due_date = date('Y-m-d', strtotime($current_expired_date. ' +'.$package->number_of_days.' days'));
            $current_package->expired_date = $package->number_of_days ==-1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = 1;
            $current_package->save();
        }


        MailHelper::setMailConfig();

        $order_details='Renew Date: '.date('Y-m-d').'<br>';
        $expired_date = $current_package->expired_date ? $current_package->expired_date : 'Lifetime';
        $order_details .='Expired Date: '. $expired_date;

        // send email
        $template=EmailTemplate::where('id',8)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{user_name}}',$user->name,$message);
        Mail::to($user->email)->send(new PaymentAccept($message,$subject));


        $notification = trans('admin_validation.Approved successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.pending-renew-order')->with($notification);

    }


    public function approve_payment($id){
        $order=Order::find($id);
        $user=$order->user;
        $oldOrders=Order::where('user_id',$user->id)->update(['status'=>0]);
        $order->payment_status=1;
        $order->status=1;
        $order->save();

        $package=Package::find($order->package_id);
        // active and  in-active minimum limit listing
        $userProperties=Property::where('user_id',$user->id)->orderBy('id','desc')->get();
        if($userProperties->count() !=0){
            if($package->number_of_property !=-1){
                foreach($userProperties as $index => $listing){
                    if(++$index <= $package->number_of_property){
                        $listing->status=1;
                        $listing->save();
                    }else{
                        $listing->status=0;
                        $listing->save();
                    }
                }
            }elseif($package->number_of_property ==-1){
                foreach($userProperties as $index => $listing){
                    $listing->status=1;
                    $listing->save();
                }
            }
        }
        // end inactive

        // setup expired date
        if($userProperties->count() != 0){
            foreach($userProperties as $index => $listing){
                $listing->expired_date=$order->expired_date;
                $listing->save();
            }
        }

        MailHelper::setMailConfig();
        $template=EmailTemplate::where('id',8)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{user_name}}',$user->name,$message);
        Mail::to($user->email)->send(new PaymentAccept($message,$subject));


        $notification = trans('admin_validation.Approved successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id){
        $order = Order::find($id);
        $order->delete();

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);
    }
}


