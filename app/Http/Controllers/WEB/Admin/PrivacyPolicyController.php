<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\TermsAndCondition;
use App\Models\TermsAndConditionTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Image;
use File;
class PrivacyPolicyController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $privacyPolicy = TermsAndCondition::first();
        $languages = Language::all();
        return view('admin.privacy_policy')->with([
            'privacyPolicy' => $privacyPolicy,
            'languages' => $languages,
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'privacy_policy' => 'required',
        ];
        $customMessages = [
            'privacy_policy.required' => trans('admin_validation.Privacy policy is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $privacyPolicy = new TermsAndCondition();

        $privacyPolicy->privacy_policy = $request->privacy_policy;
        $privacyPolicy->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function update(Request $request, $id)
    {
        $privacyPolicy = TermsAndCondition::find($id);

        $rules = [
            'privacy_policy' => 'required',
        ];
        $customMessages = [
            'privacy_policy.required' => trans('admin_validation.Privacy policy is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $privacyPolicy->privacy_policy = $request->privacy_policy;
        $privacyPolicy->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'TermsAndConditionTranslation', 'TermsAndCondition', 'terms_and_condition_id', 'privacy_policy');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.privacy-policy.index')->with($notification);
        }

        $translation = TermsAndConditionTranslation::firstOrCreate([
            'terms_and_condition_id' => $id,
            'language_code' => $code
        ]);

        return view('admin.privacy_policy_translation', [
            'privacyPolicy' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'TermsAndConditionTranslation', 'TermsAndCondition', 'terms_and_condition_id', 'privacy_policy');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.privacy-policy.index')->with($notification);
        }

        $rules = [
            'privacy_policy' => 'required',
        ];

        $customMessages = [
            'privacy_policy.required' => trans('admin_validation.Privacy policy is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = TermsAndConditionTranslation::firstOrCreate([
            'language_code' => $code,
            'terms_and_condition_id' => $id
        ]);

        $translation->update([
            'privacy_policy' => $request->privacy_policy,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.privacy-policy.index')->with($notification);
    }
}
