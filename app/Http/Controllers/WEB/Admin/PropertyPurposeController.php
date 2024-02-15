<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Models\PropertyPurpose;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PropertyPurposeTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;
class PropertyPurposeController extends Controller
{
    use TranslationTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $purposes = PropertyPurpose::all();
        $languages = Language::all();
        return view('admin.property_purpose')->with([
            'purposes' => $purposes,
            'languages' => $languages,
        ]);
    }
    public function update(Request $request, PropertyPurpose $propertyPurpose)
    {
        $rules = [
            'purpose'=>'required|unique:property_purposes,purpose,'.$propertyPurpose->id
        ];
        $customMessages = [
            'purpose.required' => trans('admin_validation.Purpose is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $propertyPurpose->custom_purpose=$request->purpose;
        $propertyPurpose->slug=Str::slug($request->purpose);
        $propertyPurpose->status=$request->status;
        $propertyPurpose->save();
        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
    // manage status
    public function changeStatus($id){
        $purpose=PropertyPurpose::find($id);
        if($purpose->status==1){
            $purpose->status=0;
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $purpose->status=1;
            $message= trans('admin_validation.Active Successfully');
        }
        $purpose->save();
        return response()->json($message);
    }
    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PropertyPurposeTranslation', 'PropertyPurpose', 'property_purpose_id', 'custom_purpose');
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            return redirect()->route('admin.property-purpose.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'PropertyPurposeTranslation', 'PropertyPurpose', 'property_purpose_id', 'custom_purpose');

        return view('admin.property_purpose_translation', [
            'propertyPurpose' => $translation
        ]);
    }
    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'PropertyPurposeTranslation', 'PropertyPurpose', 'property_purpose_id', 'custom_purpose');
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            return redirect()->route('admin.property-purpose.index')->with($notification);
        }
        $rules = [
            'custom_purpose' => 'required',
        ];
        $customMessages = [
            'custom_purpose.required' => trans('admin_validation.Type is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $translation = PropertyPurposeTranslation::firstOrCreate([
            'language_code' => $code,
            'property_purpose_id' => $id
        ]);
        $translation->update([
            'custom_purpose' => $request->custom_purpose,
        ]);
        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );
        return redirect()->route('admin.property-purpose.index')->with($notification);
    }
}
