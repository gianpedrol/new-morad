<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ServiceController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $services = Service::all();
        $languages = Language::all();

        return view('admin.service', [
            'services' => $services,
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.create_service');
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|unique:services',
            'icon' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $service = new Service();
        $service->title = $request->title;
        $service->icon = $request->icon;
        $service->description = $request->description;
        $service->status = $request->status;
        $service->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.service.index')->with($notification);
    }



    public function show($id)
    {
        $service = Service::find($id);
        return response()->json(['service' => $service],200);
    }

    public function edit($id)
    {
        $service = Service::find($id);
        return view('admin.edit_service')->with([
            'service' => $service
        ],200);
    }


    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        $rules = [
            'title' => 'required|unique:services,title,'.$service->id,
            'icon' => 'required',
            'description' => 'required',
            'status' => 'required'
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'icon.required' => trans('admin_validation.Icon is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $service->title = $request->title;
        $service->icon = $request->icon;
        $service->description = $request->description;
        $service->status = $request->status;
        $service->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.service.index')->with($notification);
    }


    public function destroy($id)
    {
        $service = Service::find($id);
        $service->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.service.index')->with($notification);
    }

    public function changeStatus($id){
        $service = Service::find($id);
        if($service->status == 1){
            $service->status = 0;
            $service->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $service->status = 1;
            $service->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'ServiceTranslation' , 'Service', 'service_id', 'title', 'description');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.service.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'ServiceTranslation' , 'Service', 'service_id', 'title', 'description');

        return view('admin.edit_service_translation', [
            'service' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'ServiceTranslation', 'Service', 'service_id', 'title', 'description');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.service.index')->with($notification);
        }

        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];

        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = ServiceTranslation::firstOrCreate([
            'language_code' => $code,
            'service_id' => $id
        ]);

        $translation->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.service.index')->with($notification);
    }
}
