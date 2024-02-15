<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqTranslation;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FaqController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $faqs = Faq::all();
        $languages = Language::all();
        return view('admin.faq')->with([
            'faqs' => $faqs,
            'languages' => $languages,
        ]);
    }


    public function create()
    {
        return view('admin.create_faq');
    }

    public function store(Request $request)
    {
        $rules = [
            'question' => 'required|unique:faqs',
            'answer' => 'required',
            'status' => 'required',
        ];
        $customMessages = [
            'question.required' => trans('admin_validation.Question is required'),
            'question.unique' => trans('admin_validation.Question already exist'),
            'answer.required' => trans('admin_validation.Answer is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = $request->status;
        $faq->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function show($id)
    {
        $faq = Faq::find($id);
        return response()->json(['faq' => $faq], 200);
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('admin.edit_faq')->with([
            'faq' => $faq
        ]);
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::find($id);
        $rules = [
            'question' => 'required|unique:faqs,question,' . $faq->id,
            'answer' => 'required',
            'status' => 'required',
        ];
        $customMessages = [
            'question.required' => trans('admin_validation.Question is required'),
            'question.unique' => trans('admin_validation.Question already exist'),
            'answer.required' => trans('admin_validation.Answer is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = $request->status;
        $faq->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.faq.index')->with($notification);
    }

    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $faq = Faq::find($id);
        if ($faq->status == 1) {
            $faq->status = 0;
            $faq->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $faq->status = 1;
            $faq->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'FaqTranslation', 'Faq', 'faq_id', 'question', 'answer');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.faq.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'FaqTranslation', 'Faq', 'faq_id', 'question', 'answer');

        return view('admin.edit_faq_translation', [
            'faq' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'FaqTranslation', 'Faq', 'faq_id', 'question', 'answer');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.faq.index')->with($notification);
        }

        $rules = [
            'question' => 'required',
            'answer' => 'required',
        ];

        $customMessages = [
            'question.required' => trans('admin_validation.Question is required'),
            'answer.required' => trans('admin_validation.Answer is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = FaqTranslation::firstOrCreate([
            'language_code' => $code,
            'faq_id' => $id
        ]);

        $translation->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.faq.index')->with($notification);
    }
}
