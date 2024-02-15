<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use App\Models\CustomPageTranslation;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;
class CustomPageController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $customPages = CustomPage::all();
        $languages = Language::all();
        return view('admin.custom_page')->with([
            'customPages' => $customPages,
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.create_custom_page');
    }


    public function store(Request $request)
    {
        $rules = [
            'description' => 'required',
            'page_name' => 'required|unique:custom_pages',
            'slug' => 'required|unique:custom_pages',
            'status' => 'required'
        ];
        $customMessages = [
            'page_name.required' => trans('admin_validation.Page name is required'),
            'page_name.unique' => trans('admin_validation.Page name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $customPage = new CustomPage();
        $customPage->page_name = $request->page_name;
        $customPage->slug = $request->slug;
        $customPage->description = $request->description;
        $customPage->status = $request->status;
        $customPage->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function show($id)
    {
        $customPage = CustomPage::find($id);
        return response()->json(['customPage' => $customPage]);
    }

    public function edit($id)
    {
        $customPage = CustomPage::find($id);
        return view('admin.edit_custom_page')->with([
            'customPage' => $customPage
        ]);
    }



    public function update(Request $request, $id)
    {
        $customPage = CustomPage::find($id);
        $rules = [
            'description' => 'required',
            'page_name' => 'required|unique:custom_pages,page_name,'.$customPage->id,
            'slug' => 'required|unique:custom_pages,page_name,'.$customPage->id,
            'status' => 'required'
        ];
        $customMessages = [
            'page_name.required' => trans('admin_validation.Page name is required'),
            'page_name.unique' => trans('admin_validation.Page name already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'description.required' => trans('admin_validation.Description is required'),

        ];
        $this->validate($request, $rules,$customMessages);

        $customPage->page_name = $request->page_name;
        $customPage->slug = $request->slug;
        $customPage->description = $request->description;
        $customPage->status = $request->status;
        $customPage->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.custom-page.index')->with($notification);
    }

    public function destroy($id)
    {
        $customPage = CustomPage::find($id);
        $customPage->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function changeStatus($id){
        $customPage = CustomPage::find($id);
        if($customPage->status == 1){
            $customPage->status = 0;
            $customPage->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $customPage->status = 1;
            $customPage->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'CustomPageTranslation', 'CustomPage', 'custom_page_id', 'page_name', 'description');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.custom-page.index')->with($notification);
        }

        $customPage = CustomPage::find($id);
        try {
            $tr = new GoogleTranslate($code);
            $translatedTitle = $tr->translate($customPage->page_name);
        } catch (\Throwable $th) {
            $translatedTitle = null;
        }

        $translation = CustomPageTranslation::firstOrCreate([
            'custom_page_id' => $id,
            'language_code' => $code
        ]);

        if (!$translation->title && $translatedTitle) {
            $translation->page_name = $translatedTitle;
            $translation->save();
        }

        return view('admin.edit_custom_page_translation', [
            'customPage' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'CustomPageTranslation', 'CustomPage', 'custom_page_id', 'page_name', 'description');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.custom-page.index')->with($notification);
        }

        $rules = [
            'page_name' => 'required',
            'description' => 'required',
        ];

        $customMessages = [
            'page_name.required' => trans('admin_validation.Page name is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = CustomPageTranslation::firstOrCreate([
            'language_code' => $code,
            'custom_page_id' => $id
        ]);

        $translation->update([
            'page_name' => $request->page_name,
            'description' => $request->description,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.custom-page.index')->with($notification);
    }
}
