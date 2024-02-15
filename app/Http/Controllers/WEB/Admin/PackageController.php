<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Package;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PackageTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Setting;

class PackageController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function index()
    {
        $packages = Package::orderBy('package_order','asc')->get();
        $currency = Setting::first()->currency_icon;
        $languages = Language::all();

        return view('admin.package',compact('packages','currency', 'languages'));
    }

    public function create()
    {
        return view('admin.create_package');
    }


    public function store(Request $request)
    {
        $rules = [
            'package_type'=>'required',
            'package_name'=>'required',
            'price'=> $request->package_type==1 ? 'required' :'',
            'number_of_days'=>'required',
            'number_of_property'=>'required',
            'number_of_aminities'=>'required',
            'number_of_nearest_place'=>'required',
            'number_of_photo'=>'required',
            'package_order'=>'required',
            'feature'=>'required',
            'top_property'=>'required',
            'urgent'=>'required',
            'number_of_feature_property'=>$request->feature==1 ? 'required' :'',
            'number_of_top_property'=>$request->top_property==1 ? 'required' :'',
            'number_of_urgent_property'=>$request->urgent==1 ? 'required' :'',
            'status'=>'required',

        ];
        $customMessages = [
            'package_type.required' => trans('admin_validation.Package type is required'),
            'package_name.required' => trans('admin_validation.Package name is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'number_of_days.required' => trans('admin_validation.Number of days is required'),
            'number_of_property.required' => trans('admin_validation.Number of property is required'),
            'number_of_aminities.required' => trans('admin_validation.Number of aminity is required'),
            'number_of_nearest_place.required' => trans('admin_validation.Number of nearest place is required'),
            'number_of_photo.required' => trans('admin_validation.Number of image is required'),
            'package_order.required' => trans('admin_validation.Package order is required'),
            'number_of_feature_property.required' => trans('admin_validation.Number of featured property is required'),
            'number_of_top_property.required' => trans('admin_validation.Number of top property is required'),
            'number_of_urgent_property.required' => trans('admin_validation.Number of urgent property is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $package=new Package();
        $package->package_type=$request->package_type;
        $package->package_name=$request->package_name;
        $package->price=$request->price ? $request->price : 0;
        $package->number_of_days=$request->number_of_days;
        $package->number_of_property=$request->number_of_property;
        $package->number_of_aminities=$request->number_of_aminities;
        $package->number_of_nearest_place=$request->number_of_nearest_place;
        $package->number_of_photo=$request->number_of_photo;
        $package->is_featured=$request->feature;
        $package->is_top=$request->top_property;
        $package->is_urgent=$request->urgent;
        $package->number_of_feature_property=$request->number_of_feature_property ? $request->number_of_feature_property : 0;
        $package->number_of_top_property=$request->number_of_top_property ? $request->number_of_top_property : 0;
        $package->number_of_urgent_property=$request->number_of_urgent_property ? $request->number_of_urgent_property : 0;
        $package->status=$request->status;
        $package->package_order=$request->package_order;
        $package->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.package.index')->with($notification);
    }

    public function show($id){
        $package = Package::find($id);

        return response()->json(['package' => $package],200);
    }

    public function edit(Package $package)
    {
        return view('admin.edit_package')->with(['package' => $package]);
    }


    public function update(Request $request, Package $package)
    {

        $rules = [
            'package_type'=>'required',
            'package_name'=>'required',
            'price'=> $request->package_type==1 ? 'required' :'',
            'number_of_days'=>'required',
            'number_of_property'=>'required',
            'number_of_aminities'=>'required',
            'number_of_nearest_place'=>'required',
            'number_of_photo'=>'required',
            'package_order'=>'required',
            'feature'=>'required',
            'top_property'=>'required',
            'urgent'=>'required',
            'number_of_feature_property'=>$request->feature==1 ? 'required' :'',
            'number_of_top_property'=>$request->top_property==1 ? 'required' :'',
            'number_of_urgent_property'=>$request->urgent==1 ? 'required' :'',
            'status'=>'required',

        ];
        $customMessages = [
            'package_type.required' => trans('admin_validation.Package type is required'),
            'package_name.required' => trans('admin_validation.Package name is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'number_of_days.required' => trans('admin_validation.Number of days is required'),
            'number_of_property.required' => trans('admin_validation.Number of property is required'),
            'number_of_aminities.required' => trans('admin_validation.Number of aminity is required'),
            'number_of_nearest_place.required' => trans('admin_validation.Number of nearest place is required'),
            'number_of_photo.required' => trans('admin_validation.Number of image is required'),
            'package_order.required' => trans('admin_validation.Package order is required'),
            'number_of_feature_property.required' => trans('admin_validation.Number of featured property is required'),
            'number_of_top_property.required' => trans('admin_validation.Number of top property is required'),
            'number_of_urgent_property.required' => trans('admin_validation.Number of urgent property is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $package->package_type=$request->package_type;
        $package->package_name=$request->package_name;
        $package->price=$request->package_type ==1 ? $request->price : 0;
        $package->number_of_days=$request->number_of_days;
        $package->number_of_property=$request->number_of_property;
        $package->number_of_aminities=$request->number_of_aminities;
        $package->number_of_nearest_place=$request->number_of_nearest_place;
        $package->number_of_photo=$request->number_of_photo;
        $package->is_featured=$request->feature;
        $package->is_top=$request->top_property;
        $package->is_urgent=$request->urgent;
        $package->number_of_feature_property=$request->feature ? $request->number_of_feature_property : 0;
        $package->number_of_top_property=$request->top_property==1 ? $request->number_of_top_property : 0;
        $package->number_of_urgent_property=$request->urgent ? $request->number_of_urgent_property : 0;
        $package->status=$request->status;
        $package->package_order=$request->package_order;
        $package->save();


        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.package.index')->with($notification);
    }


    public function destroy(Package $package)
    {

        $is_order = Order::where('package_id', $package->id)->count();
        if($is_order > 0){
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more order has been created under it');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $package->delete();

        $notification= trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.package.index')->with($notification);
    }

    public function changeStatus($id){
        $package=Package::find($id);
        if($package->status==1){
            $package->status=0;
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $package->status=1;
            $message= trans('admin_validation.Active Successfully');
        }
        $package->save();
        return response()->json($message);

    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PackageTranslation', 'Package', 'package_id', 'package_name');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.package.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'PackageTranslation', 'Package', 'package_id', 'package_name');

        return view('admin.edit_package_translation', [
            'package' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PackageTranslation', 'Package', 'package_id', 'package_name');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.package.index')->with($notification);
        }

        $rules = [
            'package_name' => 'required',
        ];

        $customMessages = [
            'package_name.required' => trans('admin_validation.Package name is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = PackageTranslation::firstOrCreate([
            'language_code' => $code,
            'package_id' => $id
        ]);

        $translation->update([
            'package_name' => $request->package_name,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.package.index')->with($notification);
    }
}
