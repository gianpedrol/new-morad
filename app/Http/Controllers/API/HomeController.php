<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\HomeSection;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\AboutUs;
use App\Models\AboutSection;
use App\Models\Partner;
use App\Models\BlogCategory;
use App\Models\ContactUs;
use App\Models\Setting;
use App\Models\BlogComment;
use App\Rules\Captcha;
use App\ContactInformation;
use App\Models\Location;
use App\Models\Listing;
use App\Models\Day;
use App\Models\Aminity;
use App\Models\Wishlist;
use App\Models\ListingReview;
use App\Models\Subscriber;
use App\Models\TermsAndCondition;
use App\Models\EmailTemplate;
use App\Models\SeoSetting;
use App\Models\BannerImage;
use App\Models\PopularPost;
use App\Models\FacebookComment;
use App\Models\ContactPage;


use App\Models\CustomPage;
use Storage;
use Str;
use Mail;
use Session;
use App\Mail\SubscriptionVerification;
use Auth;
use App\Models\Order;
use App\Models\CustomPagination;
use App\Models\Admin;
use App\Models\User;
use App\Helpers\MailHelper;
use App\Models\Overview;
use App\Models\Service;
use App\Models\Faq;
use App\Models\Property;
use App\Models\Package;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\PropertyAminity;
use App\Models\PropertyReview;
use App\Models\PrivacyPolicy;
use App\Models\NotificationText;
use App\Models\ValidationText;
use App\Models\ManageText;
use App\Models\GoogleRecaptcha;
use App\Models\HomePage;
use App\Models\BreadcrumbImage;
use App\Models\Career;
use App\Models\CareerRequest;


use DB;
use Schema;
use File;
use Illuminate\Pagination\Paginator;


class HomeController extends Controller
{

