<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Mail;
use App\Mail\ContactMessageInformation;
use App\Rules\Captcha;
use App\Models\NotificationText;
use App\Models\ValidationText;
use App\Models\Admin;
use App\Models\User;

use App\Helpers\MailHelper;
use App\EmailConfiguration;
class ContactController extends Controller
{

    public function sendMessage(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'message'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'message.required' => trans('user_validation.Message is Required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $contact=[
            'email'=>$request->email,
            'phone'=>$request->phone,
            'name'=>$request->name,
            'subject'=>$request->subject,
            'message'=>$request->message,
        ];

        $setting=Setting::first();
        if($setting->enable_save_contact_message==1){
            $contact = new ContactMessage();
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->name = $request->name;
            $contact->message = $request->message;
            $contact->subject = $request->subject;
            $contact->save();
        }

        MailHelper::setMailConfig();

        $template=EmailTemplate::where('id',2)->first();
        $message=$template->description;
        $subject=$template->subject;
        $message=str_replace('{{name}}',$contact['name'],$message);
        $message=str_replace('{{email}}',$contact['email'],$message);
        $message=str_replace('{{phone}}',$contact['phone'],$message);
        $message=str_replace('{{subject}}',$contact['subject'],$message);
        $message=str_replace('{{message}}',$contact['message'],$message);


        Mail::to($setting->contact_email)->send(new ContactMessageInformation($message,$subject));


        $notification = trans('user_validation.Message send successfully');
        return response()->json(['message' => $notification]);
    }

    public function messageForUser(Request $request){

        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required',
            'message'=>'required',
            'user_type'=>'required',
            'user_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'message.required' => trans('user_validation.Message is Required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $contact=[
            'email'=>$request->email,
            'phone'=>$request->phone,
            'name'=>$request->name,
            'subject'=>$request->subject,
            'message'=>$request->message,
        ];


        MailHelper::setMailConfig();

        if($request->user_type==1){
            $admin=Admin::find($request->admin_id);
            if($admin){
                $template=EmailTemplate::where('id',2)->first();
                $message=$template->description;
                $subject=$template->subject;
                $message=str_replace('{{name}}',$contact['name'],$message);
                $message=str_replace('{{email}}',$contact['email'],$message);
                $message=str_replace('{{phone}}',$contact['phone'],$message);
                $message=str_replace('{{subject}}',$contact['subject'],$message);
                $message=str_replace('{{message}}',$contact['message'],$message);

                Mail::to($admin->email)->send(new ContactMessageInformation($message,$subject));

                $notification = trans('user_validation.Message send successfully');
                return response()->json(['success'=>$notification]);

            }else{
                $notification= trans('user_validation.Something Went Wrong');
                return response()->json(['error'=>$notification],403);
            }
        }else if($request->user_type==0 || $request->user_type==2){
            $user=User::find($request->user_id);
            if($user){
                $template=EmailTemplate::where('id',2)->first();
                $message=$template->description;
                $subject=$template->subject;
                $message=str_replace('{{name}}',$contact['name'],$message);
                $message=str_replace('{{email}}',$contact['email'],$message);
                $message=str_replace('{{phone}}',$contact['phone'],$message);
                $message=str_replace('{{subject}}',$contact['subject'],$message);
                $message=str_replace('{{message}}',$contact['message'],$message);

                Mail::to($user->email)->send(new ContactMessageInformation($message,$subject));

                $notification = trans('user_validation.Message send successfully');
                return response()->json(['success'=>$notification]);

            }else{
                $notification= trans('user_validation.Something Went Wrong');
                return response()->json(['error'=>$notification],403);
            }
        }else{
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['error'=>$notification],403);
        }
    }
}
