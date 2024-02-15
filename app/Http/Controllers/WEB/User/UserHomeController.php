<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Image;
use File;
use App\Models\Setting;
use App\Models\ListingReview;
use App\Models\NotificationText;
use App\Models\ValidationText;
use App\Models\ManageText;
use App\Models\Navigation;
use App\Models\BannerImage;
use Hash;
use App\Models\ListingPackage;
use App\Models\ListingClaime;
use App\Models\PropertyReview;
use App\Models\Package;
use App\Models\City;
use Str;
use App\Rules\Captcha;
use Illuminate\Pagination\Paginator;
class UserHomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function clientReview(){
        Paginator::useBootstrap();
        $user=Auth::guard('web')->user();

        $clientReviews = PropertyReview::join('properties','properties.id','property_reviews.property_id')->join('users','users.id','property_reviews.user_id')
        ->where('properties.user_id',$user->id)->where('property_reviews.status',1)->select('property_reviews.*','properties.id as proper_id','properties.thumbnail_image','properties.slug','properties.title','users.image as user_image','users.name','users.slug as user_slug')->paginate(10);

        $agent_default_profile = BannerImage::find(15)->image;
        return view('user.client_review')->with([
            'clientReviews' => $clientReviews,
            'agent_default_profile' => $agent_default_profile,
        ]);
    }


    public function myReview(){
        Paginator::useBootstrap();
        $user=Auth::guard('web')->user();
        $myReviews = PropertyReview::where(['user_id'=>$user->id,'status'=>1])->orderBy('id','desc')->paginate(10);

        return view('user.my_review')->with([
            'myReviews' => $myReviews,
        ]);
    }

    public function editReview($id){
        $user=Auth::guard('web')->user();
        $review=PropertyReview::where(['user_id'=>$user->id,'id'=>$id])->first();
        if($review){
            return view('user.review_edit')->with([
                'review' => $review
            ]);
        }else{
            $notification = trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('user.my-review')->with($notification);
        }
    }

    public function deleteReview($id){
        // project demo mode check
        if(env('PROJECT_MODE')==0){
            $notification=array(
                'messege'=>env('NOTIFY_TEXT'),
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);
        }
        // end

        $user=Auth::guard('web')->user();
        $review=PropertyReview::where(['user_id'=>$user->id,'id'=>$id])->first();
        if($review){
            $review->delete();

            $notification = trans('user_validation.Delete Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('user.my-review')->with($notification);

        }else{

            $notification = trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('user.my-review')->with($notification);
        }
    }



    public function profile(){
        $user=Auth::guard('web')->user();
        $default_image = BannerImage::find(15)->image;
        $agent_default_banner = BannerImage::find(18)->image;
        $cities = City::where('status',1)->orderBy('name','asc')->get();

        return view('user.my_profile')->with([
            'user' => $user,
            'cities' => $cities,
            'default_image' => $default_image,
            'agent_default_banner' => $agent_default_banner
        ]);
    }


    public function updateProfileBanner(Request $request){
        $rules = [
            'banner_image'=>'required',
        ];
        $customMessages = [
            'banner_image.required' => trans('user_validation.Banner image is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user=Auth::guard('web')->user();

        if($request->file('banner_image')){
            $old_banner_image=$user->banner_image;
            $banner_image=$request->banner_image;
            $banner_ext=$banner_image->getClientOriginalExtension();
            $banner_name= 'profile-banner-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$banner_ext;
            $banner_path='uploads/custom-images/'.$banner_name;
            Image::make($banner_image)
                ->save(public_path().'/'.$banner_path);

            $user->banner_image=$banner_path;
            $user->save();
            if($old_banner_image){
                if(File::exists(public_path().'/'.$old_banner_image)) unlink(public_path().'/'.$old_banner_image);
            }

        }

        $notification = trans('user_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.my-profile')->with($notification);
    }

    public function updateProfile(Request $request){
        $user=Auth::guard('web')->user();
        $rules = [
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$user->id,
            'city_id'=>'required',
        ];
        $customMessages = [

            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'city_id.required' => trans('user_validation.City is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=Auth::guard('web')->user();

        // for profile image
        if($request->file('image')){
            $old_image=$user->image;
            $image=$request->image;
            $image_extention=$image->getClientOriginalExtension();
            $image_name= 'user-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$image_extention;
            $image_path='uploads/custom-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_path);

            $user->image=$image_path;
            $user->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))  unlink(public_path().'/'.$old_image);
            }

        }

        $user->name=$request->name;
        $user->slug=Str::slug($request->name);
        $user->phone=$request->phone;
        $user->city_id=$request->city_id;
        $user->about=$request->about;
        $user->icon_one=$request->icon_one;
        $user->link_one=$request->link_one;
        $user->icon_two=$request->icon_two;
        $user->link_two=$request->link_two;
        $user->icon_three=$request->icon_three;
        $user->link_three=$request->link_three;
        $user->icon_four=$request->icon_four;
        $user->link_four=$request->link_four;
        $user->address=$request->address;
        $user->website=$request->website;
        $user->save();


        $notification = trans('user_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.my-profile')->with($notification);
    }

    public function updatePassword(Request $request){

        $rules = [
            'current_password'=>'required',
            'password'=>'required|confirmed|min:4',
        ];
        $customMessages = [
            'current_password.required' => trans('user_validation.Current Password is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
            'password.min' => trans('user_validation.Password minimum 4 character')
        ];
        $this->validate($request, $rules, $customMessages);

        $user=Auth::guard('web')->user();
        if(Hash::check($request->current_password,$user->password)){
            $user->password=Hash::make($request->password);
            $user->save();

            $notification = trans('user_validation.Password Change Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');

            return redirect()->route('user.my-profile')->with($notification);
        }else{

            $notification = trans('user_validation.Old password does not match');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('user.my-profile')->with($notification);
        }


    }


    public function storeReview(Request $request){
        $rules = [
            'property_id'=>'required',
            'service_rating'=>'required',
            'location_rating'=>'required',
            'money_rating'=>'required',
            'clean_rating'=>'required',
            'avarage_rating'=>'required',
            'comment'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'comment.required' => trans('user_validation.Comment is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=Auth::guard('web')->user();

        $isExist=PropertyReview::where(['user_id'=>$user->id,'property_id'=>$request->property_id])->count();

        if($isExist==0){
            $review=new PropertyReview();
            $review->user_id=$user->id;
            $review->property_id=$request->property_id;
            $review->service_rating=$request->service_rating;
            $review->location_rating=$request->location_rating;
            $review->money_rating=$request->money_rating;
            $review->clean_rating=$request->clean_rating;
            $review->avarage_rating=$request->avarage_rating;
            $review->comment=$request->comment;
            $review->status=0;
            $review->save();

            $notification = trans('user_validation.Review has been submited');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);
        }{
            $notification = trans('user_validation.Review already submited');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }


    }

    public function updateReview(Request $request,$id){
        $rules = [
            'property_id'=>'required',
            'service_rating'=>'required',
            'location_rating'=>'required',
            'money_rating'=>'required',
            'clean_rating'=>'required',
            'avarage_rating'=>'required',
            'comment'=>'required'
        ];
        $customMessages = [
            'comment.required' => trans('user_validation.Comment is required'),
        ];
        $this->validate($request, $rules, $customMessages);


        $user=Auth::guard('web')->user();


        $review=PropertyReview::find($id);
        $review->service_rating=$request->service_rating;
        $review->location_rating=$request->location_rating;
        $review->money_rating=$request->money_rating;
        $review->clean_rating=$request->clean_rating;
        $review->avarage_rating=$request->avarage_rating;
        $review->comment=$request->comment;
        $review->save();

        $notification = trans('user_validation.Update Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('user.my-review')->with($notification);


    }


    public function pricingPlan(){
        $packages=Package::where('status',1)->orderBy('package_order','asc')->get();
        $currency=Setting::first()->currency_icon;
        return view('user.package')->with([
            'packages' => $packages,
            'currency' => $currency
        ]);
    }

}
