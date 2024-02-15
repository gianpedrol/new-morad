<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\AboutUsTranslation;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;
class AboutUsController extends Controller
{
    use TranslationTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $aboutUs = AboutUs::first();
        $languages = Language::all();
        return view('admin.about-us', compact('aboutUs', 'languages'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'about_us' => 'required',
            'service' => 'required',
            'history' => 'required',
            'team_title' => 'required',
            'team_description' => 'required',
            'team_visibility' => 'required',
        ];
        $customMessages = [
            'about_us.required' => trans('admin_validation.About us is required'),
            'service.required' => trans('admin_validation.Service is required'),
            'history.required' => trans('admin_validation.History is required'),
            'team_title.required' => trans('admin_validation.Team title is required'),
            'team_description.required' => trans('admin_validation.Team description is required'),
            'team_visibility.required' => trans('admin_validation.Team status is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $aboutUs = AboutUs::find($id);
        if ($request->image) {
            $exist_banner = $aboutUs->image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = 'about-us' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $banner_name = 'uploads/custom-images/' . $banner_name;
            Image::make($request->image)
                ->save(public_path() . '/' . $banner_name);
            $aboutUs->image = $banner_name;
            $aboutUs->save();
            if ($exist_banner) {
                if (File::exists(public_path() . '/' . $exist_banner)) unlink(public_path() . '/' . $exist_banner);
            }
        }
        $aboutUs->about_us = $request->about_us;
        $aboutUs->service = $request->service;
        $aboutUs->history = $request->history;
        $aboutUs->team_title = $request->team_title;
        $aboutUs->team_description = $request->team_description;
        $aboutUs->team_visibility = $request->team_visibility;
        $aboutUs->save();
        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'AboutUsTranslation',
                'AboutUs',
                'about_us_id',
                'about_us',
                'service',
                'team_title',
                'team_description',
                'history',
            );
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            return redirect()->route('admin.about-us.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation(
            $id,
            $code,
            'AboutUsTranslation',
            'AboutUs',
            'about_us_id',
            'team_title',
            'team_description',
        );

        return view('admin.about_us_translation', [
            'aboutUs' => $translation,
        ]);
    }
    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'AboutUsTranslation',
                'AboutUs',
                'about_us_id',
                'about_us',
                'service',
                'team_title',
                'team_description',
                'history',
            );
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            return redirect()->route('admin.about-us.index')->with($notification);
        }
        $rules = [
            'about_us' => 'required',
            'service' => 'required',
            'history' => 'required',
            'team_title' => 'required',
            'team_description' => 'required',
        ];
        $customMessages = [
            'about_us.required' => trans('admin_validation.About us is required'),
            'service.required' => trans('admin_validation.Service is required'),
            'history.required' => trans('admin_validation.History is required'),
            'team_title.required' => trans('admin_validation.Team title is required'),
            'team_description.required' => trans('admin_validation.Team description is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $translation = AboutUsTranslation::firstOrCreate([
            'language_code' => $code,
            'about_us_id' => $id
        ]);
        $translation->update([
            'about_us' => $request->about_us,
            'service' => $request->service,
            'team_title' => $request->team_title,
            'team_description' => $request->team_description,
            'history' => $request->history,
        ]);
        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );
        return redirect()->route('admin.about-us.index')->with($notification);
    }
}
