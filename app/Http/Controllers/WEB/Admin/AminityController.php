<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Aminity;
use App\Models\PropertyAminity;
use App\Http\Controllers\Controller;
use App\Models\AminityTranslation;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Str;
class AminityController extends Controller
{

    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $aminities = Aminity::orderBy('id','desc')->get();
        $languages = Language::orderBy('id','desc')->get();

        return view('admin.aminity')->with(['aminities' => $aminities, 'languages' => $languages]);
    }

    public function create(){
        return view('admin.create_aminity');
    }


    public function store(Request $request)
    {
        $rules = [
            'aminity'=>'required|unique:aminities',
            'status'=>'required'

        ];
        $customMessages = [
            'aminity.required' => trans('admin_validation.Aminity is required'),
            'aminity.unique' => trans('admin_validation.Aminity already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $aminity=new Aminity();
        $aminity->aminity=$request->aminity;
        $aminity->slug= $request->slug;
        $aminity->status=$request->status;
        $aminity->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function show($id){
        $aminity = Aminity::find($id);
        return response()->json(['aminity' => $aminity],200);
    }

    public function edit($id){
        $aminity = Aminity::find($id);
        return view('admin.edit_aminity')->with(['aminity' => $aminity]);
    }

    public function update(Request $request, Aminity $aminity)
    {
        $rules = [
            'aminity'=>'required|unique:aminities,aminity,'.$aminity->id,
            'status'=>'required'

        ];
        $customMessages = [
            'aminity.required' => trans('admin_validation.Aminity is required'),
            'aminity.unique' => trans('admin_validation.Aminity already exist'),
        ];
        $this->validate($request, $rules, $customMessages);

        $aminity->aminity=$request->aminity;
        $aminity->slug= $request->slug;
        $aminity->status=$request->status;
        $aminity->save();

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.aminity.index')->with($notification);
    }


    public function destroy(Aminity $aminity)
    {
        $is_property = PropertyAminity::where('aminity_id', $aminity->id)->count();
        if($is_property > 0){
            $notification = trans('admin_validation.You can not delete this item, Because there are one or more property has been created under it');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $aminity->delete();

        $notification=trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.aminity.index')->with($notification);
    }

    public function changeStatus($id){
        $aminity=Aminity::find($id);
        if($aminity->status==1){
            $aminity->status=0;
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $aminity->status=1;
            $message= trans('admin_validation.Active Successfully');
        }
        $aminity->save();
        return response()->json($message);

    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'AminityTranslation',
                'Aminity',
                'aminity_id',
                'aminity',
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.aminity.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation(
            $id,
            $code,
            'AminityTranslation',
            'Aminity',
            'aminity_id',
            'aminity',
        );

        return view('admin.edit_aminity_translation', [
            'aminity' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'AminityTranslation',
                'Aminity',
                'aminity_id',
                'aminity',
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.aminity.index')->with($notification);
        }

        $rules = [
            'aminity' => 'required',
        ];

        $customMessages = [
            'aminity.required' => trans('admin_validation.Aminity is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = AminityTranslation::firstOrCreate([
            'language_code' => $code,
            'aminity_id' => $id
        ]);

        $translation->update([
            'aminity' => $request->aminity,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.aminity.index')->with($notification);
    }
}
