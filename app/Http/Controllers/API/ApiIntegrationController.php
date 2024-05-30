<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Aminity;
use App\Models\City;
use App\Models\LinkJsonProperty;
use App\Models\NearestLocation;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyPurpose;
use App\Models\PropertyType;
use App\Models\User;
use Image;
use Hash;
use File;

class ApiIntegrationController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginApi",
     *      tags={"Authentication"},
     *      summary="Login user",
     *      description="Logs in a user with email and password",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User logged in successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User Logged in!"),
     *              @OA\Property(property="token", type="string", example="JWT token"),
     *              @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid login credentials",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="message", type="string", example="the login is wrong"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="User is inactive",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User Inactive"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not Found"),
     *          ),
     *      ),
     * )
     */

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


    /**
     * @OA\Put(
     *      path="/api/update-property",
     *      operationId="updateProperty",
     *      tags={"Property"},
     *      summary="Update or create property",
     *      description="Updates or creates a property based on the provided code",
     *      @OA\Parameter(
     *          name="codeImobApi",
     *          in="path",
     *          description="Code of the property",
     *          required=true,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Property"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Property updated or created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Property updated or created successfully"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request or missing data",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Something Went Wrong"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Package has been expired",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Package has been expired"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Package not found or invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Package not found or invalid"),
     *          ),
     *      ),
     * )
     */
    public function updateProperty(Request $request)
    {

        $property = Property::where('code_property_api', $request->codeImobApi)->first();

        $user = Auth::user();
        $order = Order::where(['user_id' => $user->id, 'status' => 1])->first();

        if (!$order) {
            return response()->json(['message' => 'Something Went Wrong'], 400);
        }

        $isExpired = false;
        if ($order->expired_date != null) {
            if (date('Y-m-d') > $order->expired_date) {
                $isExpired = true;
            }
        }

        if ($isExpired == true) {
            return response()->json(['message' => 'Package has been expired'], 400);
        }

        $package = Package::find($order->package_id);
        if (!$package || $package->number_of_property == 0) {
            return response()->json(['message' => 'Package not found or invalid'], 400);
        }

        $existProperty = Property::where(['user_id' => $user->id])->count();
        if ($package->number_of_property != -1 && $existProperty >= $package->number_of_property) {
            return response()->json(['message' => 'Exceeded the limit of allowed properties for this package'], 400);
        }
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
        if ($request->has('slider_images')) {
            $images = $request->slider_images;
            foreach ($images as $image) {
                if (!empty($image)) {
                    // Crie uma nova instância de PropertyImage
                    $propertyImage = new PropertyImage();

                    // Salve o link da imagem
                    $propertyImage->image = $image;

                    // Defina o ID da propriedade
                    $propertyImage->property_id = $property->id;

                    // Salve a instância do PropertyImage
                    $propertyImage->save();
                }
            }
        }


        return response()->json(['message' => 'Propriedade atualizada ou criada com sucesso'], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/get-info-property",
     *      operationId="getInfoProperty",
     *      tags={"Property"},
     *      summary="Get property info",
     *      description="Gets information about properties for the authenticated user",
     *      @OA\Response(
     *          response=200,
     *          description="Property info retrieved successfully",
     *          @OA\JsonContent(ref="#/components/schemas/PropertyInfo"),
     *      ),
     * )
     */
    public function getInfoProperty()
    {

        $user = Auth::guard('web')->user();

        // Se todas as verificações passarem, permita a criação de um novo imóvel
        $propertyTypes = PropertyType::where('status', 1)->get();
        $cities = City::where('status', 1)->get();
        $purposes = PropertyPurpose::where('status', 1)->get();
        $aminities = Aminity::where('status', 1)->get();
        $nearest_locatoins = NearestLocation::where('status', 1)->get();
        $existFeaturedProperty = Property::where(['user_id' => $user->id, 'is_featured' => 1])->count();
        $existTopProperty = Property::where(['user_id' => $user->id, 'top_property' => 1])->count();
        $existUrgentProperty = Property::where(['user_id' => $user->id, 'urgent_property' => 1])->count();

        return response()->json([
            'propertyTypes' => $propertyTypes,
            'cities' => $cities,
            'purposes' => $purposes,
            'aminities' => $aminities,
            'nearest_locatoins' => $nearest_locatoins,
            'existFeaturedProperty' => $existFeaturedProperty,
            'existTopProperty' => $existTopProperty,
            'existUrgentProperty' => $existUrgentProperty,
        ], 200);
    }

    public function importMoradImoveis()
    {
        // URL do XML
        $url = 'https://www.moradimoveis.com.br/apb_vrsync_feed.php';

        // Carregar o XML da URL
        $xml = simplexml_load_file($url);

        // Verificar se o XML foi carregado com sucesso
        if ($xml === false) {
            return json_encode(['error' => 'Falha ao carregar o XML']);
        }

        // Inicializar um array para armazenar os resultados
        $listings = array();

        // Iterar sobre cada elemento Listing no XML
        foreach ($xml->Listings->Listing as $listing) {
            // Inicializar um array para armazenar os dados do listing atual
            $listingData = array();

            // Iterar sobre os elementos dentro do listing atual
            foreach ($listing->children() as $element) {
                // Verificar se o elemento é do tipo "Media"
                if ($element->getName() == 'Media') {
                    // Inicializar um array para armazenar as URLs das imagens
                    $images = array();

                    // Iterar sobre os elementos "Item" dentro de "Media"
                    foreach ($element->children() as $item) {
                        // Extrair a URL da imagem do elemento CDATA
                        $image_url = (string)$item;

                        // Adicionar a URL da imagem ao array de imagens
                        $images[] = $image_url;
                    }

                    // Adicionar o array de URLs de imagens aos dados do listing
                    $listingData['Media'] = $images;
                } else {
                    // Verificar se o elemento é do tipo "Details" ou "Location"
                    if ($element->getName() == 'Details' || $element->getName() == 'Location') {
                        // Inicializar um array para armazenar os dados do elemento
                        $details = array();

                        // Iterar sobre os elementos dentro de "Details" ou "Location"
                        foreach ($element->children() as $subElement) {
                            // Remover espaços em branco e caracteres de tabulação do valor do subelemento
                            $value = trim((string)$subElement);

                            // Adicionar o valor do subelemento ao array de dados do detalhe ou localização
                            $details[$subElement->getName()] = $value;
                        }

                        // Adicionar o array de dados de detalhe ou localização aos dados do listing
                        $listingData[$element->getName()] = $details;
                    } else {
                        // Remover espaços em branco e caracteres de tabulação do valor do elemento
                        $value = trim((string)$element);

                        // Adicionar o valor do elemento ao array de dados do listing
                        $listingData[$element->getName()] = $value;
                    }
                }
            }

            // Adicionar os dados do listing ao array de resultados
            $listings[] = $listingData;
        }

        // Converter o array de resultados em JSON
        $json = json_encode($listings);
        // Decodificar o JSON para um array
        $listingsArray = json_decode($json, true);

        // Verificar se a decodificação foi bem-sucedida
        if ($listingsArray === null) {
            // Lidar com erros de decodificação, se necessário
            echo "Erro ao decodificar o JSON.";
        } else {
            // Iterar sobre os listings
            foreach ($listingsArray as $listing) {
                $property = Property::where('code_property_api', $listing['ListingID'])->first();

                $city = City::where('name', $listing['Location']['City'])->first();

                // Se a propriedade não existir, cria uma nova
                if (!$property) {
                    $property = new Property();
                }

                // dd($listing['Media']);

                if ($listing['Details']['PropertyType'] == 'Residential / Apartment' && $city) {
                    //dd($listing);
                    // Atualiza as informações da propriedade
                    $property->user_id = 1;
                    $property->status = 1;
                    $property->thumbnail_image = $listing['Media'][1];
                    $property->banner_image = $listing['Media'][1];
                    $property->code_property_api = $listing['ListingID'];
                    $property->value_condominio = $listing['Details']['PropertyAdministrationFee'];
                    $property->value_iptu = $listing['Details']['YearlyTax'];
                    $property->title = $listing['Title'];
                    $property->slug = $listing['Title'];
                    $property->property_type_id = 4;
                    $property->city_id = $city->id;
                    $property->address = $listing['Location']['Address'];
                    $property->phone = '';
                    $property->email = '';
                    $property->website = '';
                    $property->property_purpose_id = $listing['TransactionType'] == "For Sale" ? 1 : 2;
                    $property->price = $listing['TransactionType'] == 'For Sale' ? $listing['Details']['ListPrice'] : $listing['Details']['RentalPrice'];
                    $property->period = null;
                    $property->area = $listing['Details']['LivingArea'];
                    $property->number_of_unit = '';
                    $property->number_of_room = '';
                    $property->number_of_bedroom = $listing['Details']['Bedrooms'];
                    $property->number_of_bathroom = $listing['Details']['Bathrooms'];
                    $property->number_of_floor =  $listing['Details']['UnitFloor'];
                    $property->number_of_kitchen = '';
                    $property->number_of_parking = $listing['Details']['Garage'];;
                    $property->google_map_embed_code = '';
                    $property->description = $listing['Details']['Description'];
                    //$property->featured  = 0;
                    //$property->urgent_property = 0;
                    $property->top_property =  0;
                    $property->seo_title = $listing['Title'];
                    $property->seo_description = $listing['Title'];

                    // Salva a propriedade
                    $property->save();
                    foreach ($listing['Media'] as $image) {

                        if (!empty($image)) {
                            // Crie uma nova instância de PropertyImage
                            $propertyImage = new PropertyImage();

                            // Salve o link da imagem
                            $propertyImage->image = $image;

                            // Defina o ID da propriedade
                            $propertyImage->property_id = $property->id;

                            // Salve a instância do PropertyImage
                            $propertyImage->save();
                        }
                    }
                }
            }

            // Após o processo de importação bem-sucedido
            session()->flash('success', 'Carga de imóveis atualizada com sucesso.');


            // Redirecionar para a mesma página
            return redirect()->back();
        }
    }
}
