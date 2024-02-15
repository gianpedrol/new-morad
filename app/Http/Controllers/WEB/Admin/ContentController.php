<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintainanceText;
use App\Models\AnnouncementModal;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\ShopPage;
use App\Models\SeoSetting;
use Image;
use File;
class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function seoSetup(){
        $pages = SeoSetting::all();
        return view('admin.seo_setup', compact('pages'));
    }

    public function getSeoSetup($id){
        $page = SeoSetting::find($id);
        return response()->json(['page' => $page], 200);
    }

    public function updateSeoSetup(Request $request, $id){
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required'
        ];
        $customMessages = [
            'seo_title.required' => trans('admin_validation.Seo title is required'),
            'seo_description.required' => trans('admin_validation.Seo description is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();

        return view('admin.maintainance_mode')->with([
            'maintainance' => $maintainance
        ]);

    }

    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            'description'=> 'required',

        ];
        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $maintainance = MaintainanceText::first();
        if($request->image){
            $old_image=$maintainance->image;
            $image=$request->image;
            $ext=$image->getClientOriginalExtension();
            $image_name= 'maintainance-mode-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $maintainance->image=$image_name;
            $maintainance->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }
        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function headerPhoneNumber(){
        $header = Setting::select('topbar_phone','topbar_email')->first();

        return view('admin.header_phone_number')->with([
            'header' => $header
        ]);
    }

    public function updateHeaderPhoneNumber(Request $request){
        $rules = [
            'topbar_phone'=>'required',
            'topbar_email'=>'required',
        ];
        $customMessages = [
            'topbar_phone.required' => trans('admin_validation.Topbar phone is required'),
            'topbar_email.required' => trans('admin_validation.Topbar email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting->topbar_phone = $request->topbar_phone;
        $setting->topbar_email = $request->topbar_email;
        $setting->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function shopPage(){
        $shop_page = ShopPage::first();

        return view('admin.shop_page', compact('shop_page'));
    }

    public function updateFilterPrice(Request $request){
        $rules = [
            'filter_price_range' => 'required|numeric',
        ];
        $customMessages = [
            'filter_price_range.required' => trans('admin_validation.Filter price is required'),
            'filter_price_range.numeric' => trans('admin_validation.Filter price should be numeric number'),
        ];
        $this->validate($request, $rules,$customMessages);

        $shop_page = ShopPage::first();
        $shop_page->filter_price_range = $request->filter_price_range;
        $shop_page->save();
        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }



    public function productProgressbar(){
        $setting = Setting::select('show_product_progressbar')->first();
        return response()->json(['setting' => $setting], 200);
    }


    public function updateProductProgressbar(){
        $setting = Setting::first();
        if($setting->show_product_progressbar == 1){
            $setting->show_product_progressbar = 0;
            $setting->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $setting->show_product_progressbar = 1;
            $setting->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function defaultAvatar(){
        $defaultProfile = BannerImage::select('image')->whereId('15')->first();

        $default_image = $defaultProfile->image;

        return view('admin.default_profile_image')->with([
            'default_image' => $default_image
        ]);
    }

    public function updateDefaultAvatar(Request $request){
        $defaultProfile = BannerImage::whereId('15')->first();
        if($request->avatar){
            $existing_avatar = $defaultProfile->image;
            $extention = $request->avatar->getClientOriginalExtension();
            $default_avatar = 'default-avatar'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $default_avatar = 'uploads/website-images/'.$default_avatar;
            Image::make($request->avatar)
                ->save(public_path().'/'.$default_avatar);
            $defaultProfile->image = $default_avatar;
            $defaultProfile->save();
            if($existing_avatar){
                if(File::exists(public_path().'/'.$existing_avatar))unlink(public_path().'/'.$existing_avatar);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
