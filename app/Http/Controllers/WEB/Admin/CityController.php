<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\CityTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\City;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;
use App\Models\User;
use App\Models\Property;

use App\Exports\CityExport;
use App\Imports\CityImport;
use App\Models\Language;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{
    use TranslationTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $cities = City::orderBy('id', 'desc')->get();
        $languages = Language::all();

        return view('admin.city')->with(['cities' => $cities, 'languages' => $languages]);
    }

    public function create()
    {
        return view('admin.create_city');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:cities',
            'status' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $city = new City();
        $city->country_state_id = 0;
        $city->name = $request->name;
        $city->slug = Str::slug($request->name);
        $city->status = $request->status;
        $city->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function show($id)
    {
        $city = City::find($id);
        return response()->json(['city' => $city], 200);
    }

    public function edit($id)
    {
        $city = City::find($id);
        return view('admin.edit_city', compact('city'));
    }


    public function update(Request $request, $id)
    {
        $city = City::find($id);
        $rules = [
            'name' => 'required|unique:cities,name,' . $city->id,
            'status' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $city->name = $request->name;
        $city->slug = Str::slug($request->name);
        $city->status = $request->status;
        $city->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function destroy($id)
    {
        $city = City::find($id);

        $is_property = Property::where('city_id', $id)->count();
        $is_user = User::where('city_id', $id)->count();
        if ($is_property > 0 || $is_user > 0) {
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more property, agent has been created under it');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $city->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function changeStatus($id)
    {
        $city = City::find($id);
        if ($city->status == 1) {
            $city->status = 0;
            $city->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $city->status = 1;
            $city->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }


    public function city_import_view()
    {
        return view('admin.city_import');
    }

    public function export()
    {
        return Excel::download(new CityExport, 'cities.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new CityImport, $request->file('file'));

        $notification = trans('admin_validation.Uploaded Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.city.index')->with($notification);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'CityTranslation',
                'City',
                'city_id',
                'name',
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.city.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation(
            $id,
            $code,
            'CityTranslation',
            'City',
            'city_id',
            'name',
        );

        return view('admin.edit_city_translation', [
            'city' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'CityTranslation',
                'City',
                'city_id',
                'name',
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.city.index')->with($notification);
        }

        $rules = [
            'name' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = CityTranslation::firstOrCreate([
            'language_code' => $code,
            'city_id' => $id
        ]);

        $translation->update([
            'name' => $request->name,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.city.index')->with($notification);
    }
}
