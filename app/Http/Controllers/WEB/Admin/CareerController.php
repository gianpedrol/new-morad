<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\CareerRequest;
use Image;
use File;
use Str;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $careers = Career::orderBy('id','desc')->get();

        return view('admin.career')->with(['careers' => $careers]);
    }


    public function create()
    {
        return view('admin.create_career');
    }


    public function store(Request $request)
    {
        $rules = [
            'title'=>'required|unique:careers',
            'slug'=>'required|unique:careers',
            'image'=>'required',
            'salary_range'=>'required',
            'address'=>'required',
            'job_nature'=>'required',
            'office_time'=>'required',
            'deadline'=>'required',
            'description'=>'required',
            'status'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'image.required' => trans('admin_validation.Image is required'),
            'salary_range.required' => trans('admin_validation.Salary range is required'),
            'address.required' => trans('admin_validation.Location is required'),
            'job_nature.required' => trans('admin_validation.Job nature is required'),
            'office_time.required' => trans('admin_validation.Office time is required'),
            'deadline.required' => trans('admin_validation.Deadline is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $career = new Career();

        if($request->image){
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->title).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->image)
                ->save(public_path().'/'.$image_name);
            $career->image = $image_name;
        }

        $career->title = $request->title;
        $career->slug = $request->slug;
        $career->salary_range = $request->salary_range;
        $career->address = $request->address;
        $career->job_nature = $request->job_nature;
        $career->office_time = $request->office_time;
        $career->deadline = $request->deadline;
        $career->description = $request->description;
        $career->status = $request->status;
        $career->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.career.index')->with($notification);
    }

    public function edit($id)
    {
        $career = Career::find($id);
        return view('admin.edit_career')->with(['career' => $career]);
    }


    public function update(Request $request,$id)
    {
        $career = Career::find($id);

        $rules = [
            'title'=>'required|unique:careers,title,'.$career->id,
            'slug'=>'required|unique:careers,slug,'.$career->id,
            'salary_range'=>'required',
            'address'=>'required',
            'job_nature'=>'required',
            'office_time'=>'required',
            'deadline'=>'required',
            'description'=>'required',
            'status'=>'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'salary_range.required' => trans('admin_validation.Salary range is required'),
            'address.required' => trans('admin_validation.Location is required'),
            'job_nature.required' => trans('admin_validation.Job nature is required'),
            'office_time.required' => trans('admin_validation.Office time is required'),
            'deadline.required' => trans('admin_validation.Deadline is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->image){
            $old_image = $career->image;
            $extention = $request->image->getClientOriginalExtension();
            $image_name = Str::slug($request->title).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->image)
                ->save(public_path().'/'.$image_name);
            $career->image = $image_name;
            $career->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $career->title = $request->title;
        $career->slug = $request->slug;
        $career->salary_range = $request->salary_range;
        $career->address = $request->address;
        $career->job_nature = $request->job_nature;
        $career->office_time = $request->office_time;
        $career->deadline = $request->deadline;
        $career->description = $request->description;
        $career->status = $request->status;
        $career->save();


        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.career.index')->with($notification);
    }

    public function destroy($id){
        $career = Career::find($id);
        $old_image = $career->image;
        $career->delete();
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $career_requests = CareerRequest::where('career_id', $id)->get();
        foreach($career_requests as $career_request){
            $exist_cv = $career_request->cv;
            if($exist_cv){
                if(File::exists(public_path().'/'.$exist_cv))unlink(public_path().'/'.$exist_cv);
            }
            $career_request->delete();
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.career.index')->with($notification);

    }

    public function changeStatus($id){
        $career = Career::find($id);
        if($career->status==1){
            $career->status=0;
            $career->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $career->status=1;
            $career->save();
            $message= trans('admin_validation.Active Successfully');
        }

        return response()->json($message);
    }

    public function careerRequest($id){
        $career = Career::find($id);
        $CareerRequests = CareerRequest::where('career_id', $id)->get();
        return view('admin.career_request')->with([
            'CareerRequests' => $CareerRequests,
            'career' => $career,
        ]);
    }
}
