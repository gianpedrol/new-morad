<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\NearestLocation;
use App\Models\PropertyNearestLocation;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\NearestLocationTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;

class NearestLocationController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $locations = NearestLocation::orderBy('id','desc')->get();
        $languages = Language::all();
        return view('admin.nearest_location')->with(['locations' => $locations, 'languages' => $languages]);
    }

    public function create(){
        return view('admin.create_nearest_location');
    }

    public function show($id){
        $location = NearestLocation::find($id);
        return response()->json(['location' => $location],200);
    }

    public function edit($id){
        $location = NearestLocation::find($id);
        return view('admin.edit_nearest_location')->with(['location' => $location]);
    }

    public function store(Request $request)
    {
        $rules = [
            'location'=>'required|unique:nearest_locations',
            'status'=>'required'

        ];
        $customMessages = [
            'location.required' => trans('admin_validation.Location is required'),
            'location.unique' => trans('admin_validation.Location already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $location=new NearestLocation();
        $location->location=$request->location;
        $location->slug=Str::slug($request->location);
        $location->status=$request->status;
        $location->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }




    public function update(Request $request, NearestLocation $nearestLocation)
    {

        $rules = [
            'location'=>'required|unique:nearest_locations,location,'.$nearestLocation->id,
            'status'=>'required'
        ];

        $customMessages = [
            'location.required' => trans('admin_validation.Location is required'),
            'location.unique' => trans('admin_validation.Location already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $nearestLocation->location=$request->location;
        $nearestLocation->slug= $request->slug;
        $nearestLocation->status=$request->status;
        $nearestLocation->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.nearest-location.index')->with($notification);
    }


    public function destroy(NearestLocation $nearestLocation)
    {
        $is_property = PropertyNearestLocation::where('nearest_location_id', $nearestLocation->id)->count();
        if($is_property > 0){
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more property has been created under it');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $nearestLocation->delete();

        $notification=trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.nearest-location.index')->with($notification);
    }


    public function changeStatus($id){
        $location=NearestLocation::find($id);
        if($location->status==1){
            $location->status=0;
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $location->status=1;
            $message= trans('admin_validation.Active Successfully');
        }
        $location->save();
        return response()->json($message);

    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'NearestLocationTranslation', 'NearestLocation', 'nearest_location_id', 'location');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.nearest-location.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'NearestLocationTranslation', 'NearestLocation', 'nearest_location_id', 'location');

        return view('admin.edit_nearest_location_translation', [
            'location' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'NearestLocationTranslation', 'NearestLocation', 'nearest_location_id', 'location');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.nearest-location.index')->with($notification);
        }

        $rules = [
            'location' => 'required',
        ];

        $customMessages = [
            'location.required' => trans('admin_validation.Location is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = NearestLocationTranslation::firstOrCreate([
            'language_code' => $code,
            'nearest_location_id' => $id
        ]);

        $translation->update([
            'location' => $request->location,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.nearest-location.index')->with($notification);
    }
}
