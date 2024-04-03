<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LinkJsonProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Image;
use Hash;
use File;

class ApiIntegrationController extends Controller
{

    public function loginApi(Request $request)
    {
        $data  = $request->only('email', 'password');
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'User not Found '], 404);
        }

        if ($user->status == 0) {
            return response()->json(['message' => 'User Inactive '], 403);
        }
        $token = auth()->login($user);;
        $passVerication = Hash::check($data['password'],  $user->password);

        if (empty($user) ||  $passVerication == false) {
            return response()->json(['status' => 'error', 'message' => 'the login is wrong'], 401);
        }
        if ($token) {
            $array['token'] = $token;
        } else {
            $array['message'] = 'Incorrect username or password';
        }

        return response()->json(['message' => "User Logged in!", 'token' => $array['token'], 'user' => $user], 200);
    }

    public function createLink(Request $request)
    {
        $user = Auth::user();

        LinkJsonProperty::create([
            'user_id' => $user->id,
            'link_json' => $request->link_json
        ]);

        return response()->json(['status' => 'ok']);
    }


    public function updateProperty(Request $request, $codeImobApi)
    {

        $property = Property::where('code_property_api', $codeImobApi)->first();

        $user = Auth::user();
        // Se a propriedade não existir, cria uma nova
        if (!$property) {
            $property = new Property();
        }

        $video_link = '';
        if (preg_match('/https:\/\/www\.youtube\.com\/watch\?v=[^&]+/', $request->video_link)) {
            $video_link = $request->video_link;
        }
        // Atualiza as informações da propriedade
        $property->user_id = $user->id;
        $property->code_property_api = $request->code_imob;
        $property->value_condominio = $request->value_condominio;
        $property->value_iptu = $request->iptu;
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
        $property->video_link = $video_link;
        $property->google_map_embed_code = $request->google_map_embed_code;
        $property->description = $request->description;
        $property->is_featured = $request->featured ? $request->featured : 0;
        $property->urgent_property = $request->urgent_property ? $request->urgent_property : 0;
        $property->top_property = $request->top_property ? $request->top_property : 0;
        $property->seo_title = $request->seo_title ? $request->seo_title : $request->title;
        $property->seo_description = $request->seo_description ? $request->seo_description : $request->title;

        // Salva a propriedade
        $property->save();

        // Limpa os aminities existentes e adiciona os novos, se houverem
        $property->propertyAminities()->delete();
        if ($request->has('aminities')) {
            foreach ($request->input('aminities') as $amnty) {
                $property->propertyAminities()->create(['aminity_id' => $amnty]);
            }
        }

        // Limpa as localizações mais próximas existentes e adiciona as novas, se houverem
        $property->propertyNearestLocations()->delete();
        if ($request->has('nearest_locations')) {
            foreach ($request->input('nearest_locations') as $index => $location) {
                if ($location && $request->has('distances.' . $index)) {
                    $property->propertyNearestLocations()->create([
                        'nearest_location_id' => $location,
                        'distance' => $request->input('distances.' . $index)
                    ]);
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


        return response()->json(['message' => 'Propriedade atualizada ou criada com sucesso'], 200);
    }
}
