<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Overview;
use App\Models\OverviewTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;

class OverviewController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $counters = Overview::all();
        $languages = Language::all();
        return view('admin.overview')->with([
            'counters' => $counters,
            'languages' => $languages
        ]);
    }

    public function create()
    {
        return view('admin.create_counter');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'qty' => 'required',
            'icon' => 'required',
            'status' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'qty.required' => trans('admin_validation.Quantity is required'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $counter = new Overview();
        $counter->name = $request->name;
        $counter->qty = $request->qty;
        $counter->icon = $request->icon;
        $counter->status = $request->status;
        $counter->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.counter.index')->with($notification);
    }

    public function show($id)
    {
        $counter = Overview::find($id);
        return response()->json(['counter' => $counter],200);
    }


    public function edit($id)
    {
        $counter = Overview::find($id);
        return view('admin.edit_counter')->with([
            'counter' => $counter
        ]);
    }


    public function update(Request $request, $id)
    {
        $counter = Overview::find($id);
        $rules = [
            'name' => 'required',
            'qty' => 'required',
            'icon' => 'required',
            'status' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'qty.required' => trans('admin_validation.Quantity is required'),
            'icon.required' => trans('admin_validation.Icon is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $counter->name = $request->name;
        $counter->qty = $request->qty;
        $counter->icon = $request->icon;
        $counter->status = $request->status;
        $counter->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.counter.index')->with($notification);
    }


    public function destroy($id)
    {
        $counter = Overview::find($id);
        $counter->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.counter.index')->with($notification);
    }

    public function changeStatus($id){
        $item = Overview::find($id);
        if($item->status == 1){
            $item->status = 0;
            $item->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $item->status = 1;
            $item->save();
            $message = trans('admin_validation.Active Successfully');
        }

        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'OverviewTranslation', 'Overview', 'overview_id', 'name');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.counter.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'OverviewTranslation', 'Overview', 'overview_id', 'name');

        return view('admin.edit_counter_translation', [
            'counter' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'OverviewTranslation', 'Overview', 'overview_id', 'name');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.counter.index')->with($notification);
        }

        $rules = [
            'name' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = OverviewTranslation::firstOrCreate([
            'language_code' => $code,
            'overview_id' => $id
        ]);

        $translation->update([
            'name' => $request->name,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.counter.index')->with($notification);
    }
}
