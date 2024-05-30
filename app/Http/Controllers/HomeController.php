<?php

namespace App\Http\Controllers;

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
use App\Models\Language;
use DB;
use Schema;
use File;
use Illuminate\Pagination\Paginator;


class HomeController extends Controller
{

    public function index()
    {
        $home_page = HomePage::with('translation')->first();

        $sliders = Slider::orderBy('id', 'asc')->where('status', 1)->get();
        $about_visibility = false;
        if ($home_page->about_visibility == 1) {
            $about_visibility = true;
        }
        $aboutUs = AboutUs::with('translation')->select('image', 'about_us')->first();
        $overviews = Overview::with('translation')->where('status', 1)->get();
        $about_us = (object) array(
            'about_visibility' => $about_visibility,
            'about_us' => $aboutUs,
            'overviews' => $overviews,
        );

        // start top
        $properties = Property::with('translation')->where('status', 1);
        $top_visibility = false;
        if ($home_page->top_visibility == 1) {
            $top_visibility = true;
        }

        $top_properties = Property::with('translation')
            ->where('status', 1)

            ->inRandomOrder()
            ->take(6)
            ->get();

        $top_properties = (object) array(
            'top_visibility' => $top_visibility,
            'title' => $home_page->translated_top_property_title,
            'description' => $home_page->translated_top_property_description,
            'top_properties' => $top_properties,
        );

        // end top

        // start featured

        $properties = Property::with('translation')->where('status', 1);
        $featured_visibility = false;
        if ($home_page->top_visibility == 1) {
            $featured_visibility = true;
        }
        $featured_properties = Property::with('translation')
            ->where('status', 1)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $featured_properties = (object) array(
            'featured_visibility' => $featured_visibility,
            'title' => $home_page->translated_featured_property_title,
            'description' => $home_page->translated_featured_property_description,
            'featured_properties' => $featured_properties,
        );

        // end featured


        // start urgent

        $properties = Property::with('translation')->where('status', 1);
        $urgent_visibility = false;
        if ($home_page->urgent_visibility == 1) {
            $urgent_visibility = true;
        }
        $urgent_properties = Property::with('translation')->where('status', 1)
            ->inRandomOrder()
            ->where('number_of_bedroom', 1)
            ->take(6)
            ->get();

        $urgent_properties = (object) array(
            'urgent_visibility' => $urgent_visibility,
            'title' => $home_page->translated_urgent_property_title,
            'description' => $home_page->translated_urgent_property_description,
            'urgent_properties' => $urgent_properties,
        );

        // end urgent

        // start service

        $service_visibility = false;
        if ($home_page->service_visibility == 1) {
            $service_visibility = true;
        }
        $services = Service::with('translation')->where('status', 1)->get()->take($home_page->service_item);
        $services = (object) array(
            'service_visibility' => $service_visibility,
            'title' => $home_page->translated_service_title,
            'description' => $home_page->translated_service_description,
            'image' => $home_page->service_bg_image,
            'services' => $services,
        );

        // end service

        // start agent

        $agent_visibility = false;
        if ($home_page->agent_visibility == 1) {
            $agent_visibility = true;
        }

        $orders = Order::where(['status' => 1])->where('expired_date', '>=', date('Y-m-d'))->orWhere('expired_date', null)->get();

        $agent_id = array();

        foreach ($orders as $order) {
            if (!in_array($order->user_id, $agent_id)) {
                $agent_id[] = $order->user_id;
            }
        }

        $agents = User::where('status', 1)->orderBy('id', 'desc')->whereIn('id', $agent_id)->get()->take($home_page->agent_item);

        $agents = (object) array(
            'agent_visibility' => $agent_visibility,
            'title' => $home_page->translated_agent_title,
            'description' => $home_page->translated_agent_description,
            'agents' => $agents,
        );

        // end agent

        $blog_visibility = false;
        if ($home_page->blog_visibility == 1) {
            $blog_visibility = true;
        }
        $blogs = Blog::with('translation')->where(['status' => 1, 'show_homepage' => 1])->get()->take($home_page->blog_item);
        $blogs = (object) array(
            'blog_visibility' => $blog_visibility,
            'title' => $home_page->translated_blog_title,
            'description' => $home_page->translated_blog_description,
            'blogs' => $blogs,
        );

        // start blog


        // start testimonial

        $testimonial_visibility = false;
        if ($home_page->testimonial_visibility == 1) {
            $testimonial_visibility = true;
        }
        $testimonials = Testimonial::with('translation')->where('status', 1)->get()->take($home_page->testimonial_item);
        $testimonials = (object) array(
            'testimonial_visibility' => $testimonial_visibility,
            'title' => $home_page->translated_testimonial_title,
            'description' => $home_page->translated_testimonial_description,
            'testimonials' => $testimonials,
        );

        // end testimonial

        $default_profile_image = BannerImage::find(15)->image;

        $currency = Setting::first()->currency_icon;
        $seo_text = SeoSetting::find(1);
        $service_bg = BannerImage::find(23);
        $propertyTypes = PropertyType::with('translation')->where('status', 1)->orderBy('type', 'asc')->get();
        $cities = City::where('status', 1)->orderBy('name', 'asc')->get();

        $max_number_of_room = Property::with('translation')->where('status', 1)->orderBy('number_of_room', 'desc')->first();
        if ($max_number_of_room) {
            $max_number_of_room = $max_number_of_room->number_of_room;
        } else {
            $max_number_of_room = 0;
        }


        $max_price = Property::with('translation')->where('status', 1)->orderBy('price', 'desc')->first();
        $min_price = Property::with('translation')->where('status', 1)->orderBy('price', 'asc')->first();
        if ($min_price) {
            $minimum_price = $min_price->price;
        } else {
            $minimum_price = 0;
        }

        if ($max_price) {
            $max_price = $max_price->price;
        } else {
            $max_price = 0;
        }


        $price_range = $max_price - $minimum_price;
        $mod_price = $price_range / 10;

        return view('index')->with([
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


    public function career()
    {

        $banner_image = BreadcrumbImage::find(13);
        $seo_text = SeoSetting::find(10);

        $careers = Career::where('status', 1)->orderBy('id', 'desc')->get();

        return view('career')->with([
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'careers' => $careers,
        ]);
    }

    public function show_career($slug)
    {

        $banner_image = BreadcrumbImage::find(13);

        $career = Career::where('status', 1)->where('slug', $slug)->first();

        return view('show_career')->with([
            'banner_image' => $banner_image,
            'career' => $career,
        ]);
    }


    public function store_career_application(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'cv' => 'required',
            'career_id' => 'required',
            'description' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'phone.required' => trans('user_validation.Phone is required'),
            'email.required' => trans('user_validation.Email is required'),
            'subject.required' => trans('user_validation.Subject is required'),
            'cv.required' => trans('user_validation.CV is required'),
            'description.required' => trans('user_validation.Description is required'),

        ];
        $this->validate($request, $rules, $customMessages);

        $career = new CareerRequest();
        $career->career_id = $request->career_id;
        $career->name = $request->name;
        $career->phone = $request->phone;
        $career->email = $request->email;
        $career->subject = $request->subject;
        $career->description = $request->description;
        if ($request->cv) {
            $extention = $request->cv->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;

            $destinationPath = public_path('uploads/custom-images/');
            $request->cv->move($destinationPath, $image_name);
            $career->cv = $image_name;
        }
        $career->save();

        $notification = trans('user_validation.Application successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }




    public function aboutUs()
    {
        $about = AboutUs::with('translation')->first();
        $banner_image = BreadcrumbImage::find(1);
        $overviews = Overview::with('translation')->where('status', 1)->get();
        $seo_text = SeoSetting::find(2);

        $partners = Partner::with('translation')->where('status', 1)->get();
        $home_page = HomePage::with('translation')->first();
        $team_visibility = false;
        if ($about->team_visibility == 1) {
            $team_visibility = true;
        }

        $partners = (object) array(
            'partner_visibility' => $team_visibility,
            'title' => $about->translated_team_title,
            'description' => $about->translated_team_description,
            'partners' => $partners,
        );

        return view('about_us')->with([
            'about' => $about,
            'banner_image' => $banner_image,
            'overviews' => $overviews,
            'partners' => $partners,
            'seo_text' => $seo_text
        ]);
    }


    public function blog(Request $request)
    {
        $banner_image = BreadcrumbImage::find(10);
        $paginator = CustomPagination::where('id', 1)->first()->qty;
        $blogs = Blog::with('admin', 'category', 'translation')->where('status', 1)->orderBy('id', 'desc');
        if ($request->category) {
            $category = BlogCategory::with('translation')->where('slug', $request->category)->first();
            $blogs = $blogs->where('blog_category_id', $category->id);
        }

        if ($request->search) {
            $blogs = $blogs->where('title', 'LIKE', '%' . $request->search . "%")
                ->orWhere('description', 'LIKE', '%' . $request->search . '%');
        }

        $blogs = $blogs->paginate($paginator);
        $blogs = $blogs->appends($request->all());
        $seo_text = SeoSetting::find(6);

        $default_profile_image = BannerImage::find(15)->image;

        return view('blog')->with([
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'blogs' => $blogs,
            'default_profile_image' => $default_profile_image,
        ]);
    }

    public function blogDetails($slug)
    {

        $blog = Blog::with('admin', 'category', 'translation')->where(['slug' => $slug, 'status' => 1])->first();
        if ($blog) {

            $blog->views += 1;
            $blog->save();

            $blogCategories = BlogCategory::with('translation')->where('status', 1)->get();

            $banner_image = BreadcrumbImage::find(10);
            $default_profile_image = BannerImage::find(15)->image;
            $blogComments = BlogComment::where(['blog_id' => $blog->id, 'status' => 1])->paginate(10);

            $popular_array = array();
            $popularBlogs = PopularPost::where('status', 1)->get()->take(5);

            foreach ($popularBlogs as $popularBlog) {
                $popular_array[] = $popularBlog->blog_id;
            }

            $popularBlogs = Blog::with('admin', 'category')->where('id', '!=', $blog->id)->where('status', 1)->whereIn('id', $popular_array)->orderBy('id', 'desc')->get()->take(4);

            $blog_comment_setting = FacebookComment::first();
            $recaptcha_setting = GoogleRecaptcha::first();

            return view('show_blog')->with([
                'blog' => $blog,
                'blogCategories' => $blogCategories,
                'popularBlogs' => $popularBlogs,
                'banner_image' => $banner_image,
                'default_profile_image' => $default_profile_image,
                'blogComments' => $blogComments,
                'blog_comment_setting' => $blog_comment_setting,
                'recaptcha_setting' => $recaptcha_setting,
            ]);
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return back()->with($notification);
        }
    }




    public function blogComment(Request $request, $blogId)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];

        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'comment.required' => trans('user_validation.Comment is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $comment = new BlogComment();
        $comment->blog_id = $blogId;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->save();


        $notification = array(
            'messege' => 'Commented Successufully',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    public function faq()
    {
        $faqs = Faq::with('translation')->where('status', 1)->get();
        $banner_image = BreadcrumbImage::find(4)->image;

        return view('faq')->with([
            'banner_image' => $banner_image,
            'faqs' => $faqs
        ]);
    }


    public function contactUs()
    {
        $contact = ContactPage::first();
        $recaptcha_setting = GoogleRecaptcha::first();
        $seo_text = SeoSetting::find(3);
        $banner_image = BreadcrumbImage::find(3)->image;

        return view('contact_us')->with([
            'contact' => $contact,
            'recaptcha_setting' => $recaptcha_setting,
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
        ]);
    }


    public function termsCondition()
    {
        $terms_conditions = TermsAndCondition::with('translation')->select('terms_and_condition')->first();
        $banner_image = BreadcrumbImage::find(6)->image;

        return view('terms_condition')->with([
            'terms_conditions' => $terms_conditions,
            'banner_image' => $banner_image

        ]);
    }



    public function privacyPolicy()
    {
        $banner_image = BreadcrumbImage::find(7)->image;
        $privacy_policy = TermsAndCondition::with('translation')->select('privacy_policy')->first();

        return view('privacy_policy')->with([
            'banner_image' => $banner_image,
            'privacy_policy' => $privacy_policy,
        ]);
    }



    // manage subsciber
    public function subscribeUs(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $customMessages = [
            'email.required' => trans('user_validation.Email is required')
        ];
        $this->validate($request, $rules, $customMessages);


        $isSubsriber = Subscriber::where('email', $request->email)->count();
        if ($isSubsriber == 0) {

            $subscribe = new Subscriber();
            $subscribe->email = $request->email;
            $subscribe->verified_token = Str::random(25);
            $subscribe->save();
            MailHelper::setMailConfig();

            $template = EmailTemplate::where('id', 4)->first();
            $message = $template->description;
            $subject = $template->subject;
            Mail::to($subscribe->email)->send(new SubscriptionVerification($subscribe, $message, $subject));

            $notification = trans('user_validation.Varification link send to your mail, please verify it');
            return response()->json(['success' => $notification], 200);
        } else {

            $notification = trans('user_validation.Email already exist');
            return response()->json(['error' => $notification]);
        }
    }

    public function subscriptionVerify($token)
    {
        $subscribe = Subscriber::where('verified_token', $token)->first();
        if ($subscribe) {
            $subscribe->status = 1;
            $subscribe->verified_token = null;
            $subscribe->save();

            $notification = trans('user_validation.Verification Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->route('home')->with($notification);
        } else {

            $notification = trans('user_validation.Invalid token');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        }
    }


    public function customPage($slug)
    {
        $page = CustomPage::with('translation')->where('slug', $slug)->first();

        $banner_image = BreadcrumbImage::find(11)->image;

        return view('custom_page')->with([
            'page' => $page,
            'banner_image' => $banner_image,
        ]);
    }


    public function agent(Request $request)
    {

        $banner_image = BreadcrumbImage::find(8)->image;
        $paginate_qty = CustomPagination::where('id', 3)->first()->qty;

        // start agent

        $orders = Order::where(['status' => 1])->where('expired_date', '>=', date('Y-m-d'))->orWhere('expired_date', null)->get();

        $agent_id = array();
        foreach ($orders as $order) {
            if (!in_array($order->user_id, $agent_id)) {
                $agent_id[] = $order->user_id;
            }
        }

        $agents = User::where('status', 1)->orderBy('id', 'desc')->whereIn('id', $agent_id);
        if ($request->location) {
            $city = City::with('translation')->where('slug', $request->location)->first();
            if ($city) {
                $agents = $agents->where('city_id', $city->id);
            }
        }
        $agents = $agents->paginate($paginate_qty);

        $default_profile_image = BannerImage::find(15)->image;
        $seo_text = SeoSetting::find(5);

        $cities = City::with('translation')->where('status', 1)->orderBy('name', 'asc')->get();

        return view('agent')->with([
            'banner_image' => $banner_image,
            'cities' => $cities,
            'agents' => $agents,
            'orders' => $orders,
            'default_profile_image' => $default_profile_image,
            'seo_text' => $seo_text
        ]);
    }

    public function agentDetails(Request $request)
    {

        $user_type = '';
        if (!$request->user_type) {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        } else {
            $user_type = $request->user_type;
        }


        if ($user_type == 1 || $user_type == 2) {
            if ($request->user_name) {
                if ($user_type == 1) {

                    $paginate_qty = CustomPagination::where('id', 2)->first()->qty;
                    $user = Admin::where(['status' => 1, 'slug' => $request->user_name])->first();
                    if (!$user) {
                        $notification = trans('user_validation.Something Went Wrong');
                        $notification = array('messege' => $notification, 'alert-type' => 'error');
                        return redirect()->route('home')->with($notification);
                    }

                    $banner_image = BreadcrumbImage::find(8)->image;
                    $default_image = BannerImage::find(15)->image;
                    $currency = Setting::first()->currency_icon;
                    $recaptcha_setting = GoogleRecaptcha::first();

                    $properties = Property::with('translation')->where(['status' => 1, 'admin_id' => $user->id])->paginate($paginate_qty);
                    $popluarProperties = Property::with('translation')->where('status', 1)->orderBy('views', 'desc')->get();
                    $properties = $properties->appends($request->all());

                    $default_profile_image = BannerImage::find(15)->image;

                    return view('show_agent')->with([
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
                } else {
                    $user = User::where(['status' => 1, 'slug' => $request->user_name])->first();
                    if (!$user) {
                        $notification = trans('user_validation.Something Went Wrong');
                        $notification = array('messege' => $notification, 'alert-type' => 'error');
                        return redirect()->route('home')->with($notification);
                    }

                    $paginate_qty = CustomPagination::where('id', 2)->first()->qty;
                    $banner_image = BreadcrumbImage::find(8)->image;
                    $default_image = BannerImage::find(15)->image;
                    $currency = Setting::first()->currency_icon;
                    $recaptcha_setting = GoogleRecaptcha::first();

                    $properties = Property::with('translation')->where(['status' => 1, 'user_id' => $user->id])->paginate($paginate_qty);
                    $properties = $properties->appends($request->all());
                    $popluarProperties = Property::with('translation')->where('status', 1)->orderBy('views', 'desc')->get();
                    $default_profile_image = BannerImage::find(15)->image;

                    return view('show_agent')->with([
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
            } else {
                $notification = trans('user_validation.Something Went Wrong');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->route('home')->with($notification);
            }
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        }
    }



    public function pricingPlan()
    {
        $packages = Package::with('translation')->where('status', 1)->orderBy('package_order', 'asc')->get();
        $seo_text = SeoSetting::find(8);
        $banner_image = BreadcrumbImage::find(2)->image;

        $currency = Setting::first()->currency_icon;

        return view('pricing_plan')->with([
            'packages' => $packages,
            'seo_text' => $seo_text,
            'banner_image' => $banner_image,
            'currency' => $currency,
        ]);
    }


    public function properties(Request $request)
    {


        Paginator::useBootstrap();
        // cheack page type, page type means grid view or listing view
        $page_type = '';
        if (!$request->page_type) {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        } else {
            if ($request->page_type == 'list_view') {
                $page_type = $request->page_type;
            } else if ($request->page_type == 'grid_view') {
                $page_type = $request->page_type;
            } else {
                $notification = trans('user_validation.Something Went Wrong');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->route('home')->with($notification);
            }
        }
        // end page type


        $paginate_qty = CustomPagination::where('id', 2)->first()->qty;

        if ($request->sorting_id) {
            $id = $request->sorting_id;
            if ($id == 1) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where('status', 1)->orderBy('id', 'desc')->paginate($paginate_qty);
            } else if ($id == 2) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where('status', 1)->orderBy('views', 'desc')->paginate($paginate_qty);
            } else if ($id == 3) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where(['is_featured' => 1, 'status' => 1])->orderBy('id', 'desc')->paginate($paginate_qty);
            } else if ($id == 4) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where(['top_property' => 1, 'status' => 1])->orderBy('id', 'desc')->paginate($paginate_qty);
            } else if ($id == 5) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where(['status' => 1])->orderBy('id', 'desc')->paginate($paginate_qty);
            } else if ($id == 6) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where(['urgent_property' => 1, 'status' => 1])->orderBy('id', 'desc')->paginate($paginate_qty);
            } else if ($id == 7) {
                $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where(['status' => 1])->orderBy('id', 'asc')->paginate($paginate_qty);
            }
        } else {
            $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->where('status', 1)->orderBy('id', 'desc')->paginate($paginate_qty);
        }

        $properties = $properties->appends($request->all());

        $banner_image = BreadcrumbImage::find(12)->image;
        $default_image = BannerImage::find(15)->image;
        $currency = Setting::first()->currency_icon;
        $seo_text = SeoSetting::find(9);
        $propertyTypes = PropertyType::with('translation')->where('status', 1)->orderBy('type', 'asc')->get();
        $cities = City::with('translation')->where('status', 1)->orderBy('name', 'asc')->get();
        $aminities = Aminity::with('translation')->where('status', 1)->orderBy('aminity', 'asc')->get();

        $max_number_of_room = Property::with('translation')->where('status', 1)->orderBy('number_of_room', 'desc')->first();
        if ($max_number_of_room) {
            $max_number_of_room = $max_number_of_room->number_of_room;
        } else {
            $max_number_of_room = 0;
        }


        $max_price = Property::with('translation')->where('status', 1)->orderBy('price', 'desc')->first();
        $min_price = Property::with('translation')->where('status', 1)->orderBy('price', 'asc')->first();
        if ($min_price) {
            $minimum_price = $min_price->price;
        } else {
            $minimum_price = 0;
        }

        if ($max_price) {
            $max_price = $max_price->price;
        } else {
            $max_price = 0;
        }

        $price_range = $max_price - $minimum_price;
        $mod_price = $price_range / 10;


        return view('property')->with([
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


    public function downloadListingFile($file)
    {
        $filepath = public_path() . "/uploads/custom-images/" . $file;
        return response()->download($filepath);
    }

    public function propertDetails($slug)
    {
        $property = Property::where('slug', $slug)->first();

        if ($property === null) {
            // Buscar por um slug similar
            $similarProperty = Property::where('slug', 'like', '%' . $slug . '%')->first();

            if ($similarProperty !== null) {
                // Se for encontrado um slug similar, redirecionar para a página de detalhes dessa propriedade
                return redirect()->route('property.details', ['slug' => $similarProperty->slug]);
            } else {
                // Se não for encontrado nenhum slug similar, redirecionar para alguma página de erro ou padrão
                return redirect()->route('error.page'); // Substitua 'error.page' pela rota desejada
            }
        }
        if ($property === null) {
            return redirect()->route('property.details', ['slug' => $slug]);
        }
        if ($property) {

            $isExpired = false;
            if ($property->expired_date == null) {
                $isExpired = false;
            } else if ($property->expired_date >= date('Y-m-d')) {
                $isExpired = false;
            } else {
                $isExpired = true;
            }
            if ($isExpired) {
                $notification = trans('user_validation.Something Went Wrong');
                $notification = array('messege' => $notification, 'alert-type' => 'error');
                return redirect()->back()->with($notification);
            }

            $property->views = $property->views + 1;
            $property->save();
            $similarProperties = Property::with('translation')->where(['status' => 1, 'property_type_id' => $property->property_type_id])->where('id', '!=', $property->id)->get()->take(3);

            $banner_image = BreadcrumbImage::find(2)->image;
            $default_image = BannerImage::find(15)->image;
            $currency = Setting::first()->currency_icon;
            $recaptcha_setting = GoogleRecaptcha::first();
            $propertyReviews = PropertyReview::where(['property_id' => $property->id, 'status' => 1])->paginate(10);


            return view('show_property', compact('property', 'banner_image', 'default_image', 'currency', 'recaptcha_setting', 'similarProperties', 'propertyReviews'));
        } else {
            $notification = trans('user_validation.Something Went Wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

    public function searchPropertyPage(Request $request)
    {
        Paginator::useBootstrap();

        // Definição inicial das variáveis
        $page_type = 'grid_view';
        $orderBy = "desc";
        $orderByView = false;

        // Verificação do tipo de ordenação
        if ($request->sorting_id) {
            if ($request->sorting_id == 7) {
                $orderBy = "asc";
            } else if ($request->sorting_id == 2) {
                $orderBy = "asc";
                $orderByView = true;
            }
        }

        // Consulta principal
        $properties = Property::with('propertyType', 'propertyPurpose', 'city')
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expired_date')->orWhere('expired_date', '>=', now());
            });

        // Filtros adicionais
        if ($request->city_id) {
            $properties->where('city_id', $request->city_id);
        }

        if ($request->search) {
            $properties->where('title', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->purpose_type) {
            $properties->where('property_purpose_id', $request->purpose_type);
        }

        // Filtro de número de quartos
        if ($request->number_of_rooms) {
            $properties->where('number_of_bedroom', $request->number_of_rooms);
        }

        // Ordenação
        if ($request->sorting_id) {
            if ($request->sorting_id == 7) {
                $properties->orderBy('id', 'asc');
            } else if ($request->sorting_id == 2) {
                $properties->orderBy('views', 'desc');
            }
        } else {
            $properties->orderBy('id', 'desc');
        }

        // Paginação
        $paginate_qty = CustomPagination::findOrFail(2)->qty;
        $properties = $properties->paginate($paginate_qty)->appends($request->all());

        // Outras variáveis necessárias para a view
        $aminities = Aminity::with('translation')->where('status', 1)->orderBy('aminity', 'asc')->get();
        $banner_image = BreadcrumbImage::findOrFail(12)->image;
        $default_image = BannerImage::findOrFail(15)->image;
        $currency = Setting::value('currency_icon');
        $seo_text = SeoSetting::findOrFail(9);
        $propertyTypes = PropertyType::with('translation')->where('status', 1)->orderBy('type', 'asc')->get();
        $cities = City::with('translation')->where('status', 1)->orderBy('name', 'asc')->get();
        $max_number_of_room = Property::where('status', 1)->max('number_of_room');
        $min_price = Property::where('status', 1)->min('price');
        $max_price = Property::where('status', 1)->max('price');
        $price_range = $max_price - $min_price;
        $mod_price = $price_range / 10;

        // Corrigindo o nome da variável
        $minimum_price = $min_price;

        return view('property', compact(
            'properties',
            'aminities',
            'seo_text',
            'banner_image',
            'page_type',
            'currency',
            'propertyTypes',
            'cities',
            'price_range',
            'mod_price',
            'minimum_price', // Corrigindo o nome da variável aqui
            'max_number_of_room'
        ));
    }




    // public function searchPropertyPage(Request $request)
    // {
    //     Paginator::useBootstrap();

    //     // check page type, page type means grid view or list view
    //     $page_type = 'grid_view';

    //     /*   if ($request->page_type == 'list_view') {
    //             $page_type = $request->page_type;
    //         } else if ($request->page_type == 'grid_view') {
    //             $page_type = $request->page_type;
    //         } else {
    //             $notification = trans('user_validation.Something Went Wrong');
    //             $notification = array('messege' => $notification, 'alert-type' => 'error');
    //             return redirect()->route('home')->with($notification);
    //         }*/
    //     // end page type

    //     // check aminity
    //     $sortArry = [];
    //     if ($request->aminity) {
    //         foreach ($request->aminity as $amnty) {
    //             array_push($sortArry, (int)$amnty);
    //         }
    //     } else {
    //         $aminities = Aminity::with('translation')->where('status', 1)->get();
    //         foreach ($aminities as $aminity) {
    //             array_push($sortArry, (int)$aminity->id);
    //         }
    //     }
    //     // end aminity

    //     // soriting data
    //     $paginate_qty = CustomPagination::where('id', 2)->first()->qty;
    //     // check order type
    //     $orderBy = "desc";
    //     $orderByView = false;
    //     if ($request->sorting_id) {
    //         if ($request->sorting_id == 7) {
    //             $orderBy = "asc";
    //         } else if ($request->sorting_id == 1) {
    //             $orderBy = "desc";
    //         } else if ($request->sorting_id == 5) {
    //             $orderBy = "desc";
    //         } else if ($request->sorting_id == 2) {
    //             $orderBy = "asc";
    //             $orderByView = true;
    //         }
    //     }
    //     // end check order type

    //     if ($request->aminity) {

    //         $properties = Property::with('propertyType', 'propertyPurpose', 'city', 'translation')->whereHas('propertyAminities', function ($query) use ($request) {

    //             // check aminity
    //             $sortArry = [];
    //             if ($request->aminity) {
    //                 foreach ($request->aminity as $amnty) {
    //                     array_push($sortArry, (int)$amnty);
    //                 }
    //             } else {
    //                 $aminities = Aminity::where('status', 1)->get();
    //                 foreach ($aminities as $aminity) {
    //                     array_push($sortArry, (int)$aminity->id);
    //                 }
    //             }
    //             // end aminity

    //             $query->whereIn('aminity_id', $sortArry);
    //         })->where('status', 1)->where(function ($query) {
    //             $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
    //         });
    //     } else {
    //         $properties = Property::with('propertyType', 'propertyPurpose', 'city')->where('status', 1)->where(function ($query) {
    //             $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
    //         });
    //     }

    //     if ($request->price_range) {
    //         $price_range = explode(':', $request->price_range);
    //         $min_price = $price_range[0];
    //         $max_price = $price_range[1];
    //         $properties = $properties->where('price', '<=', $max_price)->where('price', '>=', $min_price);
    //     }

    //     if ($request->number_of_room) {
    //         $properties = $properties->where('number_of_room', $request->number_of_room);
    //     }

    //     if ($request->property_id) {
    //         $properties = $properties->where('property_search_id', $request->property_id);
    //     }

    //     $orderBy = "desc";
    //     $orderByView = false;
    //     if ($request->sorting_id) {
    //         if ($request->sorting_id == 7) {
    //             $properties = $properties->orderBy('id', 'asc');
    //         } else if ($request->sorting_id == 1) {
    //             $properties = $properties->orderBy('id', 'desc');
    //         } else if ($request->sorting_id == 2) {
    //             $properties = $properties->orderBy('views', 'desc');
    //         } else if ($request->sorting_id == 3) {
    //             $properties = $properties->where('is_featured', 1);
    //         } else if ($request->sorting_id == 6) {
    //             $properties = $properties->where('urgent_property', 1);
    //         } else if ($request->sorting_id == 5) {
    //             $properties = $properties->orderBy('id', 'desc');
    //         } else if ($request->sorting_id == 4) {
    //             $properties = $properties->where('top_property', 1);
    //         }
    //     } else {
    //         $properties = $properties->orderBy('id', 'desc');
    //     }

    //     if ($request->city_id != null) {
    //         $properties->where(['city_id' => $request->city_id, 'status' => 1]);
    //     }
    //     if ($request->search != null) {
    //         $properties->where('title', 'LIKE', '%' . $request->search . '%')->where('status', 1);
    //     }

    //     if ($request->purpose_type != null) {
    //         $properties->where(['property_purpose_id' => $request->purpose_type, 'status' => 1]);
    //     }

    //     if ($request->sorting_id) {
    //         if ($request->sorting_id == 3) {
    //             $properties->where(['is_featured' => 1, 'status' => 1]);
    //         } else if ($request->sorting_id == 6) {
    //             $properties->where(['urgent_property' => 1, 'status' => 1]);
    //         } elseif ($request->sorting_id == 4) {
    //             $properties->where(['top_property' => 1, 'status' => 1]);
    //         }
    //     }

    //     $properties->where(['status' => 1]);

    //     $properties = $properties->paginate($paginate_qty);

    //     $properties = $properties->appends($request->all());

    //     $aminities = Aminity::with('translation')->where('status', 1)->orderBy('aminity', 'asc')->get();
    //     $banner_image = BreadcrumbImage::find(12)->image;
    //     $default_image = BannerImage::find(15)->image;
    //     $currency = Setting::first()->currency_icon;
    //     $seo_text = SeoSetting::find(9);
    //     $propertyTypes = PropertyType::with('translation')->where('status', 1)->orderBy('type', 'asc')->get();
    //     $cities = City::with('translation')->where('status', 1)->orderBy('name', 'asc')->get();

    //     $max_number_of_room = Property::with('translation')->where('status', 1)->orderBy('number_of_room', 'desc')->first();

    //     if ($max_number_of_room) {
    //         $max_number_of_room = $max_number_of_room->number_of_room;
    //     }

    //     $max_price = Property::where('status', 1)->orderBy('price', 'desc')->first();
    //     $min_price = Property::where('status', 1)->orderBy('price', 'asc')->first();

    //     if ($max_price) {
    //         $max_price = $max_price->price;
    //     } else {
    //         $max_price = 0;
    //     }

    //     if ($min_price) {
    //         $minimum_price = $min_price->price;
    //     } else {
    //         $minimum_price = 0;
    //     }




    //     $price_range = $max_price - $minimum_price;
    //     $mod_price = $price_range / 10;

    //     return view('property')->with([
    //         'properties' => $properties,
    //         'aminities' => $aminities,
    //         'seo_text' => $seo_text,
    //         'banner_image' => $banner_image,
    //         'page_type' => $page_type,
    //         'currency' => $currency,
    //         'propertyTypes' => $propertyTypes,
    //         'cities' => $cities,
    //         'price_range' => $price_range,
    //         'mod_price' => $mod_price,
    //         'minimum_price' => $minimum_price,
    //         'max_number_of_room' => $max_number_of_room,
    //     ]);
    // }


    public function testsearch(Request $request)
    {

        if ($request->aminity) {

            $properties = Property::with('propertyType', 'propertyPurpose', 'city')->whereHas('propertyAminities', function ($query) use ($request) {

                // check aminity
                $sortArry = [];
                if ($request->aminity) {
                    foreach ($request->aminity as $amnty) {
                        array_push($sortArry, (int)$amnty);
                    }
                } else {
                    $aminities = Aminity::where('status', 1)->get();
                    foreach ($aminities as $aminity) {
                        array_push($sortArry, (int)$aminity->id);
                    }
                }
                // end aminity

                $query->whereIn('aminity_id', $sortArry);
            })->where('status', 1)->where(function ($query) {
                $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
            });
        } else {
            $properties = Property::with('propertyType', 'propertyPurpose', 'city')->where('status', 1)->where(function ($query) {
                $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
            });
        }

        if ($request->price_range) {
            $price_range = explode(':', $request->price_range);
            $min_price = $price_range[0];
            $max_price = $price_range[1];
            $properties = $properties->where('price', '<=', $max_price)->where('price', '>=', $min_price);
        }

        if ($request->number_of_room) {
            $properties = $properties->where('number_of_room', $request->number_of_room);
        }

        if ($request->property_id) {
            $properties = $properties->where('property_search_id', $request->property_id);
        }

        $orderBy = "desc";
        $orderByView = false;
        if ($request->sorting_id) {
            if ($request->sorting_id == 7) {
                $properties = $properties->orderBy('id', 'asc');
            } else if ($request->sorting_id == 1) {
                $properties = $properties->orderBy('id', 'desc');
            } else if ($request->sorting_id == 2) {
                $properties = $properties->orderBy('views', 'desc');
            } else if ($request->sorting_id == 3) {
                $properties = $properties->where('is_featured', 1);
            } else if ($request->sorting_id == 6) {
                $properties = $properties->where('urgent_property', 1);
            } else if ($request->sorting_id == 5) {
                $properties = $properties->orderBy('id', 'desc');
            } else if ($request->sorting_id == 4) {
                $properties = $properties->where('top_property', 1);
            }
        } else {
            $properties = $properties->orderBy('id', 'desc');
        }

        if ($request->city_id != null) {
            $properties->where(['city_id' => $request->city_id, 'status' => 1]);
        }
        if ($request->search != null) {
            $properties->where('title', 'LIKE', '%' . $request->search . '%')->where('status', 1);
        }

        if ($request->purpose_type != null) {
            $properties->where(['property_purpose_id' => $request->purpose_type, 'status' => 1]);
        }

        if ($request->sorting_id) {
            if ($request->sorting_id == 3) {
                $properties->where(['is_featured' => 1, 'status' => 1]);
            } else if ($request->sorting_id == 6) {
                $properties->where(['urgent_property' => 1, 'status' => 1]);
            } elseif ($request->sorting_id == 4) {
                $properties->where(['top_property' => 1, 'status' => 1]);
            }
        }

        $properties->where(['status' => 1]);

        $properties = $properties->paginate($paginate_qty);

        $properties = $properties->appends($request->all());


        $properties = $properties->get();



        $properties = Property::with('propertyType', 'propertyPurpose', 'city')->where('status', 1)->whereIn('id', $property_arr)->where(function ($query) {
            $query->where('expired_date', null)->orWhere('expired_date', '>=', date('Y-m-d'));
        });


        if ($request->price_range) {
            $price_range = explode(':', $request->price_range);
            $min_price = $price_range[0];
            $max_price = $price_range[1];
            $properties = $properties->where('price', '<=', $max_price)->where('price', '>=', $min_price);
        }

        if ($request->number_of_room) {
            $properties = $properties->where('number_of_room', $request->number_of_room);
        }

        if ($request->property_id) {
            $properties = $properties->where('property_search_id', $request->property_id);
        }

        $orderBy = "desc";
        $orderByView = false;
        if ($request->sorting_id) {
            if ($request->sorting_id == 7) {
                $properties = $properties->orderBy('id', 'asc');
            } else if ($request->sorting_id == 1) {
                $properties = $properties->orderBy('id', 'desc');
            } else if ($request->sorting_id == 2) {
                $properties = $properties->orderBy('views', 'desc');
            } else if ($request->sorting_id == 3) {
                $properties = $properties->where('is_featured', 1);
            } else if ($request->sorting_id == 6) {
                $properties = $properties->where('urgent_property', 1);
            } else if ($request->sorting_id == 5) {
                $properties = $properties->orderBy('id', 'desc');
            } else if ($request->sorting_id == 4) {
                $properties = $properties->where('top_property', 1);
            }
        } else {
            $properties = $properties->orderBy('id', 'desc');
        }

        $properties = $properties->paginate($paginate_qty);

        $properties = $properties->appends($request->all());
    }

    public function setLanguage()
    {
        $lang = Language::whereCode(request('code'))->first();

        if (session()->has('lang')) {
            session()->forget('lang');
            session()->forget('text_direction');
        }
        if ($lang) {
            session()->put('lang', $lang->code);
            session()->put('text_direction', $lang->direction);

            $notification = trans('user_validation.Language Changed Successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        }

        session()->put('lang', config('app.locale'));

        $notification = trans('user_validation.Language Changed Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
