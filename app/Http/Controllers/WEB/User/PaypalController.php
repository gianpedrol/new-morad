<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;
use Cart;
use App\Models\Order;
use Auth;
use App\Models\PaymentAccount;
use App\Models\Setting;
use App\Mail\OrderSuccessfully;
use Mail;
use App\Models\EmailTemplate;
use App\Models\ListingPackage;
use App\Models\NotificationText;
use App\Models\PaypalPayment;
use App\Models\Listing;
use App\Models\Property;
use App\Models\Package;
use Session;

use App\Helpers\MailHelper;
class PaypalController extends Controller
{
    private $apiContext;
    public function __construct()
    {
        $paypal = PaypalPayment::first();
    /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $paypal->client_id,
            $paypal->secret_id,
            )
        );

        $setting=array(
            'mode' => $paypal->account_mode,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'ERROR'
        );
        $this->apiContext->setConfig($setting);
    }

    public function store($id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array(
                'messege'=>env('NOTIFY_TEXT'),
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);
        }
        // end

        $package=Package::find($id);
        if(!$package){
            $notification= trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('pricing.plan')->with($notification);
        }


        Session::put('listing_package_id',$id);

        $price=$package->price;

        $setting=Setting::first();
        $paypalSetting=PaypalPayment::first();
        $amount_usd= round($package->price * $paypalSetting->currency_rate,2);
        $name=env('APP_NAME');

        // set payer
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $currency=Setting::first();
        // set amount total
        $amount = new Amount();
        $amount->setCurrency($paypalSetting->currency_code)
            ->setTotal($amount_usd);

        // transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(env('APP_NAME'));


        // redirect url
        $redirectUrls = new RedirectUrls();

        $root_url=url('/');
        $redirectUrls->setReturnUrl($root_url."/user/paypal-payment-success")
            ->setCancelUrl($root_url."/user/paypal-payment-cancled");

        // payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
           $notification = trans('user_validation.Payment Faild');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('pricing.plan')->with($notification);
        }

        // get paymentlink
        $approvalUrl = $payment->getApprovalLink();

        return redirect($approvalUrl);
    }

    public function paymentSuccess(Request $request){

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            $notification = trans('user_validation.Payment Faild');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            $package_id=Session::get('listing_package_id');
            return redirect()->route('user.payment.page',$package_id)->with($notification);
        }

        $payment_id=$request->get('paymentId');
        $payment = Payment::get($payment_id, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() == 'approved') {

            $package_id=Session::get('listing_package_id');
            $package=Package::find($package_id);

            if(!$package){
                $notification = trans('user_validation.Something Went Wrong');
                $notification=array('messege'=>$notification,'alert-type'=>'error');

                return redirect()->route('pricing.plan')->with($notification);
            }

            $user=Auth::guard('web')->user();
            $currency=Setting::first();

            $paypalSetting=PaypalPayment::first();
            $amount_usd= round($package->price * $paypalSetting->currency_rate,2);

            $this->makeOrder($user, $payment_id, 'Paypal', 1, $package, $amount_usd);

            $notification= trans('user_validation.Congratulations! Your order has been submited');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.my-order')->with($notification);
        }

       $notification = trans('user_validation.Something Went Wrong');
        $notification=array('messege'=>$notification,'alert-type'=>'error');

        return redirect()->route('pricing.plan')->with($notification);
    }

    public function renew_store($id){

        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array(
                'messege'=>env('NOTIFY_TEXT'),
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);
        }
        // end

        $package=Package::find($id);
        if(!$package){
            $notification= trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('pricing.plan')->with($notification);
        }


        Session::put('listing_package_id',$id);

        $price=$package->price;

        $setting=Setting::first();
        $paypalSetting=PaypalPayment::first();
        $amount_usd= round($package->price * $paypalSetting->currency_rate,2);
        $name=env('APP_NAME');

        // set payer
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $currency=Setting::first();
        // set amount total
        $amount = new Amount();
        $amount->setCurrency($paypalSetting->currency_code)
            ->setTotal($amount_usd);

        // transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(env('APP_NAME'));


        // redirect url
        $redirectUrls = new RedirectUrls();

        $root_url=url('/');
        $redirectUrls->setReturnUrl($root_url."/user/renew/paypal-payment-success")
            ->setCancelUrl($root_url."/user/renew/paypal-payment-cancled");

        // payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
           $notification = trans('user_validation.Payment Faild');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            return redirect()->route('pricing.plan')->with($notification);
        }

        // get paymentlink
        $approvalUrl = $payment->getApprovalLink();

        return redirect($approvalUrl);
    }

    public function renew_paymentSuccess(Request $request){

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            $notification = trans('user_validation.Payment Faild');
            $notification=array('messege'=>$notification,'alert-type'=>'error');

            $package_id=Session::get('listing_package_id');
            return redirect()->route('user.dashboard')->with($notification);
        }

        $payment_id=$request->get('paymentId');
        $payment = Payment::get($payment_id, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() == 'approved') {

            $package_id=Session::get('listing_package_id');
            $package=Package::find($package_id);

            if(!$package){
                $notification = trans('user_validation.Something Went Wrong');
                $notification=array('messege'=>$notification,'alert-type'=>'error');

                return redirect()->route('user.dashboard')->with($notification);
            }

            $user=Auth::guard('web')->user();
            $currency=Setting::first();

            $paypalSetting=PaypalPayment::first();
            $amount_usd= round($package->price * $paypalSetting->currency_rate,2);

            $current_package = Order::where('user_id', $user->id)->where('status',1)->first();
            $this->renewOrder($user, $payment_id, 'Paypal', 1, $package, $current_package, $amount_usd);

            $notification= trans('user_validation.Congratulations! Your renew payment has been successfull');

            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.my-order')->with($notification);
        }

       $notification = trans('user_validation.Something Went Wrong');
        $notification=array('messege'=>$notification,'alert-type'=>'error');

        return redirect()->route('pricing.plan')->with($notification);
    }


    public function paymentCancled(){
        $notification = trans('user_validation.Payment Faild');
        $notification=array('messege'=>$notification,'alert-type'=>'error');

        return redirect()->route('pricing.plan')->with($notification);
    }


    public function makeOrder($user, $transaction, $payment_method, $payment_status, $package, $amount_usd){
        $setting = Setting::first();
        $activeOrder=Order::where(['user_id'=>$user->id,'status'=>1])->count();
        $oldOrders=Order::where('user_id',$user->id)->update(['status'=>0]);

        $order=new Order();
        $order->user_id=$user->id;
        $order->order_id='#'.rand(22,44).date('Ydmis');
        $order->package_id=$package->id;
        $order->purchase_date=date('Y-m-d');
        $order->expired_day=$package->number_of_days;
        $order->expired_date=$package->number_of_days ==-1 ? null : date('Y-m-d', strtotime($package->number_of_days.' days'));
        $order->payment_method= $payment_method;
        $order->transaction_id= $transaction;
        $order->payment_status= $payment_status;
        $order->amount_usd=$amount_usd;
        $order->amount_real_currency=$package->price;
        $order->currency_type=$setting->currency_name;
        $order->currency_icon=$setting->currency_icon;
        $order->status=1;
        $order->save();

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


        $order_details='Purchase Date: '.$order->purchase_date.'<br>';
        $expired_date = $order->expired_date ? $order->expired_date : 'Lifetime';
        $order_details .='Expired Date: '. $expired_date;

        // send email
        $template=EmailTemplate::where('id',6)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{user_name}}',$user->name,$message);
        $message=str_replace('{{payment_method}}',$payment_method,$message);
        $total_amount=$setting->currency_icon. $package->price;
        $message=str_replace('{{amount}}',$total_amount,$message);
        $message=str_replace('{{order_details}}',$order_details,$message);
        Mail::to($user->email)->send(new OrderSuccessfully($message,$subject));
    }


    public function renewOrder($user, $transaction, $payment_method, $payment_status, $package, $current_package, $amount_usd){
        $setting = Setting::first();
        $current_expired_date  = $current_package->expired_date;
        if($current_expired_date != null){
            $next_due_date = date('Y-m-d', strtotime($current_expired_date. ' +'.$package->number_of_days.' days'));
            $current_package = Order::find($current_package->id);
            $current_package->expired_date = $package->number_of_days ==-1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = $payment_status;
            $current_package->transaction_id = $transaction;
            $current_package->payment_method = $payment_method;
            $current_package->save();
        }else{
            $current_expired_date = date('Y-m-d');
            $next_due_date = date('Y-m-d', strtotime($current_expired_date. ' +'.$package->number_of_days.' days'));
            $current_package = Order::find($current_package->id);
            $current_package->expired_date = $package->number_of_days ==-1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = $payment_status;
            $current_package->transaction_id = $transaction;
            $current_package->payment_method = $payment_method;
            $current_package->save();
        }

        MailHelper::setMailConfig();

        $order_details='Renew Date: '.date('Y-m-d').'<br>';
        $expired_date = $current_package->expired_date ? $current_package->expired_date : 'Lifetime';
        $order_details .='Expired Date: '. $expired_date;

        // send email
        $template=EmailTemplate::where('id',9)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{user_name}}',$user->name,$message);
        $message=str_replace('{{payment_method}}',$payment_method,$message);
        $total_amount=$setting->currency_icon. $package->price;
        $message=str_replace('{{amount}}',$total_amount,$message);
        $message=str_replace('{{order_details}}',$order_details,$message);
        Mail::to($user->email)->send(new OrderSuccessfully($message,$subject));

    }



}
