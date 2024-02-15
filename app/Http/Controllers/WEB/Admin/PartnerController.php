<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Partner;
use App\Models\PartnerTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PartnerController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $teams = Partner::all();
        $languages = Language::all();

        return view('admin.team')->with([
            'teams' => $teams,
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.create_team');
    }


    public function store(Request $request)
    {
        $rules = [
            'image' => 'required',
            'name' => 'required',
            'designation' => 'required',
        ];
        $customMessages = [
            'image.required' => trans('admin_validation.Image is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        // save image
        $image = $request->image;
        $extention = $image->getClientOriginalExtension();
        $name = 'our-team-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
        $image_path = 'uploads/custom-images/' . $name;
        Image::make($image)
            ->save(public_path() . '/' . $image_path);


        $partner = new Partner();
        $partner->image = $image_path;
        $partner->name = $request->name;
        $partner->designation = $request->designation;

        if ($request->first_icon && $request->first_link) {
            $partner->first_icon = $request->first_icon;
            $partner->first_link = $request->first_link;
        }

        if ($request->second_icon && $request->second_link) {
            $partner->second_icon = $request->second_icon;
            $partner->second_link = $request->second_link;
        }

        if ($request->third_icon && $request->third_link) {
            $partner->third_icon = $request->third_icon;
            $partner->third_link = $request->third_link;
        }

        if ($request->four_icon && $request->four_link) {
            $partner->four_icon = $request->four_icon;
            $partner->four_link = $request->four_link;
        }

        $partner->status = $request->status;
        $partner->save();


        $notification = trans('admin_validation.Create Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.our-team.index')->with($notification);
    }

    public function edit($id)
    {
        $team = Partner::find($id);

        return view('admin.edit_team')->with([
            'team' => $team
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'designation' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $partner = Partner::find($id);
        if ($request->image) {
            $old_image = $partner->image;
            $image = $request->image;
            $extention = $image->getClientOriginalExtension();
            $name = 'our-team-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_path = 'uploads/custom-images/' . $name;

            Image::make($image)
                ->save(public_path() . '/' . $image_path);

            $partner->image = $image_path;
            $partner->save();
            if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
        }

        $partner->name = $request->name;
        $partner->designation = $request->designation;
        $partner->first_icon = $request->first_icon;
        $partner->first_link = $request->first_link;
        $partner->second_icon = $request->second_icon;
        $partner->second_link = $request->second_link;
        $partner->third_icon = $request->third_icon;
        $partner->third_link = $request->third_link;
        $partner->four_icon = $request->four_icon;
        $partner->four_link = $request->four_link;
        $partner->status = $request->status;
        $partner->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');

        return redirect()->route('admin.our-team.index')->with($notification);
    }

    public function destroy($id)
    {

        $partner = Partner::find($id);
        $old_image = $partner->image;
        $partner->delete();

        if ($old_image) {
            if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
        }

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');

        return redirect()->route('admin.our-team.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $partner = Partner::find($id);
        if ($partner->status == 1) {
            $partner->status = 0;
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $partner->status = 1;
            $message = trans('admin_validation.Active Successfully');
        }
        $partner->save();
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PartnerTranslation', 'Partner', 'partner_id', 'name', 'designation');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.our-team.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'PartnerTranslation', 'Partner', 'partner_id', 'name', 'designation');

        return view('admin.edit_team_translation', [
            'team' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PartnerTranslation', 'Partner', 'partner_id', 'name', 'designation');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.our-team.index')->with($notification);
        }

        $rules = [
            'name' => 'required',
            'designation' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = PartnerTranslation::firstOrCreate([
            'language_code' => $code,
            'partner_id' => $id
        ]);

        $translation->update([
            'name' => $request->name,
            'designation' => $request->designation,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.our-team.index')->with($notification);
    }
}
