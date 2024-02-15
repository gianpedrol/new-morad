<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\TestimonialTranslation;
use Image;
use File;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Str;
use Cache;
class TestimonialController extends Controller
{
    use TranslationTrait;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $testimonials = Testimonial::all();
        $languages = Language::all();
        return view('admin.testimonial')->with([
            'testimonials' => $testimonials,
            'languages' => $languages,
        ]);
    }

    public function create()
    {
        return view('admin.create_testimonial');
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'image' => 'required',
            'status' => 'required',
            'comment' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
            'image.required' => trans('admin_validation.Image is required'),
            'comment.required' => trans('admin_validation.Comment is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $testimonial = new Testimonial();

        if($request->image){
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Ymdhis').'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;

            Image::make($request->image)
                ->save(public_path().'/'.$image_name);

        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->image = $image_name;
        $testimonial->comment = $request->comment;
        $testimonial->status = $request->status;
        $testimonial->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.testimonial.index')->with($notification);
    }

    public function show($id)
    {
        $testimonial = Testimonial::find($id);
        return response()->json(['testimonial' => $testimonial],200);
    }


    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        return view('admin.edit_testimonial')->with([
            'testimonial' => $testimonial
        ]);
    }


    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::find($id);
        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'status' => 'required',
            'comment' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
            'comment.required' => trans('admin_validation.Comment is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->image){
            $existing_image = $testimonial->image;
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Ymdhis').'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->image)
                    ->save(public_path().'/'.$image_name);
                $testimonial->image= $image_name;
                $testimonial->save();
                if($existing_image){
                    if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
                }
        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->comment = $request->comment;
        $testimonial->status = $request->status;
        $testimonial->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.testimonial.index')->with($notification);
    }


    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);
        $existing_image = $testimonial->image;
        $testimonial->delete();

        if($existing_image){
            if(File::exists(public_path().'/'.$existing_image))unlink(public_path().'/'.$existing_image);
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.testimonial.index')->with($notification);
    }

    public function changeStatus($id){
        $item = Testimonial::find($id);
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
            $this->updateDefaultTranslation($id, $code, 'TestimonialTranslation', 'Testimonial', 'testimonial_id', 'name', 'designation', 'comment');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.testimonial.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation($id, $code, 'TestimonialTranslation', 'Testimonial', 'testimonial_id', 'name', 'designation', 'comment');

        return view('admin.edit_testimonial_translation', [
            'testimonial' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($id, $code, 'TestimonialTranslation', 'Testimonial', 'testimonial_id', 'name', 'designation', 'comment');

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.testimonial.index')->with($notification);
        }

        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'comment' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'designation.required' => trans('admin_validation.Designation is required'),
            'comment.required' => trans('admin_validation.Comment is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = TestimonialTranslation::firstOrCreate([
            'language_code' => $code,
            'testimonial_id' => $id
        ]);

        $translation->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'comment' => $request->comment,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.testimonial.index')->with($notification);
    }
}
