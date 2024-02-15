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
class TermsAndConditionController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $termsAndCondition = TermsAndCondition::first();
        $languages = Language::all();
        return view('admin.terms_and_condition')->with([
            'termsAndCondition' => $termsAndCondition,
            'languages' => $languages,
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'terms_and_condition' => 'required',
        ];
        $customMessages = [
            'terms_and_condition.required' => trans('admin_validation.Terms and condition is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $termsAndCondition = new TermsAndCondition();

        $termsAndCondition->terms_and_condition = $request->terms_and_condition;
        $termsAndCondition->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function update(Request $request, $id)
    {
        $termsAndCondition = TermsAndCondition::find($id);

        $rules = [
            'terms_and_condition' => 'required',
        ];
        $customMessages = [
            'terms_and_condition.required' => trans('admin_validation.Terms and condition is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $termsAndCondition->terms_and_condition = $request->terms_and_condition;
        $termsAndCondition->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'TermsAndConditionTranslation', 'TermsAndCondition', 'terms_and_condition_id', 'terms_and_condition');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.terms-and-condition.index')->with($notification);
        }

        $translation = TermsAndConditionTranslation::firstOrCreate([
            'terms_and_condition_id' => $id,
            'language_code' => $code
        ]);

        return view('admin.terms_and_condition_translation', [
            'termsAndCondition' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'TermsAndConditionTranslation', 'TermsAndCondition', 'terms_and_condition_id', 'terms_and_condition');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.terms-and-condition.index')->with($notification);
        }

        $rules = [
            'terms_and_condition' => 'required',
        ];

        $customMessages = [
            'terms_and_condition.required' => trans('admin_validation.Terms and condition is required')
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = TermsAndConditionTranslation::firstOrCreate([
            'language_code' => $code,
            'terms_and_condition_id' => $id
        ]);

        $translation->update([
            'terms_and_condition' => $request->terms_and_condition,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.terms-and-condition.index')->with($notification);
    }

}
