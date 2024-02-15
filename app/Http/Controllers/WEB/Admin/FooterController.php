<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Footer;
use App\Models\FooterTranslation;
use App\Models\Language;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;
class FooterController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $footer = Footer::first();
        $languages = Language::all();
        return view('admin.website_footer')->with([
            'footer' => $footer,
            'languages' => $languages,
        ]);
    }

    public function update(Request $request, $id){
        $rules = [
            'email' =>'required',
            'phone' =>'required',
            'address' =>'required',
            'copyright' =>'required',
            'first_column' =>'required',
            'second_column' =>'required',
            'third_column' =>'required',
        ];
        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'phone.required' => trans('admin_validation.Phone is required'),
            'address.required' => trans('admin_validation.Address is required'),
            'copyright.required' => trans('admin_validation.Copyright is required'),
            'first_column.required' => trans('admin_validation.First column title is required'),
            'second_column.required' => trans('admin_validation.Second column title is required'),
            'third_column.required' => trans('admin_validation.Third column title is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $footer = Footer::first();
        $footer->email = $request->email;
        $footer->phone = $request->phone;
        $footer->address = $request->address;
        $footer->copyright = $request->copyright;
        $footer->first_column = $request->first_column;
        $footer->second_column = $request->second_column;
        $footer->third_column = $request->third_column;
        $footer->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'FooterTranslation', 'Footer', 'footer_id', 'address', 'first_column', 'second_column', 'third_column', 'copyright');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.footer.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'FooterTranslation', 'Footer', 'footer_id', 'address', 'first_column', 'second_column', 'third_column', 'copyright');

        return view('admin.website_footer_translation', [
            'footer' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'FooterTranslation', 'Footer', 'footer_id', 'address', 'first_column', 'second_column', 'third_column', 'copyright');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.footer.index')->with($notification);
        }

        $rules = [
            'address' => 'required',
            'copyright' => 'required',
            'first_column' => 'required',
            'second_column' => 'required',
            'third_column' => 'required',
        ];
        $customMessages = [
            'address.required' => trans('admin_validation.Address is required'),
            'copyright.required' => trans('admin_validation.Copyright is required'),
            'first_column.required' => trans('admin_validation.First column title is required'),
            'second_column.required' => trans('admin_validation.Second column title is required'),
            'third_column.required' => trans('admin_validation.Third column title is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = FooterTranslation::firstOrCreate([
            'language_code' => $code,
            'footer_id' => $id
        ]);

        $translation->update([
            'address' => $request->address,
            'first_column' => $request->first_column,
            'second_column' => $request->second_column,
            'third_column' => $request->third_column,
            'copyright' => $request->copyright,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.footer.index')->with($notification);
    }
}