    public function index(){
        $home_page = HomePage::first();

        $sliders =Slider::orderBy('id','asc')->where('status',1)->get();
        $about_visibility = false;
        if($home_page->about_visibility == 1){
            $about_visibility = true;
        }
        $aboutUs = AboutUs::select('image','about_us')->first();
        $overviews=Overview::where('status',1)->get();
        $about_us = (object) array(
            'about_visibility' => $about_visibility,
            'about_us' => $aboutUs,
            'overviews' => $overviews,
        );

        // start top
        $properties=Property::where('status',1);
        $top_visibility = false;
        if($home_page->top_visibility == 1){
            $top_visibility = true;
        }
        $top_properties = Property::where('status',1)->where('top_property', 1)->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'))->get()->take($home_page->top_property_item);

        $top_properties = (object) array(
            'top_visibility' => $top_visibility,
            'title' => $home_page->	top_property_title,
            'description' => $home_page->top_property_description,
            'top_properties' => $top_properties,
        );

        // end top

        // start featured

        $properties = Property::where('status',1);
        $featured_visibility = false;
        if($home_page->top_visibility == 1){
            $featured_visibility = true;
        }
        $featured_properties = Property::where('status',1)->where('is_featured', 1)->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'))->get()->take($home_page->featured_property_item);

        $featured_properties = (object) array(
            'featured_visibility' => $featured_visibility,
            'title' => $home_page->featured_property_title,
            'description' => $home_page->featured_property_description,
            'featured_properties' => $featured_properties,
        );

        // end featured


        // start urgent

        $properties = Property::where('status',1);
        $urgent_visibility = false;
        if($home_page->urgent_visibility == 1){
            $urgent_visibility = true;
        }
        $urgent_properties = Property::where('status',1)->where('urgent_property', 1)->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'))->get()->take($home_page->urgent_property_item);

        $urgent_properties = (object) array(
            'urgent_visibility' => $urgent_visibility,
            'title' => $home_page->urgent_property_title,
            'description' => $home_page->urgent_property_description,
            'urgent_properties' => $urgent_properties,
        );

        // end urgent

        // start service

        $service_visibility = false;
        if($home_page->service_visibility == 1){
            $service_visibility = true;
        }
        $services = Service::where('status',1)->get()->take($home_page->service_item);
        $services = (object) array(
            'service_visibility' => $service_visibility,
            'title' => $home_page->service_title,
            'description' => $home_page->service_description,
            'image' => $home_page->service_bg_image,
            'services' => $services,
        );

        // end service

        // start agent

        $agent_visibility = false;
        if($home_page->agent_visibility == 1){
            $agent_visibility = true;
        }

        $orders = Order::where(['status'=>1])->where('expired_date','>=', date('Y-m-d'))->orWhere('expired_date', null)->get();

        $agent_id = array();

        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $agents = User::where('status',1)->orderBy('id','desc')->whereIn('id', $agent_id)->get()->take($home_page->	agent_item);

        $agents = (object) array(
            'agent_visibility' => $agent_visibility,
            'title' => $home_page->agent_title,
            'description' => $home_page->agent_description,
            'agents' => $agents,
        );

        // end agent

        $blog_visibility = false;
        if($home_page->blog_visibility == 1){
            $blog_visibility = true;
        }
        $blogs = Blog::where(['status'=>1,'show_homepage'=>1])->get()->take($home_page->blog_item);
        $blogs = (object) array(
            'blog_visibility' => $blog_visibility,
            'title' => $home_page->blog_title,
            'description' => $home_page->blog_description,
            'blogs' => $blogs,
        );

        // start blog


        // start testimonial

        $testimonial_visibility = false;
        if($home_page->testimonial_visibility == 1){
            $testimonial_visibility = true;
        }
        $testimonials = Testimonial::where('status',1)->get()->take($home_page->testimonial_item);
        $testimonials = (object) array(
            'testimonial_visibility' => $testimonial_visibility,
            'title' => $home_page->testimonial_title,
            'description' => $home_page->testimonial_description,
            'testimonials' => $testimonials,
        );

        // end testimonial

        $default_profile_image = BannerImage::find(15)->image;

        $currency = Setting::first()->currency_icon;
        $seo_text = SeoSetting::find(1);
        $service_bg = BannerImage::find(23);
        $propertyTypes = PropertyType::where('status',1)->orderBy('type','asc')->get();
        $cities = City::where('status',1)->orderBy('name','asc')->get();

        $max_number_of_room = Property::where('status',1)->orderBy('number_of_room','desc')->first();
        if($max_number_of_room){
            $max_number_of_room = $max_number_of_room->number_of_room;
        }else{
            $max_number_of_room = 0;
        }


        $max_price = Property::where('status',1)->orderBy('price','desc')->first();
        $min_price = Property::where('status',1)->orderBy('price','asc')->first();
        if($min_price){
            $minimum_price = $min_price->price;
        }else{
            $minimum_price = 0;
        }

        if($max_price){
            $max_price = $max_price->price;
        }else{
            $max_price = 0;
        }


        $price_range = $max_price - $minimum_price;
        $mod_price = $price_range/10;

        return response()->json([
            'seo_text' => $seo_text,
            'sliders' => $sliders,
            'cities' => $cities,
            'propertyTypes' => $propertyTypes,
            'price_range' => $price_range,
            'mod_price' => $mod_price,
            'minimum_price' => $minimum_price,
            'max_number_of_room' => $max_number_of_room,


            'about_us' => $about_us,
            'top_properties' => $top_properties,
            'featured_properties' => $featured_properties,
            'urgent_properties' => $urgent_properties,
            'services' => $services,
            'agents' => $agents,
            'blogs' => $blogs,
            'testimonials' => $testimonials,
            'currency' => $currency,
            'default_profile_image' => $default_profile_image,
        ]);
    }

    public function career(){

        $banner_image = BreadcrumbImage::find(13);
        $seo_text = SeoSetting::find(10);

        $careers = Career::where('status',1)->orderBy('id','desc')->get();

        return response()->json([
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'careers' => $careers,
        ]);
    }

    public function show_career($slug){

        $banner_image = BreadcrumbImage::find(13);

        $career = Career::where('status',1)->where('slug', $slug)->first();

        return response()->json([
            'banner_image' => $banner_image,
            'career' => $career,
        ]);
    }


    public function store_career_application(Request $request){
        $rules = [
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'cv'=>'required',
            'career_id'=>'required',
            'description'=>'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'cv.required' => trans('user_validation.CV is required'),
            'description.required' => trans('user_validation.Description is required'),

        ];
        $this->validate($request, $rules,$customMessages);

        $career = new CareerRequest();
        $career->career_id = $request->career_id;
        $career->name = $request->name;
        $career->phone = $request->phone;
        $career->email = $request->email;
        $career->subject = $request->subject;
        $career->description = $request->description;
        if($request->cv){
            $extention = $request->cv->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;

            $destinationPath = public_path('uploads/custom-images/');
            $request->cv->move($destinationPath,$image_name);
            $career->cv = $image_name;
        }
        $career->save();

        $notification = trans('user_validation.Application successfully');
        return response()->json(['message' => $notification]);
    }




