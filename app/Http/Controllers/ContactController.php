<?php

namespace App\Http\Controllers;

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
use App\Mail\ContactPropertyMessage;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{


    public function sendMessage(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'message.required' => trans('user_validation.Message is Required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $contact = [
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        $setting = Setting::first();
        if ($setting->enable_save_contact_message == 1) {
            $contact = new ContactMessage();
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->name = $request->name;
            $contact->message = $request->message;
            $contact->subject = $request->subject;
            $contact->save();
        }

        MailHelper::setMailConfig();

        $template = EmailTemplate::where('id', 2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{name}}', $contact['name'], $message);
        $message = str_replace('{{email}}', $contact['email'], $message);
        $message = str_replace('{{phone}}', $contact['phone'], $message);
        $message = str_replace('{{subject}}', $contact['subject'], $message);
        $message = str_replace('{{message}}', $contact['message'], $message);

        Mail::to('contato@apartamentosbaratos.com.br')->send(new ContactMessageInformation($message, $subject));


        $notification = trans('user_validation.Message send successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function messageForUser(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'user_type' => 'required',
            'property_url' => 'required|url'
        ];

        $this->validate($request, $rules);

        $contact = [
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
            'property_url' => $request->property_url
        ];

        MailHelper::setMailConfig();


        $sendMail = function ($recipientEmail) use ($contact) {
            Mail::to($recipientEmail)->send(new ContactPropertyMessage($contact));
        };

        if ($request->user_type == 1) {
            $admin = Admin::find($request->admin_id);
            if ($admin) {
                $sendMail($admin->email);
                $notification = trans('user_validation.Message send successfully');
            }
        } elseif ($request->user_type == 0 || $request->user_type == 2) {
            $user = User::find($request->user_id);
            if ($user) {
                $sendMail($user->email);
                $notification = trans('user_validation.Message send successfully');
                // return back()->with($notification);

            }
        }

        $loginResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://cluster.apigratis.com/api/v2/login', [
            'email' => 'contato@apartamentosbaratos.com.br',
            'password' => 'APB@2024',
        ]);



        $resp = $loginResponse->json();
        $phone = $request->property_phone;

        if ($request->user_type == 1) {
            $admin = Admin::find($request->admin_id);
            $phone = $admin->phone;
        }

        $token = $resp['authorization']['token'];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'DeviceToken' => 'ba69dbe2-9b13-4191-874d-30d1bd903ea1',
            'Authorization' => 'Bearer ' . $token
        ])->post('https://gateway.apibrasil.io/api/v2/whatsapp/sendText', [
            'number' => $phone,
            'text' => "Chegou um novo LEAD, da APB para você!\n\n Nome: {$contact['name']},\n Email: {$contact['email']}, \nTelefone: {$contact['phone']},\n Imóvel de interesse: {$contact['property_url']}",
            'time_typing' => 1
        ]);




        $notification = trans('user_validation.Something Went Wrong');
        return response()->json(['error' => $notification]);
    }
}
