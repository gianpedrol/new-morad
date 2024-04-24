<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Order;
use App\Models\Setting;
use App\Models\PaymentAccount;
use App\Models\NotificationText;
use App\Models\Navigation;
use App\Models\ManageText;
use App\Models\BannerImage;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use App\Mail\OrderSuccessfully;
use Mail;
use Auth;
use Session;
use Stripe;
use App\Models\Listing;
use App\Models\Property;
use App\Models\Razorpay;
use App\Models\PaymongoPayment;
use App\Models\Flutterwave;
use App\Helpers\MailHelper;
use Str;
use Razorpay\Api\Api;
use Exception;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use Redirect;
use Mollie\Laravel\Facades\Mollie;

use App\Models\PaypalPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;

use App\Models\BankPayment;
use App\Models\CurrencyCountry;
use App\Models\Currency;
use App\Models\BreadcrumbImage;
use App\Models\MercadopagoPayment;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function renew_package($id)
    {
        $package = Package::find($id);
        if ($package) {
            $user = Auth::guard('web')->user();

            $banner_image = BreadcrumbImage::find(9)->image;
            $currency = Setting::first();
            $setting = Setting::first();

            $package_price = $package->price;
            $mercadopagoPaymentInfo = MercadopagoPayment::first();

            return view('renew_package', compact('banner_image', 'currency', 'setting', 'package', 'package_price', 'user', 'mercadopagoPaymentInfo'));
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }

    public function purchase($id)
    {

        $package = Package::find($id);
        $user = Auth::guard('web')->user();;
        if ($package) {
            if ($package->package_type == 0) {

                // project demo mode check
                if (env('PROJECT_MODE') == 0) {
                    $notification = array(
                        'messege' => env('NOTIFY_TEXT'),
                        'alert-type' => 'error'
                    );

                    return redirect()->back()->with($notification);
                }
                // end

                $setting = Setting::first();
                $activeOrder = Order::where(['user_id' => $user->id, 'status' => 1])->count();
                $oldOrders = Order::where('user_id', $user->id)->update(['status' => 0]);
                $order = new Order();
                $order->user_id = $user->id;
                $order->order_id = '#' . rand(22, 44) . date('Ydmis');
                $order->package_id = $package->id;
                $order->purchase_date = date('Y-m-d');
                $order->expired_day = $package->number_of_days;
                $order->expired_date = date('Y-m-d', strtotime($package->number_of_days . ' days'));
                $order->status = 1;
                $order->payment_status = 1;
                $order->amount_usd = 0;
                $order->amount_real_currency = 0;
                $order->currency_type = $setting->currency_name;
                $order->currency_icon = $setting->currency_icon;
                $order->save();

                // active and  in-active minimum limit listing
                $userProperties = Property::where('user_id', $user->id)->orderBy('id', 'desc')->get();
                if ($userProperties->count() != 0) {
                    if ($package->number_of_property != -1) {
                        foreach ($userProperties as $index => $listing) {
                            if (++$index <= $package->number_of_property) {
                                $listing->status = 1;
                                $listing->save();
                            } else {
                                $listing->status = 0;
                                $listing->save();
                            }
                        }
                    } elseif ($package->number_of_property == -1) {
                        foreach ($userProperties as $index => $listing) {
                            $listing->status = 1;
                            $listing->save();
                        }
                    }
                }
                // end inactive


                // setup expired date
                if ($userProperties->count() != 0) {
                    foreach ($userProperties as $index => $listing) {
                        $listing->expired_date = $order->expired_date;
                        $listing->save();
                    }
                }


                $notification = trans('user_validation.Congratulations! Your order has been submited');
                $notification = array('messege' => $notification, 'alert-type' => 'success');
                return redirect()->route('user.my-order')->with($notification);
            } else {

                $banner_image = BreadcrumbImage::find(9)->image;
                $currency = Setting::first();
                $setting = Setting::first();
                $stripe = StripePayment::first();
                $paypal = PaypalPayment::first();
                $package_price = $package->price;
                $razorpay = RazorpayPayment::first();
                $flutterwave = Flutterwave::first();
                $bank_payment = BankPayment::first();
                $paymentSetting = $stripe;
                $paystack = PaystackAndMollie::first();
                $instamojo = InstamojoPayment::first();
                $paymongo = PaymongoPayment::first();
                $mercadopagoPaymentInfo = MercadopagoPayment::first();

                return view('payment', compact('banner_image', 'currency', 'setting', 'stripe', 'paypal', 'package', 'package_price', 'razorpay', 'paymentSetting', 'flutterwave', 'user', 'paystack', 'instamojo', 'paymongo', 'bank_payment', 'mercadopagoPaymentInfo'));
            }
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }

    public function stripePayment(Request $request, $id)
    {

        $stripe = StripePayment::first();
        $currency = Setting::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();


        if ($package) {
            Stripe\Stripe::setApiKey($stripe->stripe_secret);

            $setting = Setting::first();
            $amount_usd = round($package->price * $stripe->currency_rate, 2);
            $payableAmount = round($package->price * $stripe->currency_rate, 2);
            $result = Stripe\Charge::create([
                "amount" => $payableAmount * 100,
                "currency" => $stripe->currency_code,
                "source" => $request->stripeToken,
                "description" => env('APP_NAME')
            ]);

            $this->makeOrder($user, $result->balance_transaction, 'Stripe', 1, $package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your order has been submited');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }

    public function bankPayment(Request $request)
    {

        $this->validate($request, [
            'tran_id' => 'required'
        ]);

        $currency = Setting::first();
        $setting = Setting::first();
        $package = Package::find($request->package_id);
        $user = Auth::guard('web')->user();
        $amount_usd = round($package->price / $setting->currency_rate, 2);
        if ($package) {

            $this->makeOrder($user, $request->tran_id, 'Bank Payment', 0, $package, $amount_usd);


            $notification = trans('user_validation.Order submited successfully. please wait for admin approval');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }


    public function razorPay(Request $request, $id)
    {


        $razorpay = RazorpayPayment::first();
        $input = $request->all();
        $api = new Api($razorpay->key, $razorpay->secret_key);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $payId = $response->id;
                $currency = Setting::first();
                $package = Package::find($id);
                $user = Auth::guard('web')->user();
                if ($package) {

                    $setting = Setting::first();
                    $amount_usd = round($package->price * $razorpay->currency_rate, 2);

                    $this->makeOrder($user, $payId, 'Razorpay', 1, $package, $amount_usd);

                    $notification = trans('user_validation.Congratulations! Your order has been submited');
                    $notification = array('messege' => $notification, 'alert-type' => 'success');
                    return redirect()->route('user.my-order')->with($notification);
                } else {

                    $notification = trans('user_validation.Something Went Wrong');
                    $notification = array('messege' => $notification, 'alert-type' => 'error');

                    return redirect()->route('pricing.plan')->with($notification);
                }
            } catch (Exception $e) {
                $notification = trans('user_validation.Something Went Wrong');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }
        }
        return "payment success";
    }

    public function flutterWavePayment(Request $request)
    {

        $flutterwave = Flutterwave::first();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $flutterwave->secret_key;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response->status == 'success') {
            $currency = Setting::first();
            $package = Package::find($request->package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();
            $amount_usd = round($package->price * $flutterwave->currency_rate, 2);

            $this->makeOrder($user, $tnx_id, 'Flutterwave', 1, $package, $amount_usd);


            $notification = trans('user_validation.Congratulations! Your order has been submited');
            return response()->json(['status' => 'success', 'message' => $notification]);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            return response()->json(['status' => 'faild', 'message' => $notification]);
        }
    }

    public function paystackPayment(Request $request)
    {

        $paystack = PaystackAndMollie::first();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $paystack->paystack_secret_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret_key",
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $final_data = json_decode($response);
        if ($final_data->status == true) {

            $currency = Setting::first();
            $package = Package::find($request->package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();

            $amount_usd = round($package->price * $paystack->paystack_currency_rate, 2);

            $this->makeOrder($user, $transaction, 'Paystack', 1, $package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your order has been submited');
            return response()->json(['status' => 'success', 'message' => $notification]);
        }
    }

    public function molliePayment($id)
    {
        $mollie = PaystackAndMollie::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();
        $payableAmount = round($package->price * $mollie->mollie_currency_rate);

        $payableAmount = number_format($payableAmount, 2);
        $mollie_api_key = $mollie->mollie_key;
        $currency = strtoupper($mollie->mollie_currency_code);
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $currency,
                'value' => '' . $payableAmount . '',
            ],
            'description' => env('APP_NAME'),
            'redirectUrl' => route('user.mollie-payment-success'),
        ]);


        $payment = Mollie::api()->payments()->get($payment->id);
        session()->put('payment_id', $payment->id);
        session()->put('package_id', $id);
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function molliePaymentSuccess(Request $request)
    {

        $mollie = PaystackAndMollie::first();
        $mollie_api_key = $mollie->mollie_key;
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()) {
            $package_id = Session::get('package_id');
            $payment_id = Session::get('payment_id');
            $package = Package::find($package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();
            $currency = $setting;

            $amount_usd = round($package->price * $mollie->mollie_currency_rate, 2);

            $this->makeOrder($user, $payment_id, 'Mollie', 1, $package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your order has been submited');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }

    public function payWithInstamojo($id)
    {

        $instamojoPayment = InstamojoPayment::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();
        $payableAmount = round($package->price * $instamojoPayment->currency_rate);
        $setting = Setting::first();
        $price = $payableAmount;

        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . 'payment-requests/');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"
            )
        );
        $payload = array(
            'purpose' => env("APP_NAME"),
            'amount' => $price,
            'phone' => '918160651749',
            'buyer_name' => Auth::user()->name,
            'redirect_url' => route('user.instamojo-response'),
            'send_email' => true,
            'webhook' => 'http://www.example.com/webhook/',
            'send_sms' => true,
            'email' => Auth::user()->email,
            'allow_repeated_payments' => false
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        session()->put('package_id', $id);
        return redirect($response->payment_request->longurl);
    }

    public function instamojoResponse(Request $request)
    {

        $input = $request->all();

        $instamojoPayment = InstamojoPayment::first();
        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . 'payments/' . $request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"
            )
        );
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('pricing.plan')->with($notification);
        } else {
            $data = json_decode($response);
        }

        if ($data->success == true) {
            if ($data->payment->status == 'Credit') {
                $package_id = Session::get('package_id');
                $payment_id = Session::get('payment_id');
                $package = Package::find($package_id);
                $user = Auth::guard('web')->user();
                $setting = Setting::first();
                $currency = $setting;
                $instamojoPayment = InstamojoPayment::first();

                $amount_usd = round($package->price * $instamojoPayment->currency_rate, 2);

                $this->makeOrder($user, $request->payment_id, 'Instamojo', 1, $package, $amount_usd);

                $notification = trans('user_validation.Congratulations! Your order has been submited');
                $notification = array('messege' => $notification, 'alert-type' => 'success');
                return redirect()->route('user.my-order')->with($notification);
            }
        }
    }


    public function payWithMercadoPago(Request $request)
    {
        $package = Package::find($request->package_id);
        $mercadopagoPaymentInfo = MercadopagoPayment::first();
        $total_price = $package->price;
        $payableAmount = round($total_price * $mercadopagoPaymentInfo->currency_rate, 2);
        require_once 'vendor/autoload.php';
        \MercadoPago\SDK::setAccessToken($mercadopagoPaymentInfo->access_token);

        try {
            $payment = new \MercadoPago\Payment();
            $payment->transaction_amount = $payableAmount;
            $payment->token = $request->token;
            $payment->description = env('APP_NAME');
            $payment->installments = 1;
            $payment->payment_method_id = $request->paymentMethodId;
            $payment->payer = array(
                "email" => "user@gmail.com"
            );
            $payment->save();
        } catch (Exception $ex) {

            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }


        if ($payment->status == 'approved') {
            $payment_id = $payment->id;
            $user = Auth::guard('web')->user();
            $amount_usd = round($package->price * $mercadopagoPaymentInfo->currency_rate, 2);

            $this->makeOrder($user, $payment_id, 'Mercadopago', 1, $package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your order has been submited');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }


    public function makeOrder($user, $transaction, $payment_method, $payment_status, $package, $amount_usd)
    {
        $setting = Setting::first();

        $activeOrder = Order::where(['user_id' => $user->id, 'status' => 1])->count();
        if ($payment_status == 1) {
            $oldOrders = Order::where('user_id', $user->id)->update(['status' => 0]);
        }


        $order = new Order();
        $order->user_id = $user->id;
        $order->order_id = '#' . rand(22, 44) . date('Ydmis');
        $order->package_id = $package->id;
        $order->purchase_date = date('Y-m-d');
        $order->expired_day = $package->number_of_days;
        $order->expired_date = $package->number_of_days == -1 ? null : date('Y-m-d', strtotime($package->number_of_days . ' days'));
        $order->payment_method = $payment_method;
        $order->transaction_id = $transaction;
        $order->payment_status = $payment_status;
        $order->amount_usd = $amount_usd;
        $order->amount_real_currency = $package->price;
        $order->currency_type = $setting->currency_name;
        $order->currency_icon = $setting->currency_icon;
        $order->status = $payment_status == 0 ? '-1' : 1;
        $order->save();

        if ($payment_status == 1) {
            // active and  in-active minimum limit listing
            $userProperties = Property::where('user_id', $user->id)->orderBy('id', 'desc')->get();
            if ($userProperties->count() != 0) {
                if ($package->number_of_property != -1) {
                    foreach ($userProperties as $index => $listing) {
                        if (++$index <= $package->number_of_property) {
                            $listing->status = 1;
                            $listing->save();
                        } else {
                            $listing->status = 0;
                            $listing->save();
                        }
                    }
                } elseif ($package->number_of_property == -1) {
                    foreach ($userProperties as $index => $listing) {
                        $listing->status = 1;
                        $listing->save();
                    }
                }
            }
            // end inactive

            // setup expired date
            if ($userProperties->count() != 0) {
                foreach ($userProperties as $index => $listing) {
                    $listing->expired_date = $order->expired_date;
                    $listing->save();
                }
            }
        }

        MailHelper::setMailConfig();


        $order_details = 'Purchase Date: ' . $order->purchase_date . '<br>';
        $expired_date = $order->expired_date ? $order->expired_date : 'Lifetime';
        $order_details .= 'Expired Date: ' . $expired_date;

        // send email
        $template = EmailTemplate::where('id', 6)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{payment_method}}', $payment_method, $message);
        $total_amount = $setting->currency_icon . $package->price;
        $message = str_replace('{{amount}}', $total_amount, $message);
        $message = str_replace('{{order_details}}', $order_details, $message);
        Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));
    }



    // renew order
    public function renewBankPayment(Request $request)
    {
        $this->validate($request, [
            'tran_id' => 'required'
        ]);

        $currency = Setting::first();
        $setting = Setting::first();
        $package = Package::find($request->package_id);
        $user = Auth::guard('web')->user();

        $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
        $amount_usd = round($package->price / $setting->currency_rate, 2);
        if ($package) {

            $current_package->renew_payment_status = 0;
            $current_package->transaction_id = $request->tran_id;
            $current_package->payment_method = 'Bank Payment';
            $current_package->save();

            $notification = trans('user_validation.Order submited successfully. please wait for admin approval');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }



    public function renewStripePayment(Request $request, $id)
    {

        $stripe = StripePayment::first();
        $currency = Setting::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();


        if ($package) {
            Stripe\Stripe::setApiKey($stripe->stripe_secret);

            $setting = Setting::first();
            $amount_usd = round($package->price * $stripe->currency_rate, 2);
            $payableAmount = round($package->price * $stripe->currency_rate, 2);
            $result = Stripe\Charge::create([
                "amount" => $payableAmount * 100,
                "currency" => $stripe->currency_code,
                "source" => $request->stripeToken,
                "description" => env('APP_NAME')
            ]);

            $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();

            $this->renewOrder($user, $result->balance_transaction, 'Stripe', 1, $package, $current_package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }


    public function renewRazorPay(Request $request, $id)
    {
        $razorpay = RazorpayPayment::first();
        $input = $request->all();
        $api = new Api($razorpay->key, $razorpay->secret_key);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $payId = $response->id;
                $currency = Setting::first();
                $package = Package::find($id);
                $user = Auth::guard('web')->user();
                if ($package) {
                    $setting = Setting::first();
                    $amount_usd = round($package->price * $razorpay->currency_rate, 2);
                    $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
                    $this->renewOrder($user, $payId, 'Razorpay', 1, $package, $current_package, $amount_usd);

                    $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
                    $notification = array('messege' => $notification, 'alert-type' => 'success');
                    return redirect()->route('user.my-order')->with($notification);
                } else {

                    $notification = trans('user_validation.Something Went Wrong');
                    $notification = array('messege' => $notification, 'alert-type' => 'error');

                    return redirect()->route('pricing.plan')->with($notification);
                }
            } catch (Exception $e) {
                $notification = trans('user_validation.Something Went Wrong');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }
        }
        return "payment success";
    }


    public function renewFlutterWavePayment(Request $request)
    {

        $flutterwave = Flutterwave::first();
        $curl = curl_init();
        $tnx_id = $request->tnx_id;
        $url = "https://api.flutterwave.com/v3/transactions/$tnx_id/verify";
        $token = $flutterwave->secret_key;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if ($response->status == 'success') {
            $currency = Setting::first();
            $package = Package::find($request->package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();
            $amount_usd = round($package->price * $flutterwave->currency_rate, 2);

            $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
            $this->renewOrder($user, $tnx_id, 'Flutterwave', 1, $package, $current_package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
            return response()->json(['status' => 'success', 'message' => $notification]);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            return response()->json(['status' => 'faild', 'message' => $notification]);
        }
    }


    public function renewPaystackPayment(Request $request)
    {

        $paystack = PaystackAndMollie::first();

        $reference = $request->reference;
        $transaction = $request->tnx_id;
        $secret_key = $paystack->paystack_secret_key;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret_key",
                "Cache-Control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $final_data = json_decode($response);
        if ($final_data->status == true) {

            $currency = Setting::first();
            $package = Package::find($request->package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();

            $amount_usd = round($package->price * $paystack->paystack_currency_rate, 2);
            $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
            $this->renewOrder($user, $transaction, 'Paystack', 1, $package, $current_package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
            return response()->json(['status' => 'success', 'message' => $notification]);
        }
    }

    public function renewMolliePayment($id)
    {
        $mollie = PaystackAndMollie::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();
        $payableAmount = round($package->price * $mollie->mollie_currency_rate);

        $payableAmount = number_format($payableAmount, 2);
        $mollie_api_key = $mollie->mollie_key;
        $currency = strtoupper($mollie->mollie_currency_code);
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $currency,
                'value' => '' . $payableAmount . '',
            ],
            'description' => env('APP_NAME'),
            'redirectUrl' => route('user.renew.mollie-payment-success'),
        ]);


        $payment = Mollie::api()->payments()->get($payment->id);
        session()->put('payment_id', $payment->id);
        session()->put('package_id', $id);
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function renewMolliePaymentSuccess(Request $request)
    {

        $mollie = PaystackAndMollie::first();
        $mollie_api_key = $mollie->mollie_key;
        Mollie::api()->setApiKey($mollie_api_key);
        $payment = Mollie::api()->payments->get(session()->get('payment_id'));
        if ($payment->isPaid()) {
            $package_id = Session::get('package_id');
            $payment_id = Session::get('payment_id');
            $package = Package::find($package_id);
            $user = Auth::guard('web')->user();
            $setting = Setting::first();
            $currency = $setting;

            $amount_usd = round($package->price * $mollie->mollie_currency_rate, 2);

            $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
            $this->renewOrder($user, $payment_id, 'Mollie', 1, $package, $current_package, $amount_usd);

            $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('user.my-order')->with($notification);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('pricing.plan')->with($notification);
        }
    }


    public function renewPayWithInstamojo($id)
    {

        $instamojoPayment = InstamojoPayment::first();
        $package = Package::find($id);
        $user = Auth::guard('web')->user();
        $payableAmount = round($package->price * $instamojoPayment->currency_rate);
        $setting = Setting::first();
        $price = $payableAmount;

        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url . 'payment-requests/');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"
            )
        );
        $payload = array(
            'purpose' => env("APP_NAME"),
            'amount' => $price,
            'phone' => '918160651749',
            'buyer_name' => Auth::user()->name,
            'redirect_url' => route('user.renew.instamojo-response'),
            'send_email' => true,
            'webhook' => 'http://www.example.com/webhook/',
            'send_sms' => true,
            'email' => Auth::user()->email,
            'allow_repeated_payments' => false
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        session()->put('package_id', $id);
        return redirect($response->payment_request->longurl);
    }

    public function renewInstamojoResponse(Request $request)
    {

        $input = $request->all();

        $instamojoPayment = InstamojoPayment::first();
        $environment = $instamojoPayment->account_mode;
        $api_key = $instamojoPayment->api_key;
        $auth_token = $instamojoPayment->auth_token;

        if ($environment == 'Sandbox') {
            $url = 'https://test.instamojo.com/api/1.1/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . 'payments/' . $request->get('payment_id'));
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:$api_key",
                "X-Auth-Token:$auth_token"
            )
        );
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('pricing.plan')->with($notification);
        } else {
            $data = json_decode($response);
        }

        if ($data->success == true) {
            if ($data->payment->status == 'Credit') {
                $package_id = Session::get('package_id');
                $payment_id = Session::get('payment_id');
                $package = Package::find($package_id);
                $user = Auth::guard('web')->user();
                $setting = Setting::first();
                $currency = $setting;
                $instamojoPayment = InstamojoPayment::first();

                $amount_usd = round($package->price * $instamojoPayment->currency_rate, 2);
                $current_package = Order::where('user_id', $user->id)->where('status', 1)->first();
                $this->renewOrder($user, $request->payment_id, 'Instamojo', 1, $package, $current_package, $amount_usd);

                $notification = trans('user_validation.Congratulations! Your renew payment has been successfull');
                $notification = array('messege' => $notification, 'alert-type' => 'success');
                return redirect()->route('user.my-order')->with($notification);
            }
        }
    }

    public function renewOrder($user, $transaction, $payment_method, $payment_status, $package, $current_package, $amount_usd)
    {
        $setting = Setting::first();
        $current_expired_date  = $current_package->expired_date;
        if ($current_expired_date != null) {
            $next_due_date = date('Y-m-d', strtotime($current_expired_date . ' +' . $package->number_of_days . ' days'));
            $current_package = Order::find($current_package->id);
            $current_package->expired_date = $package->number_of_days == -1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = $payment_status;
            $current_package->transaction_id = $transaction;
            $current_package->payment_method = $payment_method;
            $current_package->save();
        } else {
            $current_expired_date = date('Y-m-d');
            $next_due_date = date('Y-m-d', strtotime($current_expired_date . ' +' . $package->number_of_days . ' days'));
            $current_package = Order::find($current_package->id);
            $current_package->expired_date = $package->number_of_days == -1 ? null : $next_due_date;
            $current_package->last_renew_date = date('Y-m-d');
            $current_package->renew_payment_status = $payment_status;
            $current_package->transaction_id = $transaction;
            $current_package->payment_method = $payment_method;
            $current_package->save();
        }

        MailHelper::setMailConfig();

        $order_details = 'Renew Date: ' . date('Y-m-d') . '<br>';
        $expired_date = $current_package->expired_date ? $current_package->expired_date : 'Lifetime';
        $order_details .= 'Expired Date: ' . $expired_date;

        // send email
        $template = EmailTemplate::where('id', 9)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}', $user->name, $message);
        $message = str_replace('{{payment_method}}', $payment_method, $message);
        $total_amount = $setting->currency_icon . $package->price;
        $message = str_replace('{{amount}}', $total_amount, $message);
        $message = str_replace('{{order_details}}', $order_details, $message);
        Mail::to($user->email)->send(new OrderSuccessfully($message, $subject));
    }
}