    public function aboutUs(){
        $about = AboutUs::first();
        $banner_image = BreadcrumbImage::find(1);
        $overviews = Overview::where('status',1)->get();
        $seo_text = SeoSetting::find(2);

        $partners = Partner::where('status',1)->get();
        $home_page = HomePage::first();
        $team_visibility = false;
        if($about->team_visibility == 1){
            $team_visibility = true;
        }

        $partners = (object) array(
            'partner_visibility' => $team_visibility,
            'title' => $about->team_title,
            'description' => $about->team_description,
            'partners' => $partners,
        );

        return response()->json([
            'about' => $about,
            'banner_image' => $banner_image,
            'overviews' => $overviews,
            'partners' => $partners,
            'seo_text' => $seo_text
        ]);
    }


    public function blog(Request $request){
        $banner_image = BreadcrumbImage::find(10);
        $paginator = CustomPagination::where('id',1)->first()->qty;
        $blogs = Blog::with('admin','category')->where('status',1)->orderBy('id','desc');
        if($request->category){
            $category = BlogCategory::where('slug', $request->category)->first();
            $blogs = $blogs->where('blog_category_id', $category->id);
        }

        if($request->search){
            $blogs = $blogs->where('title', 'LIKE', '%'. $request->search. "%")
                            ->orWhere('description','LIKE','%'.$request->search.'%');
        }

        $blogs = $blogs->paginate($paginator);
        $blogs = $blogs->appends($request->all());
        $seo_text = SeoSetting::find(6);

        $default_profile_image = BannerImage::find(15)->image;

        return response()->json([
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'blogs' => $blogs,
            'default_profile_image' => $default_profile_image,
        ]);
    }

