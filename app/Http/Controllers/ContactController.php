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

class ContactController extends Controller
{

    public function sendMessage(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $contact = [
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
        ];

        $setting = Setting::first();
        if ($setting->enable_save_contact_message == 1) {
            $contact = new ContactMessage();
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->name = $request->name;
            $contact->save();
        }

        MailHelper::setMailConfig();

        $template = EmailTemplate::where('id', 2)->first();
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{name}}', $contact['name'], $message);
        $message = str_replace('{{email}}', $contact['email'], $message);
        $message = str_replace('{{phone}}', $contact['phone'], $message);

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

        $template = EmailTemplate::where('id', 2)->first();

        if ($request->user_type == 1) {
            $admin = Admin::find($request->admin_id);
            if ($admin) {
                Mail::to($admin->email)->send(new ContactMessageInformation($contact));
                $notification = trans('user_validation.Message send successfully');
                return response()->json(['success' => $notification]);
            }
        } elseif ($request->user_type == 0 || $request->user_type == 2) {
            $user = User::find($request->user_id);
            if ($user) {
                Mail::to($user->email)->send(new ContactMessageInformation($contact));
                $notification = trans('user_validation.Message send successfully');
                return response()->json(['success' => $notification]);
            }
        }

        $notification = trans('user_validation.Something Went Wrong');
        return response()->json(['error' => $notification]);
    }
}
