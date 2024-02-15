<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\City;
use App\Models\PropertyPurpose;
use App\Models\Aminity;
use App\Models\Language;
use App\Models\PropertyAminity;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Wishlist;
use App\Models\NearestLocation;
use App\Models\PropertyReview;
use Str;
use File;
use Image;
use Auth;
use App\Models\Setting;
use App\Models\PropertyNearestLocation;
use App\Models\PropertyTranslation;
use App\Models\User;

class AdminPropertyController extends Controller
{
    use TranslationTrait;
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $properties = Property::with('propertyType', 'propertyPurpose')->where('user_type', 1)->orderBy('id', 'desc')->get();
        $currency = Setting::first()->currency_icon;
        $languages = Language::all();
        return view('admin.property')->with(['properties' => $properties, 'currency' => $currency, 'languages' => $languages]);
    }
    public function agentProperty()
    {
        $properties = Property::with('propertyType', 'propertyPurpose', 'user')->where('user_type', 0)->orderBy('id', 'desc')->get();
        $currency = Setting::first()->currency_icon;
        $languages = Language::all();
        return view('admin.agent_property')->with(['properties' => $properties, 'currency' => $currency, 'languages' => $languages]);
    }
    public function create()
    {
        $agents = User::all();
        $propertyTypes = PropertyType::where('status', 1)->get();
        $cities = City::where('status', 1)->get();
        $purposes = PropertyPurpose::where('status', 1)->get();
        $aminities = Aminity::where('status', 1)->get();
        $nearest_locatoins = NearestLocation::where('status', 1)->get();
        return view('admin.create_property')->with([
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'purposes' => $purposes,
            'aminities' => $aminities,
            'nearest_locatoins' => $nearest_locatoins,
            'agents' => $agents,
        ]);
    }
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|unique:properties',
            'slug' => 'required|unique:properties',
            'property_type' => 'required',
            'city' => 'required',
            'agent' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'purpose' => 'required',
            'price' => 'required|numeric',
            'area' => 'required',
            'unit' => 'required',
            'room' => 'required',
            'bedroom' => 'required',
            'bathroom' => 'required',
            'floor' => 'required',
            "banner_image"    => "required|file",
            'thumbnail_image' => 'required|file',
            "slider_images"    => "required",
            'description' => 'required',
            'status' => 'required',
            'featured' => 'required',
            'urgent_property' => 'required',
            "pdf_file" => "mimes:pdf|max:10000"
        ];
        $customMessages = [
            'agent.required' => trans('admin_validation.Property agent is required'),
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'property_type.required' => trans('admin_validation.Property type is required'),
            'city.required' => trans('admin_validation.City is required'),
            'address.required' => trans('admin_validation.Address is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'purpose.required' => trans('admin_validation.Purpose is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'area.required' => trans('admin_validation.Area is required'),
            'unit.required' => trans('admin_validation.Unit is required'),
            'room.required' => trans('admin_validation.Room is required'),
            'bedroom.required' => trans('admin_validation.Bedroom is required'),
            'floor.required' => trans('admin_validation.Floor is required'),
            'banner_image.required' => trans('admin_validation.Banner image is required'),
            'thumbnail_image.required' => trans('admin_validation.Thumbnail is required'),
            'slider_images.required' => trans('admin_validation.Slider image is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $property = new Property();
        $admin = Auth::guard('admin')->user();
        if ($request->agent == 0) {
            $property->admin_id = $admin->id;
            $property->user_type = 1;
        } else {
            $property->user_id = $request->agent;
            $property->user_type = 0;
        }
        $property->title = $request->title;
        $property->property_search_id = mt_rand(10000000, 99999999);
        $property->slug = $request->slug;
        $property->property_type_id = $request->property_type;
        $property->city_id = $request->city;
        $property->address = $request->address;
        $property->phone = $request->phone;
        $property->email = $request->email;
        $property->website = $request->website;
        $property->property_purpose_id = $request->purpose;
        $property->price = $request->price;
        $property->period = $request->period ? $request->period : null;
        $property->area = $request->area;
        $property->number_of_unit = $request->unit;
        $property->number_of_room = $request->room;
        $property->number_of_bedroom = $request->bedroom;
        $property->number_of_bathroom = $request->bathroom;
        $property->number_of_floor = $request->floor;
        $property->number_of_kitchen = $request->kitchen;
        $property->number_of_parking = $request->parking;
        $property->video_link = $request->video_link;
        $property->google_map_embed_code = $request->google_map_embed_code;
        $property->description = $request->description;
        $property->status = $request->status;
        $property->is_featured = $request->featured;
        $property->urgent_property = $request->urgent_property;
        $property->top_property = $request->top_property;
        $property->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $property->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        // pdf file
        if ($request->file('pdf_file')) {
            $file = $request->pdf_file;
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'property-file-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $file_ext;
            $file_path = $file_name;
            $file->move(public_path() . '/uploads/custom-images/', $file_path);
            $property->file = $file_path;
        }
        //thumbnail image
        if ($request->file('thumbnail_image')) {
            $thumbnail_image = $request->thumbnail_image;
            $thumbnail_extention = $thumbnail_image->getClientOriginalExtension();
            $thumb_name = 'property-thumb-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $thumbnail_extention;
            $thumb_path = 'uploads/custom-images/' . $thumb_name;
            Image::make($thumbnail_image)
                ->save(public_path() . "/" . $thumb_path);
            $property->thumbnail_image = $thumb_path;
        }
        // banner image image
        if ($request->file('banner_image')) {
            $banner_image = $request->banner_image;
            $banner_ext = $banner_image->getClientOriginalExtension();
            $banner_name = 'listing-banner-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $banner_ext;
            $banner_path = 'uploads/custom-images/' . $banner_name;
            Image::make($banner_image)
                ->save(public_path() . "/" . $banner_path);
            $property->banner_image = $banner_path;
        }
        $property->save();
        // property end
        // insert aminity
        if ($request->aminities) {
            foreach ($request->aminities as $amnty) {
                $aminity = new PropertyAminity();
                $aminity->property_id = $property->id;
                $aminity->aminity_id = $amnty;
                $aminity->save();
            }
        }
        // insert nearest place
        $exist_location = [];
        if ($request->nearest_locations) {
            foreach ($request->nearest_locations as $index => $location) {
                if ($location) {
                    if ($request->distances[$index]) {
                        if (!in_array($location, $exist_location)) {
                            $nearest_location = new PropertyNearestLocation();
                            $nearest_location->property_id = $property->id;
                            $nearest_location->nearest_location_id = $location;
                            $nearest_location->distance = $request->distances[$index];
                            $nearest_location->save();
                        }
                        $exist_location[] = $location;
                    }
                }
            }
        }
        // slider image
        if ($request->file('slider_images')) {
            $images = $request->slider_images;
            foreach ($images as $image) {
                if ($image != null) {
                    $propertyImage = new PropertyImage();
                    $slider_ext = $image->getClientOriginalExtension();
                    // for small image
                    $slider_image = 'property-slide-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $slider_ext;
                    $slider_path = 'uploads/custom-images/' . $slider_image;
                    Image::make($image)
                        ->save(public_path() . "/" . $slider_path);
                    $propertyImage->image = $slider_path;
                    $propertyImage->property_id = $property->id;
                    $propertyImage->save();
                }
            }
        }
        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.property.index')->with($notification);
    }
    public function show($id)
    {
        $property = Property::with('propertyType', 'propertyPurpose', 'propertyAminities', 'propertyImages', 'propertyNearestLocations', 'city', 'user', 'reviews', 'admin')->find($id);
        return response()->json(['property' => $property]);
    }
    public function edit(Property $property)
    {
        // return $property;
        $agents = User::all();
        $propertyTypes = PropertyType::where('status', 1)->get();
        $cities = City::where('status', 1)->get();
        $purposes = PropertyPurpose::where('status', 1)->get();
        $aminities = Aminity::where('status', 1)->get();
        $nearest_locatoins = NearestLocation::where('status', 1)->get();
        return view('admin.edit_property')->with([
            'property' => $property,
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'purposes' => $purposes,
            'aminities' => $aminities,
            'nearest_locatoins' => $nearest_locatoins,
            'propertyTypes' => $propertyTypes,
            'agents' => $agents,
        ]);
    }
    public function update(Request $request, Property $property)
    {
        $rules = [
            'title' => 'required|unique:properties,title,' . $property->id,
            'slug' => 'required|unique:properties,slug,' . $property->id,
            'property_type' => 'required',
            'agent' => 'required',
            'city' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'purpose' => 'required',
            'price' => 'required|numeric',
            'area' => 'required',
            'unit' => 'required',
            'room' => 'required',
            'bedroom' => 'required',
            'bathroom' => 'required',
            'floor' => 'required',
            'description' => 'required',
            'status' => 'required',
            'featured' => 'required',
            'urgent_property' => 'required',
            "pdf_file" => "mimes:pdf|max:10000"
        ];
        $customMessages = [
            'agent.required' => trans('admin_validation.Property agent is required'),
            'title.required' => trans('admin_validation.Title is required'),
            'title.unique' => trans('admin_validation.Title already exist'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'property_type.required' => trans('admin_validation.Property type is required'),
            'city.required' => trans('admin_validation.City is required'),
            'address.required' => trans('admin_validation.Address is required'),
            'email.required' => trans('admin_validation.Email is required'),
            'purpose.required' => trans('admin_validation.Purpose is required'),
            'price.required' => trans('admin_validation.Price is required'),
            'area.required' => trans('admin_validation.Area is required'),
            'unit.required' => trans('admin_validation.Unit is required'),
            'room.required' => trans('admin_validation.Room is required'),
            'bedroom.required' => trans('admin_validation.Bedroom is required'),
            'floor.required' => trans('admin_validation.Floor is required'),
            'banner_image.required' => trans('admin_validation.Banner image is required'),
            'thumbnail_image.required' => trans('admin_validation.Thumbnail is required'),
            'slider_images.required' => trans('admin_validation.Slider image is required'),
            'description.required' => trans('admin_validation.Description is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $admin = Auth::guard('admin')->user();
        if ($request->agent == 0) {
            $property->admin_id = $admin->id;
            $property->user_type = 1;
        } else {
            $property->user_id = $request->agent;
            $property->user_type = 0;
        }
        $property->title = $request->title;
        $property->slug = $request->slug;
        $property->property_type_id = $request->property_type;
        $property->city_id = $request->city;
        $property->address = $request->address;
        $property->phone = $request->phone;
        $property->email = $request->email;
        $property->website = $request->website;
        $property->property_purpose_id = $request->purpose;
        $property->price = $request->price;
        $property->period = $request->period ? $request->period : null;
        $property->area = $request->area;
        $property->number_of_unit = $request->unit;
        $property->number_of_room = $request->room;
        $property->number_of_bedroom = $request->bedroom;
        $property->number_of_bathroom = $request->bathroom;
        $property->number_of_floor = $request->floor;
        $property->number_of_kitchen = $request->kitchen;
        $property->number_of_parking = $request->parking;
        $property->video_link = $request->video_link;
        $property->google_map_embed_code = $request->google_map_embed_code;
        $property->description = $request->description;
        $property->status = $request->status;
        $property->is_featured = $request->featured;
        $property->urgent_property = $request->urgent_property;
        $property->top_property = $request->top_property;
        $property->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $property->seo_description = $request->seo_description ? $request->seo_description : $request->title;
        // pdf file
        if ($request->file('pdf_file')) {
            $file = $request->pdf_file;
            $old_file = $property->file;
            $file_ext = $file->getClientOriginalExtension();
            $file_name = 'property-file-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $file_ext;
            $file_path = $file_name;
            $file->move(public_path() . '/uploads/custom-images/', $file_path);
            $property->file = $file_path;
            $property->save();
            if ($old_file) {
                if (File::exists(public_path() . '/' . "uploads/custom-images/" . $old_file)) unlink(public_path() . '/' . "uploads/custom-images/" . $old_file);
            }
        }
        //thumbnail image
        if ($request->file('thumbnail_image')) {
            $old_thumbnail = $property->thumbnail_image;
            $thumbnail_image = $request->thumbnail_image;
            $thumbnail_extention = $thumbnail_image->getClientOriginalExtension();
            $thumb_name = 'property-thumb-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $thumbnail_extention;
            $thumb_path = 'uploads/custom-images/' . $thumb_name;
            Image::make($thumbnail_image)
                ->save(public_path() . '/' . $thumb_path);
            $property->thumbnail_image = $thumb_path;
            $property->save();
            if (File::exists(public_path() . '/' . $old_thumbnail)) unlink(public_path() . '/' . $old_thumbnail);
        }
        // banner image image
        if ($request->file('banner_image')) {
            $old_banner = $property->banner_image;
            $banner_image = $request->banner_image;
            $banner_ext = $banner_image->getClientOriginalExtension();
            $banner_name = 'listing-banner-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $banner_ext;
            $banner_path = 'uploads/custom-images/' . $banner_name;
            Image::make($banner_image)
                ->save(public_path() . '/' . $banner_path);
            $property->banner_image = $banner_path;
            $property->save();
            if (File::exists(public_path() . '/' . $old_banner)) unlink(public_path() . '/' . $old_banner);
        }
        $property->save();
        // property end
        // for aminity
        $old_aminities = $property->propertyAminities;
        if ($request->aminities) {
            foreach ($request->aminities as $amnty) {
                $aminity = new PropertyAminity();
                $aminity->property_id = $property->id;
                $aminity->aminity_id = $amnty;
                $aminity->save();
            }
            if ($old_aminities->count() > 0) {
                foreach ($old_aminities as $old_aminity) {
                    $old_aminity->delete();
                }
            }
        } else {
            if ($old_aminities->count() > 0) {
                foreach ($old_aminities as $old_aminity) {
                    $old_aminity->delete();
                }
            }
        }
        // insert nearest place
        $old_nearest_locations = $property->propertyNearestLocations;
        $exist_location = [];
        $new_nearest_location = false;
        if ($request->nearest_locations) {
            foreach ($request->nearest_locations as $index => $location) {
                if ($location) {
                    if ($request->distances[$index]) {
                        if (!in_array($location, $exist_location)) {
                            $nearest_location = new PropertyNearestLocation();
                            $nearest_location->property_id = $property->id;
                            $nearest_location->nearest_location_id = $location;
                            $nearest_location->distance = $request->distances[$index];
                            $nearest_location->save();
                            $new_nearest_location = true;
                        }
                        $exist_location[] = $location;
                    }
                }
            }
            if ($new_nearest_location) {
                if ($old_nearest_locations->count() > 0) {
                    foreach ($old_nearest_locations as $old_location) {
                        $old_location->delete();
                    }
                }
            }
        } else {
            if ($old_nearest_locations->count() > 0) {
                foreach ($old_nearest_locations as $old_location) {
                    $old_location->delete();
                }
            }
        }
        // slider image
        if ($request->file('slider_images')) {
            $images = $request->slider_images;
            foreach ($images as $image) {
                if ($image != null) {
                    $propertyImage = new PropertyImage();
                    $slider_ext = $image->getClientOriginalExtension();
                    // for small image
                    $slider_image = 'property-slide-' . date('Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $slider_ext;
                    $slider_path = 'uploads/custom-images/' . $slider_image;
                    Image::make($image)
                        ->save(public_path() . '/' . $slider_path);
                    $propertyImage->image = $slider_path;
                    $propertyImage->property_id = $property->id;
                    $propertyImage->save();
                }
            }
        }
        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.property.index')->with($notification);
    }
    public function destroy(Property $property)
    {
        $old_thumbnail = $property->thumbnail_image;
        $old_banner = $property->banner_image;
        $old_pdf = $property->file;
        PropertyAminity::where('property_id', $property->id)->delete();
        Wishlist::where('property_id', $property->id)->delete();
        PropertyReview::where('property_id', $property->id)->delete();
        PropertyNearestLocation::where('property_id', $property->id)->delete();
        foreach ($property->propertyImages as $image) {
            if (File::exists(public_path() . '/' . $image->image)) unlink(public_path() . '/' . $image->image);
        }
        PropertyImage::where('property_id', $property->id)->delete();
        if ($old_pdf) {
            if (File::exists(public_path() . '/' . 'uploads/custom-images/' . $old_pdf)) unlink(public_path() . '/' . 'uploads/custom-images/' . $old_pdf);
        }
        if (File::exists(public_path() . '/' . $old_thumbnail)) unlink(public_path() . '/' . $old_thumbnail);
        if (File::exists(public_path() . '/' . $old_banner)) unlink(public_path() . '/' . $old_banner);
        $property->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
    public function propertySliderImage($id)
    {
        $image = PropertyImage::find($id);
        $old_image = $image->image;
        $image->delete();
        if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
        $notification = trans('admin_validation.Removed Successfully');
        return response()->json(['success' => $notification]);
    }
    public function deletePdfFile($id)
    {
        $property = Property::find($id);
        $old_file = $property->file;
        $property->file = null;
        $property->save();
        $old_file = "uploads/custom-images/" . $old_file;
        if (File::exists(public_path() . '/' . $old_file)) unlink(public_path() . '/' . $old_file);
        $notification = trans('admin_validation.Removed Successfully');
        return response()->json(['success' => $notification]);
    }
    public function changeStatus($id)
    {
        $property = Property::find($id);
        if ($property->status == 1) {
            $property->status = 0;
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $property->status = 1;
            $message = trans('admin_validation.Active Successfully');
        }
        $property->save();
        return response()->json($message);
    }
    public function existNearestLocation($id)
    {
        $nearest_location = PropertyNearestLocation::find($id);
        $nearest_location->delete();
        $notification = trans('admin_validation.Removed Successfully');
        return response()->json(['success' => $notification]);
    }
    public function propertyReview()
    {
        $reviews = PropertyReview::with('user', 'property')->orderBy('id', 'desc')->get();
        return view('admin.property_review')->with([
            'reviews' => $reviews
        ]);
    }
    public function reviewDelete($id)
    {
        $review = PropertyReview::find($id);
        $review->delete();
        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
    public function reviewChangeStatus($id)
    {
        $review = PropertyReview::find($id);
        if ($review->status == 1) {
            $review->status = 0;
            $message = trans('admin_validation.Inactive Successfully');
        } else {
            $review->status = 1;
            $message = trans('admin_validation.Active Successfully');
        }
        $review->save();
        return response()->json($message);
    }
    public function editTranslation($id, $code, $type = 'admin')
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'PropertyTranslation',
                'Property',
                'property_id',
                'title',
                'description',
            );
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            if ($type == 'agent') {
                return redirect()->route('admin.agent-property')->with($notification);
            }
            return redirect()->route('admin.property.index')->with($notification);
        }

        $translation = $this->createAndUpdateFromGoogleTranslation(
            $id,
            $code,
            'PropertyTranslation',
            'Property',
            'property_id',
            'title',
        );

        return view('admin.edit_property_translation', ['property' => $translation]);
    }
    public function updateTranslation(Request $request, $id, $code, $type = 'admin')
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation(
                $id,
                $code,
                'PropertyTranslation',
                'Property',
                'property_id',
                'title',
                'description',
            );
            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );
            if ($type == 'agent') {
                return redirect()->route('admin.agent-property')->with($notification);
            }
            return redirect()->route('admin.property.index')->with($notification);
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
        $translation = PropertyTranslation::firstOrCreate([
            'language_code' => $code,
            'property_id' => $id
        ]);
        $translation->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );
        if ($type == 'agent') {
            return redirect()->route('admin.agent-property')->with($notification);
        }
        return redirect()->route('admin.property.index')->with($notification);
    }
}