    public function blogDetails($slug){

        $blog=Blog::with('admin','category')->where(['slug'=>$slug,'status'=>1])->first();
        if($blog){

            $blog->views +=1;
            $blog->save();

            $blogCategories = BlogCategory::where('status',1)->get();

            $banner_image = BreadcrumbImage::find(10);
            $default_profile_image = BannerImage::find(15)->image;
            $blogComments = BlogComment::where(['blog_id' => $blog->id, 'status' => 1])->paginate(10);

            $popular_array = array();
            $popularBlogs = PopularPost::where('status',1)->get()->take(5);

            foreach($popularBlogs as $popularBlog){
                $popular_array[] = $popularBlog->blog_id;
            }

            $popularBlogs = Blog::with('admin','category')->where('id','!=',$blog->id)->where('status', 1)->whereIn('id', $popular_array)->orderBy('id','desc')->get()->take(4);

            $blog_comment_setting = FacebookComment::first();
            $recaptcha_setting = GoogleRecaptcha::first();

            return response()->json([
                'blog' => $blog,
                'blogCategories' => $blogCategories,
                'popularBlogs' => $popularBlogs,
                'banner_image' => $banner_image,
                'default_profile_image' => $default_profile_image,
                'blogComments' => $blogComments,
                'blog_comment_setting' => $blog_comment_setting,
                'recaptcha_setting' => $recaptcha_setting,
            ]);
        }else{
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 403);
        }

    }




    public function blogComment(Request $request,$blogId){
        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'comment'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'comment.required' => trans('user_validation.Comment is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $comment=new BlogComment();
        $comment->blog_id=$blogId;
        $comment->name=$request->name;
        $comment->email=$request->email;
        $comment->comment=$request->comment;
        $comment->save();


        $notification = trans('Blog comment submited successfully');
        return response()->json(['message' => $notification]);
    }

    public function faq(){
        $faqs = Faq::where('status',1)->get();
        $banner_image = BreadcrumbImage::find(4)->image;

        return response()->json([
            'banner_image' => $banner_image,
            'faqs' => $faqs
        ]);

    }


    public function contactUs(){
        $contact= ContactPage::first();
        $recaptcha_setting = GoogleRecaptcha::first();
        $seo_text = SeoSetting::find(3);
        $banner_image = BreadcrumbImage::find(3)->image;

        return response()->json([
            'contact' => $contact,
            'recaptcha_setting' => $recaptcha_setting,
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
        ]);
    }


    public function termsCondition(){
        $terms_conditions = TermsAndCondition::select('terms_and_condition')->first();
        $banner_image = BreadcrumbImage::find(6)->image;

        return response()->json([
            'terms_conditions' => $terms_conditions,
            'banner_image' => $banner_image

        ]);
    }



    public function privacyPolicy(){
        $banner_image = BreadcrumbImage::find(7)->image;
        $privacy_policy = TermsAndCondition::select('privacy_policy')->first();

        return response()->json([
            'banner_image' => $banner_image,
            'privacy_policy' => $privacy_policy,
        ]);
    }



    // manage subsciber
    public function subscribeUs(Request $request){
        $rules = [
            'email'=>'required|email',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required')
        ];
        $this->validate($request, $rules, $customMessages);


        $isSubsriber=Subscriber::where('email',$request->email)->count();
        if($isSubsriber ==0){

            $subscribe= new Subscriber();
            $subscribe->email = $request->email;
            $subscribe->verified_token = Str::random(25);
            $subscribe->save();
            MailHelper::setMailConfig();

            $template = EmailTemplate::where('id',4)->first();
            $message = $template->description;
            $subject = $template->subject;
            Mail::to($subscribe->email)->send(new SubscriptionVerification($subscribe,$message,$subject));

            $notification= trans('user_validation.Varification link send to your mail, please verify it');
            return response()->json(['success'=>$notification],200);
        }else{

            $notification = trans('user_validation.Email already exist');
            return response()->json(['error'=>$notification]);
        }

    }

    public function subscriptionVerify($token){
        $subscribe=Subscriber::where('verified_token',$token)->first();
        if($subscribe){
            $subscribe->status=1;
            $subscribe->verified_token=null;
            $subscribe->save();

            $notification= trans('user_validation.Verification Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('home')->with($notification);
        }else{

            $notification= trans('user_validation.Invalid token');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('home')->with($notification);
        }
    }


    public function customPage($slug){
        $page = CustomPage::where('slug',$slug)->first();

        $banner_image = BreadcrumbImage::find(11)->image;

        return view('custom_page')->with([
            'page' => $page,
            'banner_image' => $banner_image,
        ]);
    }


    public function agent(Request $request){

        $banner_image = BreadcrumbImage::find(8)->image;
        $paginate_qty = CustomPagination::where('id',3)->first()->qty;

        // start agent

        $orders = Order::where(['status'=>1])->where('expired_date','>=', date('Y-m-d'))->orWhere('expired_date', null)->get();

        $agent_id = array();
        foreach($orders as $order){
            if(!in_array($order->user_id, $agent_id)){
                $agent_id[] = $order->user_id;
            }
        }

        $agents = User::where('status',1)->orderBy('id','desc')->whereIn('id', $agent_id);
        if($request->location){
            $city = City::where('slug', $request->location)->first();
            if($city){
                $agents = $agents->where('city_id', $city->id);
            }
        }
        $agents = $agents->paginate($paginate_qty);

        $default_profile_image = BannerImage::find(15)->image;
        $seo_text = SeoSetting::find(5);

        $cities = City::where('status',1)->orderBy('name','asc')->get();

        return response()->json([
            'banner_image' => $banner_image,
            'cities' => $cities,
            'agents' => $agents,
            'orders' => $orders,
            'default_profile_image' => $default_profile_image,
            'seo_text' => $seo_text
        ]);
    }

    public function agentDetails(Request $request){

        $user_type='';
        if(!$request->user_type){
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 403);
        }else{
            $user_type=$request->user_type;
        }


        if($user_type ==1 || $user_type ==2){
            if($request->user_name){
                if($user_type==1){

                    $paginate_qty = CustomPagination::where('id',2)->first()->qty;
                    $user=Admin::where(['status'=>1,'slug'=>$request->user_name])->first();
                    if(!$user){
                        $notification= trans('user_validation.Something Went Wrong');
                        $notification=array('messege'=>$notification,'alert-type'=>'error');
                        return redirect()->route('home')->with($notification);
                    }

                    $banner_image = BreadcrumbImage::find(8)->image;
                    $default_image=BannerImage::find(15)->image;
                    $currency=Setting::first()->currency_icon;
                    $recaptcha_setting = GoogleRecaptcha::first();

                    $properties=Property::where(['status'=>1,'admin_id'=>$user->id])->paginate($paginate_qty);
                    $popluarProperties=Property::where('status',1)->orderBy('views','desc')->get();
                    $properties=$properties->appends($request->all());

                    $default_profile_image=BannerImage::find(15)->image;

                    return response()->json([
                        'properties' => $properties,
                        'banner_image' => $banner_image,
                        'default_image' => $default_image,
                        'currency' => $currency,
                        'recaptcha_setting' => $recaptcha_setting,
                        'user' => $user,
                        'user_type' => $user_type,
                        'popluarProperties' => $popluarProperties,
                        'default_profile_image' => $default_profile_image,
                    ]);

                }else{
                    $user=User::where(['status'=>1,'slug'=>$request->user_name])->first();
                    if(!$user){
                        $notification= trans('user_validation.Something Went Wrong');
                        return response()->json(['message' => $notification], 403);
                    }

                    $paginate_qty = CustomPagination::where('id',2)->first()->qty;
                    $banner_image = BreadcrumbImage::find(8)->image;
                    $default_image=BannerImage::find(15)->image;
                    $currency=Setting::first()->currency_icon;
                    $recaptcha_setting = GoogleRecaptcha::first();

                    $properties=Property::where(['status'=>1,'user_id'=>$user->id])->paginate($paginate_qty);
                    $properties=$properties->appends($request->all());
                    $popluarProperties=Property::where('status',1)->orderBy('views','desc')->get();
                    $default_profile_image=BannerImage::find(15)->image;

                    return response()->json([
                        'properties' => $properties,
                        'banner_image' => $banner_image,
                        'default_image' => $default_image,
                        'currency' => $currency,
                        'recaptcha_setting' => $recaptcha_setting,
                        'user' => $user,
                        'user_type' => $user_type,
                        'popluarProperties' => $popluarProperties,
                        'default_profile_image' => $default_profile_image,
                    ]);
                }
            }else{
                $notification= trans('user_validation.Something Went Wrong');
                return response()->json(['message' => $notification], 403);
            }
        }else{
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 403);
        }
    }



    public function pricingPlan(){
        $packages = Package::where('status',1)->orderBy('package_order','asc')->get();
        $seo_text = SeoSetting::find(8);
        $banner_image = BreadcrumbImage::find(2)->image;

        $currency = Setting::first()->currency_icon;

        return response()->json([
            'packages' => $packages,
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'currency' => $currency,
        ]);
    }


    public function properties(Request $request){


        Paginator::useBootstrap();
        // cheack page type, page type means grid view or listing view
        $page_type='';
        if(!$request->page_type){
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['message' => $notification], 403);
        }else{
            if($request->page_type=='list_view'){
                $page_type=$request->page_type;
            }else if($request->page_type=='grid_view'){
                $page_type=$request->page_type;
            }else{
                $notification= trans('user_validation.Something Went Wrong');
                return response()->json(['message' => $notification], 403);
            }
        }
        // end page type


        $paginate_qty=CustomPagination::where('id',2)->first()->qty;

        if($request->sorting_id){
            $id=$request->sorting_id;
            if($id==1){
                $properties=Property::with('propertyType','propertyPurpose','city')->where('status',1)->orderBy('id','desc')->paginate($paginate_qty);
            }else if($id==2){
                $properties=Property::with('propertyType','propertyPurpose','city')->where('status',1)->orderBy('views','desc')->paginate($paginate_qty);
            }else if($id==3){
                $properties=Property::with('propertyType','propertyPurpose','city')->where(['is_featured'=>1,'status'=>1])->orderBy('id','desc')->paginate($paginate_qty);
            }else if($id==4){
                $properties=Property::with('propertyType','propertyPurpose','city')->where(['top_property'=>1,'status'=>1])->orderBy('id','desc')->paginate($paginate_qty);
            }else if($id==5){
                $properties=Property::with('propertyType','propertyPurpose','city')->where(['status'=>1])->orderBy('id','desc')->paginate($paginate_qty);
            }else if($id==6){
                $properties=Property::with('propertyType','propertyPurpose','city')->where(['urgent_property'=>1,'status'=>1])->orderBy('id','desc')->paginate($paginate_qty);
            }else if($id==7){
                $properties=Property::with('propertyType','propertyPurpose','city')->where(['status'=>1])->orderBy('id','asc')->paginate($paginate_qty);
            }
        }else{
            $properties=Property::with('propertyType','propertyPurpose','city')->where('status',1)->orderBy('id','desc')->paginate($paginate_qty);
        }

        $properties=$properties->appends($request->all());

        $banner_image = BreadcrumbImage::find(12)->image;
        $default_image=BannerImage::find(15)->image;
        $currency = Setting::first()->currency_icon;
        $seo_text=SeoSetting::find(9);
        $propertyTypes=PropertyType::where('status',1)->orderBy('type','asc')->get();
        $cities=City::where('status',1)->orderBy('name','asc')->get();
        $aminities=Aminity::where('status',1)->orderBy('aminity','asc')->get();

        $max_number_of_room = Property::where('status',1)->orderBy('number_of_room','desc')->first();
        if($max_number_of_room){
            $max_number_of_room = $max_number_of_room->number_of_room;
        }else{
            $max_number_of_room = 0;
        }


        $max_price = Property::where('status',1)->orderBy('price','desc')->first();
        $min_price = Property::where('status',1)->orderBy('price','asc')->first();
        if($min_price){
            $minimum_price = $min_price->price;
        }else{
            $minimum_price = 0;
        }

        if($max_price){
            $max_price = $max_price->price;
        }else{
            $max_price = 0;
        }

        $price_range = $max_price - $minimum_price;
        $mod_price = $price_range/10;

        return response()->json([
            'properties' => $properties,
            'banner_image' => $banner_image,
            'default_image' => $default_image,
            'currency' => $currency,
            'page_type' => $page_type,
            'seo_text' => $seo_text,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'aminities' => $aminities,
            'price_range' => $price_range,
            'mod_price' => $mod_price,
            'minimum_price' => $minimum_price,
            'max_number_of_room' => $max_number_of_room
        ]);
    }


      public function downloadListingFile($file){
        $filepath= public_path() . "/uploads/custom-images/".$file;
        return response()->download($filepath);
    }

    public function propertDetails($slug){
        $property=Property::with('admin','user')->where(['status'=>1,'slug'=>$slug])->first();
        if($property){

            $isExpired=false;
            if($property->expired_date==null){
                $isExpired=false;
            }else if($property->expired_date >= date('Y-m-d')){
                $isExpired=false;
            }else{
                $isExpired=true;
            }
            if($isExpired){
                $notification= trans('user_validation.Something Went Wrong');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }

            $property->views=$property->views +1;
            $property->save();
            $similarProperties=Property::where(['status'=>1,'property_type_id'=>$property->property_type_id])->where('id', '!=',$property->id)->get()->take(3);

            $banner_image = BreadcrumbImage::find(2)->image;
            $default_image=BannerImage::find(15)->image;
            $currency = Setting::first()->currency_icon;
            $recaptcha_setting = GoogleRecaptcha::first();
            $propertyReviews = PropertyReview::where(['property_id' => $property->id, 'status' => 1])->paginate(10);

            return response()->json([
                'banner_image' => $banner_image,
                'property' => $property,
                'default_image' => $default_image,
                'currency' => $currency,
                'recaptcha_setting' => $recaptcha_setting,
                'similarProperties' => $similarProperties,
                'propertyReviews' => $propertyReviews,
            ]);

        }else{
            $notification= trans('user_validation.Something Went Wrong');
            return response()->json(['message' => $notification],403);
        }
    }




    public function searchPropertyPage(Request $request){
        Paginator::useBootstrap();

        // check page type, page type means grid view or list view
        $page_type='';
        if(!$request->page_type){

            $notification= trans('user_validation.Something Went Wrong');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('home')->with($notification);
        }else{
            if($request->page_type=='list_view'){
                $page_type=$request->page_type;
            }else if($request->page_type=='grid_view'){
                $page_type=$request->page_type;
            }else{
                $notification= trans('user_validation.Something Went Wrong');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->route('home')->with($notification);
            }
        }
        // end page type

        // check aminity
        $sortArry=[];
        if($request->aminity){
            foreach($request->aminity as $amnty){
                array_push($sortArry,(int)$amnty);
            }
        }else{
            $aminities=Aminity::where('status',1)->get();
            foreach($aminities as $aminity){
                array_push($sortArry,(int)$aminity->id);
            }
        }
        // end aminity

        // soriting data
        $paginate_qty=CustomPagination::where('id',2)->first()->qty;
        // check order type
        $orderBy="desc";
        $orderByView=false;
        if($request->sorting_id){
            if($request->sorting_id==7){
                $orderBy="asc";
            }else if($request->sorting_id==1){
                $orderBy="desc";
            }else if($request->sorting_id==5){
                $orderBy="desc";
            }else if($request->sorting_id==2){
                $orderBy="asc";
                $orderByView=true;
            }
        }
        // end check order type
        // start query
        $propertyAminities=PropertyAminity::whereHas('property',function($query) use ($request){
            if($request->property_type != null){
                $query->where(['property_type_id'=>$request->property_type,'status'=>1]);
            }
            if($request->city_id != null){
                $query->where(['city_id'=>$request->city_id,'status'=>1]);
            }
            if($request->search != null){
                $query->where('title','LIKE','%'.$request->search.'%')->where('status',1);
            }

            if($request->purpose_type != null){
                $query->where(['property_purpose_id'=>$request->purpose_type,'status'=>1]);
            }

            if($request->sorting_id){
                if($request->sorting_id==3){
                    $query->where(['is_featured'=>1,'status'=>1]);
                }else if($request->sorting_id==6){
                    $query->where(['urgent_property'=>1,'status'=>1]);
                }elseif($request->sorting_id==4){
                    $query->where(['top_property'=>1,'status'=>1]);
                }
            }

            $query->where(['status'=>1]);
        })->whereIn('aminity_id',$sortArry)
        ->select('property_id')->groupBy('property_id')
        ->orderBy('id',$orderBy)
        ->get();


        $property_arr = array();

        foreach($propertyAminities as $propertyAminity){
            $property_arr[] = $propertyAminity->property_id;
        }

        $properties = Property::with('propertyType','propertyPurpose','city')->where('status',1)->whereIn('id', $property_arr)->where(function($query) {
            $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
        });


        if($request->price_range){
            $price_range = explode(':',$request->price_range);
            $min_price = $price_range[0];
            $max_price = $price_range[1];
            $properties = $properties->where('price', '<=', $max_price)->where('price', '>=', $min_price);
        }

        if($request->number_of_room){
            $properties = $properties->where('number_of_room', $request->number_of_room);
        }

        if($request->property_id){
            $properties = $properties->where('property_search_id', $request->property_id);
        }

        $orderBy="desc";
        $orderByView=false;
        if($request->sorting_id){
            if($request->sorting_id==7){
                $properties = $properties->orderBy('id','asc');
            }else if($request->sorting_id==1){
                $properties = $properties->orderBy('id','desc');
            }else if($request->sorting_id==2){
                $properties = $properties->orderBy('views','desc');
            }else if($request->sorting_id==3){
                $properties = $properties->where('is_featured', 1);
            }else if($request->sorting_id==6){
                $properties = $properties->where('urgent_property', 1);
            }else if($request->sorting_id==5){
                $properties = $properties->orderBy('id','desc');
            }else if($request->sorting_id==4){
                $properties = $properties->where('top_property', 1);
            }
        }else{
            $properties = $properties->orderBy('id','desc');
        }

       $properties = $properties->paginate($paginate_qty);

        $properties = $properties->appends($request->all());



        $aminities=Aminity::where('status',1)->orderBy('aminity','asc')->get();
        $banner_image = BreadcrumbImage::find(12)->image;
        $default_image=BannerImage::find(15)->image;
        $currency = Setting::first()->currency_icon;
        $seo_text=SeoSetting::find(9);
        $propertyTypes=PropertyType::where('status',1)->orderBy('type','asc')->get();
        $cities=City::where('status',1)->orderBy('name','asc')->get();

        $max_number_of_room = Property::where('status',1)->orderBy('number_of_room','desc')->first();
        $max_number_of_room = $max_number_of_room->number_of_room;

        $max_price = Property::where('status',1)->orderBy('price','desc')->first();
        $min_price = Property::where('status',1)->orderBy('price','asc')->first();
        $minimum_price = $min_price->price;
        $max_price = $max_price->price;

        $price_range = $max_price - $minimum_price;
        $mod_price = $price_range/10;


        return view('property')->with([
            'properties' => $properties,
            'aminities' => $aminities,
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'page_type' => $page_type,
            'currency' => $currency,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'price_range' => $price_range,
            'mod_price' => $mod_price,
            'minimum_price' => $minimum_price,
            'max_number_of_room' => $max_number_of_room,
        ]);
    }


}
