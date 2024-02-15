<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogTranslation;
use App\Models\Language;
use App\Models\PopularPost;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use  Image;
use File;
use Auth;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BlogController extends Controller
{
    use TranslationTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $blogs = Blog::with('category')->orderBy('id', 'desc')->get();
        $languages = Language::all();
        return view('admin.blog')->with([
            'blogs' => $blogs,
            'languages' => $languages,
        ]);
    }


    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();
        return view('admin.create_blog')->with([
            'categories' => $categories
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|unique:blogs',
            'slug' => 'required|unique:blogs',
            'image' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'category' => 'required',
            'status' => 'required',
            'show_homepage' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'image.required' => trans('admin_validation.Image is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'show_homepage.required' => trans('admin_validation.Show homepage is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $admin = Auth::guard('admin')->user();
        $blog = new Blog();
        if ($request->image) {
            $extention = $request->image->getClientOriginalExtension();
            $image_name = 'blog-' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;
            Image::make($request->image)
                ->save(public_path() . '/' . $image_name);
            $blog->image = $image_name;
        }

        $blog->admin_id = $admin->id;
        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->description = $request->description;
        $blog->short_description = $request->short_description;
        $blog->blog_category_id = $request->category;
        $blog->status = $request->status;
        $blog->show_homepage = $request->show_homepage;
        $blog->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $blog->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $blog->save();

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $categories = BlogCategory::where('status', 1)->get();
        $blog = Blog::find($id);
        return view('admin.edit_blog')->with([
            'categories' => $categories,
            'blog' => $blog,
        ]);
    }


    public function show($id)
    {
        $blog = Blog::with('category')->find($id);
        return response()->json(['blog' => $blog], 200);
    }


    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        $rules = [
            'title' => 'required|unique:blogs,title,' . $blog->id,
            'slug' => 'required|unique:blogs,slug,' . $blog->id,
            'description' => 'required',
            'short_description' => 'required',
            'category' => 'required',
            'status' => 'required',
            'show_homepage' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'description.required' => trans('admin_validation.Description is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'category.required' => trans('admin_validation.Category is required'),
            'show_homepage.required' => trans('admin_validation.Show homepage is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        if ($request->image) {
            $old_image = $blog->image;
            $extention = $request->image->getClientOriginalExtension();
            $image_name = 'blog-' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;
            Image::make($request->image)
                ->save(public_path() . '/' . $image_name);
            $blog->image = $image_name;
            $blog->save();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }

        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->description = $request->description;
        $blog->short_description = $request->short_description;
        $blog->blog_category_id = $request->category;
        $blog->status = $request->status;
        $blog->show_homepage = $request->show_homepage;
        $blog->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $blog->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        $blog->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.blog.index')->with($notification);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $old_image = $blog->image;
        $blog->delete();
        if ($old_image) {
            if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
        }

        BlogComment::where('blog_id', $id)->delete();
        PopularPost::where('blog_id', $id)->delete();

        $notification =  trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function changeStatus($id)
    {
        $blog = Blog::find($id);
        if ($blog->status == 1) {
            $blog->status = 0;
            $blog->save();
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $blog->status = 1;
            $blog->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function editTranslation($id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'BlogTranslation',
                'Blog',
                'blog_id',
                'title',
                'short_description',
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.blog.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation(
            $id,
            $code,
            'BlogTranslation',
            'Blog',
            'blog_id',
            'title',
            'short_description',
        );

        return view('admin.edit_blog_translation', [
            'blog' => $translation,
        ]);
    }

    public function updateTranslation(Request $request, $id, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'BlogTranslation',
                'Blog',
                'blog_id',
                'title',
                'short_description',
                'description'
            );

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.blog.index')->with($notification);
        }

        $rules = [
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
        ];

        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $translation = BlogTranslation::firstOrCreate([
            'language_code' => $code,
            'blog_id' => $id
        ]);

        $translation->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
        ]);

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.blog.index')->with($notification);
    }
}
