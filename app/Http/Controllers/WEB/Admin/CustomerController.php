<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BannerImage;
use App\Models\Order;
use App\Models\Property;
use App\Models\Setting;
use App\Models\PropertyReview;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\Wishlist;
use App\Models\Package;
use App\Helpers\MailHelper;
use Mail;
use App\Mail\SendSingleSellerMail;
use Image;
use File;
use Hash;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){

        $orders = Order::all();
        $agent_id = array();

        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $customers = User::orderBy('id','desc')->whereIn('id', $agent_id)->get();

        return view('admin.agent')->with([
            'customers' => $customers
        ]);
    }

    public function regular_user(){;
        $orders = Order::all();
        $agent_id = array();

        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $customers = User::orderBy('id','desc')->whereNotIn('id', $agent_id)->get();

        return view('admin.customer')->with([
            'customers' => $customers
        ]);
    }

    public function pendingCustomerList(){
        $customers = User::with('city','seller','state', 'country')->orderBy('id','desc')->where('status',0)->get();
        $defaultProfile = BannerImage::whereId('15')->first();
        $orders = Order::all();

        return view('admin.customer', compact('customers','defaultProfile','orders'));

    }

    public function show($id){
        $customer = User::find($id);
        if($customer){
            return view('admin.show_agent',compact('customer'));

        }else{
            $notification= trans('admin_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.our-agent')->with($notification);
        }

    }

    public function reqular_user_show($id){
        $customer = User::find($id);
        if($customer){
            return view('admin.show_customer',compact('customer'));

        }else{
            $notification= trans('admin_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.regular-user')->with($notification);
        }

    }


    public function create_user(){
        return view('admin.create_user');
    }

    public function store_user(Request $request){

        $rules = [
            'name'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|min:4',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'email.unique' => trans('admin_validation.Email already exist'),
            'password.required' => trans('admin_validation.Password is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user= new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->slug = mt_rand(10000000,99999999);
        $user->password = Hash::make($request->password);
        $user->status=1;
        $user->email_verified=1;
        $user->save();

        $notification = trans('admin_validation.Create Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }


    public function assign_package(){
        $users = User::where('status',1)->get();
        $packages = Package::where('status',1)->get();
        return view('admin.assign_package',compact('users','packages'));
    }


    public function store_assign_package(Request $request){

        $rules = [
            'user'=>'required',
            'package'=>'required',
        ];

        $this->validate($request, $rules);


        $setting = Setting::first();
        $user = User::find($request->user);
        $package = Package::find($request->package);

        $activeOrder=Order::where(['user_id'=>$user->id,'status'=>1])->count();
        $oldOrders=Order::where('user_id',$user->id)->update(['status'=>0]);


        $order=new Order();
        $order->user_id=$user->id;
        $order->order_id='#'.rand(22,44).date('Ydmis');
        $order->package_id=$package->id;
        $order->purchase_date=date('Y-m-d');
        $order->expired_day=$package->number_of_days;
        $order->expired_date=$package->number_of_days ==-1 ? null : date('Y-m-d', strtotime($package->number_of_days.' days'));
        $order->payment_method= 'added_by_admin';
        $order->transaction_id= 'added_by_admin';
        $order->payment_status= 1;
        $order->amount_usd=0;
        $order->amount_real_currency=$package->price;
        $order->currency_type=$setting->currency_name;
        $order->currency_icon=$setting->currency_icon;
        $order->status= 1;
        $order->save();

        $payment_status = 1;
        if($payment_status == 1){
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
        }





        $notification = trans('admin_validation.Create Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }









    public function destroy($id)
    {
        $user = User::find($id);

        $is_property = Property::where('user_id', $id)->count();
        $is_order = Order::where('user_id', $id)->count();
        if($is_order > 1 || $is_property > 0){
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more order, property has been created under it');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
        $user_image = $user->image;
        $user->delete();
        if($user_image){
            if(File::exists(public_path().'/'.$user_image))unlink(public_path().'/'.$user_image);
        }
        Wishlist::where('user_id',$id)->delete();
        PropertyReview::where('user_id',$id)->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function changeStatus($id){
        $customer = User::find($id);
        if($customer->status == 1){
            $customer->status = 0;
            $customer->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $customer->status = 1;
            $customer->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function sendEmailToAllUser(){
        return view('admin.send_email_to_all_user');
    }



    public function sendMailToAllUser(Request $request){
        $rules = [
            'subject'=>'required',
            'message'=>'required'
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $orders = Order::all();
        $agent_id = array();

        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $users = User::where('status',1)->orderBy('id','desc')->whereNotIn('id', $agent_id)->get();

        MailHelper::setMailConfig();
        foreach($users as $user){
            Mail::to($user->email)->send(new SendSingleSellerMail($request->subject,$request->message));
        }

        $notification = trans('admin_validation.Email Send Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function sendEmailToAllAgent(){
        return view('admin.send_email_to_all_agent');
    }

    public function sendMailToAllAgent(Request $request){
        $rules = [
            'subject'=>'required',
            'message'=>'required'
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $orders = Order::all();
        $agent_id = array();

        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $users = User::where('status',1)->orderBy('id','desc')->whereIn('id', $agent_id)->get();
        MailHelper::setMailConfig();
        foreach($users as $user){
            Mail::to($user->email)->send(new SendSingleSellerMail($request->subject,$request->message));
        }

        $notification = trans('admin_validation.Email Send Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }




    public function sendMailToSingleUser(Request $request, $id){
        $rules = [
            'subject'=>'required',
            'message'=>'required'
        ];
        $customMessages = [
            'subject.required' => trans('admin_validation.Subject is required'),
            'message.required' => trans('admin_validation.Message is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = User::find($id);
        MailHelper::setMailConfig();
        Mail::to($user->email)->send(new SendSingleSellerMail($request->subject,$request->message));

        $notification = trans('admin_validation.Email Send Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
