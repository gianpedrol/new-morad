<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Setting;
class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $contactMessages = ContactMessage::all();
        $message_setting = Setting::select('enable_save_contact_message','contact_email')->first();
        $message_setting = (object) array(
            'save_status' => $message_setting->enable_save_contact_message,
            'reciever_mail' => $message_setting->contact_email,
        );

        return view('admin.contact_message')->with([
            'contactMessages' => $contactMessages,
            'message_setting' => $message_setting
        ]);
    }

    public function show($id){
        $contactMessage = ContactMessage::find($id);

        return view('admin.show_contact_message')->with([
            'contactMessage' => $contactMessage
        ]);
    }



    public function destroy($id){
        $contactMessage = ContactMessage::find($id);
        $contactMessage->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function handleSaveContactMessage(Request $request){
        $rules = [
            'save_status' => 'required',
            'reciever_mail' => 'required',
        ];
        $customMessages = [
            'save_status.required' => trans('admin_validation.Status is required'),
            'reciever_mail.required' => trans('admin_validation.Email is required'),

        ];
        $this->validate($request, $rules,$customMessages);


        $setting = Setting::first();
        $setting->enable_save_contact_message = $request->save_status;
        $setting->contact_email = $request->reciever_mail;
        $setting->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
