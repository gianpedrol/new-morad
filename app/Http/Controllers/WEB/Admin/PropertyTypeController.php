<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\PropertyType;
use App\Models\Property;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PropertyTypeTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class PropertyTypeController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {
        $propertyTypes = PropertyType::all();
        return view('admin.property_type')->with([
            'propertyTypes' => $propertyTypes,
            'languages' => Language::all(),
        ]);
    }


    public function create()
    {
        return view('admin.create_property_type');
    }


    public function store(Request $request)
    {
        $rules = [
            'type'=>'required|unique:property_types',
            'slug'=>'required|unique:property_types',
            'status'=>'required'
        ];
        $customMessages = [
            'type.required' => trans('admin_validation.Type is required'),
            'type.unique' => trans('admin_validation.Type already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Type already exist'),

        ];
        $this->validate($request, $rules, $customMessages);

        $type = new PropertyType();
        $type->type = $request->type;
        $type->slug = $request->slug;
        $type->status = $request->status;
        $type->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }




    public function edit(PropertyType $propertyType)
    {
        return view('admin.edit_property_type')->with([
            'propertyType' => $propertyType
        ]);
    }

    public function update(Request $request, PropertyType $propertyType)
    {
        $rules = [
            'type'=>'required|unique:property_types,type,'.$propertyType->id,
            'slug'=>'required|unique:property_types,slug,'.$propertyType->id,
            'status'=>'required'
        ];

        $customMessages = [
            'type.required' => trans('admin_validation.Type is required'),
            'type.unique' => trans('admin_validation.Type already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Type already exist'),

        ];

        $this->validate($request, $rules, $customMessages);

        $type = new PropertyType();
        $type->type = $request->type;
        $type->slug = $request->slug;
        $type->status = $request->status;
        $type->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.property-type.index')->with($notification);

    }


    public function destroy(PropertyType $propertyType)
    {

        $is_property = Property::where('property_type_id', $propertyType->id)->count();
        if($is_property > 0){
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more property has been created under it');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.property-type.index')->with($notification);
        }
        $propertyType->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.property-type.index')->with($notification);
    }

    public function changeStatus($id){
        $type=PropertyType::find($id);
        if($type->status==1){
            $type->status=0;
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $type->status=1;
            $message = trans('admin_validation.Active Successfully');
        }
        $type->save();
        return response()->json($message);

    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PropertyTypeTranslation', 'PropertyType', 'property_type_id', 'type');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.property-type.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'PropertyTypeTranslation', 'PropertyType', 'property_type_id', 'type');

        return view('admin.edit_property_type_translation', [
            'propertyType' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PropertyTypeTranslation', 'PropertyType', 'property_type_id', 'type');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.property-type.index')->with($notification);
        }

        $rules = [
            'type' => 'required',
        ];

        $customMessages = [
            'type.required' => trans('admin_validation.Type is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = PropertyTypeTranslation::firstOrCreate([
            'language_code' => $code,
            'property_type_id' => $id
        ]);

        $translation->update([
            'type' => $request->type,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.property-type.index')->with($notification);
    }
}
