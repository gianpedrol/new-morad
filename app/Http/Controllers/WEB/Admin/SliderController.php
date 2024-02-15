<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\SliderTranslation;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;
class SliderController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $sliders = Slider::all();
        return view('admin.slider')->with([
            'sliders' => $sliders,
            'languages' => Language::all(),
        ]);
    }

    public function create(){
        return view('admin.create_slider');
    }

    public function store(Request $request){
        $rules = [
            'slider_image' => 'required',
            'title' => 'required',
            'status' => 'required',
            'serial' => 'required',
        ];
        $customMessages = [
            'slider_image.required' => trans('admin_validation.Slider image is required'),
            'title.required' => trans('admin_validation.Title is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $slider = new Slider();
        if($request->slider_image){
            $extention = $request->slider_image->getClientOriginalExtension();
            $slider_image = 'slider'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $slider_image = 'uploads/custom-images/'.$slider_image;
            Image::make($request->slider_image)
                ->save(public_path().'/'.$slider_image);
            $slider->image = $slider_image;
        }


        $slider->title = $request->title;
        $slider->serial = $request->serial;
        $slider->status = $request->status;
        $slider->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function show($id){
        $slider = Slider::find($id);
        return response()->json(['slider' => $slider], 200);
    }

    public function edit($id){
        $slider = Slider::find($id);
        return view('admin.edit_slider')->with([
            'slider' => $slider
        ]);
    }

    public function update(Request $request, $id){
        $rules = [
            'title' => 'required',
            'status' => 'required',
            'serial' => 'required',

        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title link is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'serial.required' => trans('admin_validation.Serial is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $slider = Slider::find($id);
        if($request->slider_image){
            $existing_slider = $slider->image;
            $extention = $request->slider_image->getClientOriginalExtension();
            $slider_image = 'slider'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $slider_image = 'uploads/custom-images/'.$slider_image;
            Image::make($request->slider_image)
                ->save(public_path().'/'.$slider_image);
            $slider->image = $slider_image;
            $slider->save();
            if($existing_slider){
                if(File::exists(public_path().'/'.$existing_slider))unlink(public_path().'/'.$existing_slider);
            }
        }

        $slider->title = $request->title;
        $slider->serial = $request->serial;
        $slider->status = $request->status;
        $slider->save();

        $notification= trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.slider.index')->with($notification);
    }

    public function destroy($id){
        $slider = Slider::find($id);
        $existing_slider = $slider->image;
        $slider->delete();
        if($existing_slider){
            if(File::exists(public_path().'/'.$existing_slider))unlink(public_path().'/'.$existing_slider);
        }

        $notification= trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function changeStatus($id){
        $slider = Slider::find($id);
        if($slider->status==1){
            $slider->status=0;
            $slider->save();
            $message= trans('admin_validation.Inactive Successfully');
        }else{
            $slider->status=1;
            $slider->save();
            $message= trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'SliderTranslation' , 'Slider', 'slider_id', 'title');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.slider.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'SliderTranslation' , 'Slider', 'slider_id', 'title');

        return view('admin.edit_slider_translation', [
            'slider' => $translation
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'SliderTranslation' , 'Slider', 'slider_id', 'title');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.slider.index')->with($notification);
        }

        $rules = [
            'title' => 'required',
        ];

        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = SliderTranslation::firstOrCreate([
            'language_code' => $code,
            'slider_id' => $id
        ]);

        $translation->update([
            'title' => $request->title,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.slider.index')->with($notification);
    }
}
