@extends('layout')
@section('title')
<title>{{ $property->seo_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $property->seo_description }}">
@endsection
@section('user-content')
<!-- Estilos CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-7GGDcu90Xfnb/8KOqmkBzjK8yqdNEDj31XTXllEdFGvUqXyrjPy6S3mtg0SpQIkW31tF6ZKQq9MCcO+ynZ0qfg==" crossorigin="anonymous" />

<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-IE0bC1S3KMBk8EuK6W/VOAfIbeGPTv4tTXO8MQwuvSNXqUyAOMXahd+f4O+Y+kbEqcB6GbZOLzuyYiOepBx+Dw==" crossorigin="anonymous"></script>

<!--===BREADCRUMB PART START====-->
<!--===BREADCRUMB PART END====-->
@php
$addressWithoutNumbers = preg_replace('/\d+/', '', $property->address);
@endphp



<!--=====PROPERTY DETAILD START=====-->
<section class="wsus__property_details mt_45 mb_20">
    <div class="container">
        <div class="row pro_det_slider">
            {{-- @foreach ($property->propertyImages as $imag_item)
            <div class="col-12">
                <div class="pro_det_slider_item">
                    <img src="{{ url($imag_item->image) }}" alt="property" class="img-fluid w-100">
        </div>
    </div>
    @endforeach --}}
    </div>



    <div class="row mt_40">
        <div class="col-xl-8 col-lg-7">
            <div class="wsus__property_det_content">
                <div class="row">
                    <div class="col-12">
                        <div class="wsus__single_details pb-sm-2 m-sm-5">
                            <div class="wsus__single_det_top d-flex justify-content-between " style="
                            max-width: 100%;
                            flex-wrap: wrap;
                        ">
                                <p>
                                    <span class="sale m-2">{{ $property->propertyPurpose->translated_custom_purpose }}</span>
                                    @if ($property->urgent_property==1)
                                    <span class="rent m-2">{{__('user.Urgent')}}</span>
                                    @endif
                                </p>

                                @if($property->value_iptu != 0)

                                <span class="tk m-2">IPTU: {{ $currency }}{{$property->value_iptu}}</span>
                                @endif
                                @if($property->value_condominio != 0)
                                <span class="tk m-2">Condominio: {{ $currency }} {{$property->value_condominio}}</span>
                                @endif


                                @if ($property->property_purpose_id==1)
                                <span class="tk m-2 ">{{ $currency }}{{ number_format( $property->price, 2, ',', '.') }}</span>

                                @elseif ($property->property_purpose_id==2)
                                <span class="tk m-2">{{ $currency }}{{ $property->price }} /
                                    @if ($property->period=='Daily')
                                    <span>{{__('user.Daily')}}</span>
                                    @elseif ($property->period=='Monthly')
                                    <span>{{__('user.Monthly')}}</span>
                                    @elseif ($property->period=='Yearly')
                                    <span>{{__('user.Yearly')}}</span>
                                    @endif
                                </span>
                                @endif

                            </div>
                            <h4>{{ $property->translated_title }}</h4>
                            <p class="my-2" style="color: #FF9800">Código do anuciante: {{ $property->code_property_api }}</p>
                            @php
                            $total_review=$property->reviews->where('status',1)->count();
                            if($total_review > 0){
                            $avg_sum=$property->reviews->where('status',1)->sum('avarage_rating');

                            $avg=$avg_sum/$total_review;
                            $intAvg=intval($avg);
                            $nextVal=$intAvg+1;
                            $reviewPoint=$intAvg;
                            $halfReview=false;
                            if($intAvg < $avg && $avg < $nextVal){ $reviewPoint=$intAvg + 0.5; $halfReview=true; } } @endphp <p>

                                @if($property->neighborhood != null)
                                {{ $addressWithoutNumbers . ' ,' . $property->neighborhood }}
                                @else
                                {{ $addressWithoutNumbers}}

                                @endif

                                @if($property->city)
                                , {{ $property->city->translated_name }}
                                @endif

                                </p>

                                <ul class="item d-flex flex-wrap mt-3">
                                    <li><i class="fal fa-bed"></i> {{ $property->number_of_bedroom }} {{__('user.Bed')}}</li>
                                    <li><i class="fal fa-shower"></i> {{ $property->number_of_bathroom }} {{__('user.Bath')}}</li>
                                    <li><i class="fal fa-draw-square"></i> {{ $property->area }} {{__('user.Sqft')}}</li>
                                </ul>
                                <ul class="list d-flex flex-wrap">
                                    @if ($property->is_featured==1)
                                    <li><a href="javascript:;"><i class="fas fa-check-circle"></i>{{__('user.Featured')}}</a></li>
                                    @endif
                                    <li><a href="javascript:;"><i class="far fa-eye"></i> {{ $property->views }}</a></li>

                                    <li><a href="{{ route('user.add.to.wishlist',$property->id) }}"><i class="fas fa-heart"></i> {{__('user.Add to Wishlist')}}</a></li>
                                </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="wsus__single_details details_future">
                            <h5>{{__('user.Details & Features')}}</h5>
                            <div class="details_futurr_single">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <table class="table">
                                            <tbody>

                                                <tr>
                                                    <th>{{__('user.Property Type')}}:</th>
                                                    <td>{{ $property->propertyType->type }}</td>
                                                </tr>


                                                <tr>
                                                    <th> {{__('user.Area')}}:</th>
                                                    <td>{{ $property->area }} {{__('user.Sqft')}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('user.Bedrooms')}}:</th>
                                                    <td>{{ $property->number_of_bedroom }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('user.Bathrooms')}}:</th>
                                                    <td>{{ $property->number_of_bathroom  }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-xl-6">
                                        <table class="table xs_sm_mb">
                                            <tbody>
                                                <tr>


                                                <tr>
                                                    <th>{{__('user.Kitchens')}}:</th>
                                                    <td>{{ $property->number_of_kitchen }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('user.Parking Place')}}:</th>
                                                    <td>{{ $property->number_of_parking }}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row p-5">
                        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                            <div class="swiper-wrapper">
                                @foreach ($property->propertyImages as $image)
                                <div class="swiper-slide">
                                    <img src="{{ url($image->image) }}" style="max-height: 400px; width: auto; height: auto;" />
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next" style="background-color: black; border-radius: 50%; width: 20px; height: 20px; line-height: 20px; font-size: 18px;">&gt;</div>
                            <div class="swiper-button-prev" style="background-color: black; border-radius: 50%; width: 20px; height: 20px; line-height: 20px; font-size: 18px;">&lt;</div>
                        </div>
                        <div thumbsSlider="" class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($property->propertyImages as $image)
                                <div class="swiper-slide" style="width: 100px; height: 100px;">
                                    <img src="{{ url($image->image) }}" style="max-height: 100%; max-width: 100%;" />
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row p-5">
                            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                                <div class="swiper-wrapper">
                                    @foreach ($property->propertyImages as $image)
                                  <div class="swiper-slide">
                                    <img src="{{ url($image->image) }}" />
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div thumbsSlider="" class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($property->propertyImages as $image)
                <div class="swiper-slide">
                    <img src="{{ url($image->image) }}" />
                </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
    </div> --}}




    <div class="col-12">
        <div class="wsus__single_details details_description">
            <h5>{{__('user.Description')}}</h5>
            {!! clean($property->translated_description) !!}

            @if ($property->file)
            <a href="{{ route('download-listing-file',$property->file) }}" class="common_btn mt_20">{{__('user.Download PDF')}}</a>
            @endif


        </div>
    </div>
    @if ($property->video_link)

    <div class="col-12">
        <div class="wsus__single_details details_videos pb_10">
            <h5>{{__('user.Property Video')}}</h5>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $property->video_link }}" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
    @endif

    @if ($property->propertyAminities->count() !=0)
    <div class="col-12">
        <div class="wsus__single_details details_aminities pb_10">
            <h5>{{__('user.Aminities')}}</h5>
            <ul class="d-flex flex-wrap">
                @foreach ($property->propertyAminities as $aminity_item)
                <li><i class="fal fa-check"></i> {{ $aminity_item->aminity->translated_aminity }}</li>
                @endforeach

            </ul>
        </div>
    </div>
    @endif

    @if ($property->propertyNearestLocations->count() !=0)
    <div class="col-12">
        <div class="wsus__single_details details_nearest_location pb_10">
            <h5>{{__('user.Nearest Place')}}</h5>
            <ul class="d-flex flex-wrap">
                @foreach ($property->propertyNearestLocations as $item)
                <li><span>{{ $item->nearestLocation->translated_location }}:</span> {{ $item->distance }}{{__('user.KM')}}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if ($property->google_map_embed_code)
    <div class="col-12">
        <div class="wsus__single_details details_map">
            {!! $property->google_map_embed_code !!}
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="wsus__share_blog">
            <p>{{__('user.Share')}}:</p>
            <ul>

                <li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ route('property.details', $property->slug) }}&t={{ $property->title }}"><i class="fab fa-facebook-f"></i></a></li>

                <li><a class="twitter" href="https://twitter.com/share?text={{ $property->title }}&url={{ route('property.details', $property->slug) }}"><i class="fab fa-twitter"></i></a></li>

                <li><a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('property.details', $property->slug) }}&title={{ $property->title }}"><i class="fab fa-linkedin-in"></i></a></li>

                <li><a class="pinterest" href="https://www.pinterest.com/pin/create/button/?description={{ $property->title }}&media=&url={{ route('property.details', $property->slug) }}"><i class="fab fa-pinterest-p"></i></a></li>

                <li>
                    <a href="mailto:?body={{ route('property.details', $property->slug) }}" class="fab fa-google" target="_blank"></a>
                </li>

            </ul>
        </div>
    </div>



    @php
    $total_review=$property->reviews->where('status',1)->count();
    if($total_review>0){
    $avg_sum=$property->reviews->where('status',1)->sum('avarage_rating');

    $service_sum=$property->reviews->where('status',1)->sum('service_rating');
    $service_avg=$service_sum/$total_review;
    $service_progress= ($service_avg*100)/5;

    $location_sum=$property->reviews->where('status',1)->sum('location_rating');
    $location_avg=$location_sum/$total_review;
    $location_progress= ($location_avg*100)/5;

    $money_sum=$property->reviews->where('status',1)->sum('money_rating');
    $money_avg=$money_sum/$total_review;
    $money_progress= ($money_avg*100)/5;


    $clean_sum=$property->reviews->where('status',1)->sum('clean_rating');
    $clean_avg=$clean_sum/$total_review;
    $clean_progress= ($clean_avg*100)/5;



    $avg=$avg_sum/$total_review;
    $intAvg=intval($avg);
    $nextVal=$intAvg+1;
    $reviewPoint=$intAvg;
    $halfReview=false;
    if($intAvg < $avg && $avg < $nextVal){ $reviewPoint=$intAvg + 0.5; $halfReview=true; } } @endphp </div>
        </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="wsus__property_sidebar">
                <div class="wsus__sidebar_message">


                    @if ($property->user_type==1)
                    <div class="wsus__sidebar_message_top">
                        <div class="wsus__sidebar_message_top">
                            @if($property->admin)
                            <img src="{{ $property->admin->image ? url($property->admin->image) :  url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                            @else


                            <img src="{{ url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                            @endif
                            {{-- <h3 >  {{ $property->admin->name }}</h3> --}}
                            {{-- <div class="pt-5">
                                Código do imóvel
                                <h3>{{ $property->code_property_api  }}</h3>
                        </div> --}}


                    </div>
                    @else

                    <div class="wsus__sidebar_message_top">
                        <img src="{{ $property->user->image ? url($property->user->image) : url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                        <a class="name" href="{{ route('agent.show',['user_type' => '2','user_name'=>$property->user->slug]) }}">{{ $property->user->name }}</a>
                        <a class="mail" href="mailto:{{ $property->user->email }}"><i class="fal fa-envelope-open"></i> {{ $property->user->email }}</a>
                        <div class="pt-5">
                            Código do imóvel
                            <h3>{{ $property->code_property_api  }}</h3>
                        </div>
                    </div>
                    @endif
                    <form id="listingAuthContactForm" class="p-2">
                        @csrf
                        <input type="hidden" name="property_url" value="{{ url()->current() }}">
                        <div class="wsus__sidebar_input">
                            <label>{{__('user.Name')}}</label>
                            <input type="text" name="name">
                        </div>
                        <div class="wsus__sidebar_input">
                            <label>{{__('user.Email')}}</label>
                            <input type="email" name="email">
                        </div>
                        <div class="wsus__sidebar_input">
                            <label>{{__('user.Phone')}}</label>
                            <input type="text" name="phone">
                        </div>
                        <div class="wsus__sidebar_input">

                            <input type="hidden" name="user_type" value="{{ $property->user_type }}">
                            <input type="hidden" name="property_phone" value="{{ $property->phone }}">
                            @if ($property->user_type==1)
                            <input type="hidden" name="admin_id" value="{{ $property->admin_id }}">
                            <input type="hidden" name="user_phone" value="{{ $property->admin->phone }}">
                            @else
                            <input type="hidden" name="user_id" value="{{ $property->user_id }}">
                            <input type="hidden" name="user_phone" value="{{ $property->user->phone }}">
                            @endif


                            @if($recaptcha_setting->status==1)
                            <p class="g-recaptcha mt-3" data-sitekey="{{ $recaptcha_setting->site_key }}"></p>
                            @endif

                            <button type="submit" id="listingAuthorContctBtn" class="common_btn"><i id="listcontact-spinner" class="loading-icon fa fa-spin fa-spinner d-none mr-5"></i> {{__('user.Send Message')}}</button>
                        </div>

                    </form>
                </div>

                @php
                $isActivePropertyQty=0;
                foreach ($similarProperties as $value) {
                if($value->expired_date==null){
                $isActivePropertyQty +=1;
                }else if($value->expired_date >= date('Y-m-d')){
                $isActivePropertyQty +=1;
                }
                }
                @endphp

                @if ($isActivePropertyQty !=0)
                <div class="row ">
                    @foreach ($similarProperties as $similar_item)
                    @if ($similar_item->expired_date==null)
                    <div class="col-xl-12 col-md-6 col-lg-12">
                        <div class="wsus__single_property">
                            <div class="wsus__single_property_img">
                                <img src="{{ asset($similar_item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">

                                @if ($similar_item->property_purpose_id==1)
                                <span class="sale">{{ $similar_item->propertyPurpose->translated_custom_purpose }}</span>

                                @elseif($similar_item->property_purpose_id==2)
                                <span class="sale">{{ $similar_item->propertyPurpose->translated_custom_purpose }}</span>
                                @endif

                                @if ($similar_item->urgent_property==1)
                                <span class="rent">{{__('user.Urgent')}}</span>
                                @endif

                            </div>
                            <div class="wsus__single_property_text">
                                @if ($similar_item->property_purpose_id==1)
                                <span class="tk">{{ $currency }}{{ $similar_item->price }}</span>
                                @elseif ($similar_item->property_purpose_id==2)
                                <span class="tk">{{ $currency }}{{ $similar_item->price }} /
                                    @if ($similar_item->period=='Daily')
                                    <span>{{__('user.Daily')}}</span>
                                    @elseif ($similar_item->period=='Monthly')
                                    <span>{{__('user.Monthly')}}</span>
                                    @elseif ($similar_item->period=='Yearly')
                                    <span>{{__('user.Yearly')}}</span>
                                    @endif
                                </span>
                                @endif

                                <a href="{{ route('property.details',$similar_item->slug) }}" class="title w-100">{{ $similar_item->translated_title }}</a>
                                <ul class="d-flex flex-wrap justify-content-between">
                                    <li><i class="fal fa-bed"></i> {{ $similar_item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                    <li><i class="fal fa-shower"></i> {{ $similar_item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                    <li><i class="fal fa-draw-square"></i> {{ $similar_item->area }} {{__('user.Sqft')}}</li>
                                </ul>
                                <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                    <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $similar_item->propertyType->id]) }}" class="category">{{ $similar_item->propertyType->translated_type }}</a>

                                    @php
                                    $total_review=$similar_item->reviews->where('status',1)->count();
                                    if($total_review > 0){
                                    $avg_sum=$similar_item->reviews->where('status',1)->sum('avarage_rating');

                                    $avg=$avg_sum/$total_review;
                                    $intAvg=intval($avg);
                                    $nextVal=$intAvg+1;
                                    $reviewPoint=$intAvg;
                                    $halfReview=false;
                                    if($intAvg < $avg && $avg < $nextVal){ $reviewPoint=$intAvg + 0.5; $halfReview=true; } } @endphp </div>
                                </div>
                            </div>
                        </div>
                        @elseif($similar_item->expired_date >= date('Y-m-d'))
                        <div class="col-xl-12 col-md-6 col-lg-12">
                            <div class="wsus__single_property">
                                <div class="wsus__single_property_img">
                                    <img src="{{ asset($similar_item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">

                                    @if ($similar_item->property_purpose_id==1)
                                    <span class="sale">{{ $similar_item->propertyPurpose->translated_custom_purpose }}</span>

                                    @elseif($similar_item->property_purpose_id==2)
                                    <span class="sale">{{ $similar_item->propertyPurpose->translated_custom_purpose }}</span>
                                    @endif

                                    @if ($similar_item->urgent_property==1)
                                    <span class="rent">{{__('user.Urgent')}}</span>
                                    @endif

                                </div>
                                <div class="wsus__single_property_text">
                                    @if ($similar_item->property_purpose_id==1)
                                    <span class="tk">{{ $currency }}{{ $similar_item->price }}</span>
                                    @elseif ($similar_item->property_purpose_id==2)
                                    <span class="tk">{{ $currency }}{{ $similar_item->price }} /
                                        @if ($similar_item->period=='Daily')
                                        <span>{{__('user.Daily')}}</span>
                                        @elseif ($similar_item->period=='Monthly')
                                        <span>{{__('user.Monthly')}}</span>
                                        @elseif ($similar_item->period=='Yearly')
                                        <span>{{__('user.Yearly')}}</span>
                                        @endif
                                    </span>
                                    @endif

                                    <a href="{{ route('property.details',$similar_item->slug) }}" class="title w-100">{{ $similar_item->translated_title }}</a>
                                    <ul class="d-flex flex-wrap justify-content-between">
                                        <li><i class="fal fa-bed"></i> {{ $similar_item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                        <li><i class="fal fa-shower"></i> {{ $similar_item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                        <li><i class="fal fa-draw-square"></i> {{ $similar_item->area }} {{__('user.Sqft')}}</li>
                                    </ul>
                                    <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                        <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $similar_item->propertyType->id]) }}" class="category">{{ $similar_item->propertyType->type }}</a>


                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
</section>
<!--=====PROPERTY DETAILD  END=====-->


<script>
    (function($) {
            "use strict";
            $(document).ready(function() {
                        $("#listingAuthorContctBtn").on('click', function(e) {
                                    e.preventDefault();

                                    $("#listcontact-spinner").removeClass('d-none')
                                    $("#listingAuthorContctBtn").addClass('custom-opacity')
                                    $("#listingAuthorContctBtn").attr('disabled', true);
                                    $("#listingAuthorContctBtn").removeClass('site-btn-effect')

                                    $.ajax({
                                                url: "{{ route('user.contact.message') }}",
                                                type: "post",
                                                data: $('#listingAuthContactForm').serialize(),
                                                success: function(response) {
                                                    if (response.success) {


                                                        $("#listingAuthContactForm").trigger("reset");
                                                        toastr.success(response.success)
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    }
                                                    if (response.error) {
                                                        toastr.error(response.error)
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')

                                                    }

                                                },
                                                error: function(response) {
                                                    if (response.responseJSON.errors.name) {
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')

                                                        toastr.error(response.responseJSON.errors.name[0])

                                                    }

                                                    if (response.responseJSON.errors.email) {
                                                        toastr.error(response.responseJSON.errors.email[0])
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')

                                                    }

                                                    if (response.responseJSON.errors.phone) {
                                                        toastr.error(response.responseJSON.errors.phone[0])
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    }

                                                    if (response.responseJSON.errors.subject) {
                                                        toastr.error(response.responseJSON.errors.subject[0])
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    }

                                                    if (response.responseJSON.errors.message) {
                                                        toastr.error(response.responseJSON.errors.message[0])
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    } else {
                                                        toastr.error('Please Complete the recaptcha to submit the form')
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    }

                                                    if (response.responseJSON.errors.g - recaptcha) {
                                                        toastr.error('Please Complete the recaptcha to submit the form')
                                                        $("#listcontact-spinner").addClass('d-none')
                                                        $("#listingAuthorContctBtn").removeClass('custom-opacity')
                                                        $("#listingAuthorContctBtn").attr('disabled', false);
                                                        $("#listingAuthorContctBtn").addClass('site-btn-effect')
                                                    }


                                                },
                                                complete: function() {
                                                        // var userPhoneInput = $("input[name='user_phone']").val();
                                                        // var userPhone = "+55" + userPhoneInput.replace(/\D/g,''); // Remove todos os não dígitos

                                                        // var propertyUrl = $("input[name='property_url']").val();
                                                        // var message = "Olá, tenho interesse nesse imóvel! " + propertyUrl;
                                                        // var whatsappUrl = "https://api.whatsapp.com/send?phone=" + userPhone + "&text=" + encodeURIComponent(message);

                                                        // window.location.href = whatsappUrl;

                                                        // // Limpe o spinner e habilite o botão após o envio
                                                        // $("#listcontact-spinner").addClass('d-none');
                                                        // $("#listingAuthorContctBtn").removeClass('custom-opacity').attr('disabled', false).addClass('site-btn-effect');
                                                        // }
                                                        // });


                                                        // })
                                                        // });

                                                        // })(jQuery);


                                                        function serviceReview(rating) {

                                                            $("#service_rating").val(rating);
                                                            $(".service_rat").each(function() {
                                                                var service_rat = $(this).data('service_rating')
                                                                if (service_rat > rating) {
                                                                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                                                                } else {
                                                                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                                                                }
                                                            })

                                                            var service_rating = $("#service_rating").val();
                                                            var location_rating = $("#location_rating").val();
                                                            var money_rating = $("#money_rating").val();
                                                            var clean_rating = $("#clean_rating").val();
                                                            var avg = (service_rating * 1) + (location_rating * 1) + (money_rating * 1) + (clean_rating * 1);
                                                            avg = avg / 4;
                                                            $("#avarage_rating").val(avg);
                                                            $("#avg_rating").text(avg)
                                                        }

                                                        function locationReview(rating) {

                                                            $("#location_rating").val(rating);
                                                            $(".location_rat").each(function() {
                                                                var location_rat = $(this).data('location_rating')
                                                                if (location_rat > rating) {
                                                                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                                                                } else {
                                                                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                                                                }

                                                            })


                                                            var service_rating = $("#service_rating").val();
                                                            var location_rating = $("#location_rating").val();
                                                            var money_rating = $("#money_rating").val();
                                                            var clean_rating = $("#clean_rating").val();
                                                            var avg = (service_rating * 1) + (location_rating * 1) + (money_rating * 1) + (clean_rating * 1);
                                                            avg = avg / 4;
                                                            $("#avarage_rating").val(avg);
                                                            $("#avg_rating").text(avg)

                                                        }

                                                        function moneyReview(rating) {
                                                            $("#money_rating").val(rating);
                                                            $(".money_rat").each(function() {
                                                                var money_rat = $(this).data('money_rating')
                                                                if (money_rat > rating) {
                                                                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                                                                } else {
                                                                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                                                                }

                                                            })

                                                            var service_rating = $("#service_rating").val();
                                                            var location_rating = $("#location_rating").val();
                                                            var money_rating = $("#money_rating").val();
                                                            var clean_rating = $("#clean_rating").val();
                                                            var avg = (service_rating * 1) + (location_rating * 1) + (money_rating * 1) + (clean_rating * 1);
                                                            avg = avg / 4;
                                                            $("#avarage_rating").val(avg);
                                                            $("#avg_rating").text(avg)

                                                        }

                                                        function cleanReview(rating) {

                                                            $("#clean_rating").val(rating);
                                                            $(".clean_rat").each(function() {
                                                                var clean_rat = $(this).data('clean_rating')
                                                                if (clean_rat > rating) {
                                                                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                                                                } else {
                                                                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                                                                }

                                                            })
                                                            var service_rating = $("#service_rating").val();
                                                            var location_rating = $("#location_rating").val();
                                                            var money_rating = $("#money_rating").val();
                                                            var clean_rating = $("#clean_rating").val();
                                                            var avg = (service_rating * 1) + (location_rating * 1) + (money_rating * 1) + (clean_rating * 1);
                                                            avg = avg / 4;
                                                            $("#avarage_rating").val(avg);
                                                            $("#avg_rating").text(avg)
                                                        }
</script>
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            $("#listingAuthorContctBtn").on('click', function(e) {
                e.preventDefault();

                $("#listcontact-spinner").removeClass('d-none');
                $("#listingAuthorContctBtn").addClass('custom-opacity').attr('disabled', true);
                $("#listingAuthorContctBtn").removeClass('site-btn-effect');

                // $.ajax({
                //     url: "{{ route('user.contact.message') }}",
                //     type: "post",
                //     data: $('#listingAuthContactForm').serialize(),
                //     success: function(response) {
                //         if (response.success) {
                //             $("#listingAuthContactForm").trigger("reset");
                //             toastr.success(response.success);
                //         }
                //         if (response.error) {
                //             toastr.error(response.error);
                //         }
                //     },
                //     error: function(response) {
                //         if (response.responseJSON.errors) {
                //             var errorMsg = '';
                //             $.each(response.responseJSON.errors, function(index, value) {
                //                 errorMsg += value[0] + '<br>';
                //             });
                //             toastr.error(errorMsg);
                //         }
                //     },
                //     complete: function() {
                //         var userPhoneInput = $("input[name='user_phone']").val();
                //         var userPhone = "+55" + userPhoneInput.replace(/\D/g,''); // Remove todos os não dígitos

                //         var propertyUrl = $("input[name='property_url']").val();
                //         var message = "Olá, tenho interesse nesse imóvel! " + propertyUrl;
                //         var whatsappUrl = "https://api.whatsapp.com/send?phone=" + userPhone + "&text=" + encodeURIComponent(message);

                //         // Redirecionar para o WhatsApp
                //         window.location.href = whatsappUrl;

                //         // Limpar o spinner e habilitar o botão após o envio
                //         $("#listcontact-spinner").addClass('d-none');
                //         $("#listingAuthorContctBtn").removeClass('custom-opacity').attr('disabled', false).addClass('site-btn-effect');
                //     }
                // });
                $.ajax({
                    url: "{{ route('user.contact.message') }}",
                    type: "post",
                    data: $('#listingAuthContactForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            $("#listingAuthContactForm").trigger("reset");
                            toastr.success(response.success);
                        }
                        if (response.error) {
                            toastr.error(response.error);
                        }
                        setTimeout(function() {
                            window.location.reload(); // Recarrega a página atual
                        }, 1000);
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            var errorMsg = '';
                            $.each(response.responseJSON.errors, function(index, value) {
                                errorMsg += value[0] + '<br>';
                            });
                            toastr.error(errorMsg);
                        }
                        setTimeout(function() {
                            window.location.reload(); // Recarrega a página atual
                        }, 1000);
                    }
                    // complete: function() {
                    //     var userPhoneInput = $("input[name='user_phone']").val();
                    //     var userPhone = "+55" + userPhoneInput.replace(/\D/g,''); // Remove todos os não dígitos

                    //     var propertyUrl = $("input[name='property_url']").val();
                    //     var message = "Olá, tenho interesse nesse imóvel! " + propertyUrl;
                    //     var whatsappUrl = "https://api.whatsapp.com/send?phone=" + userPhone + "&text=" + encodeURIComponent(message);

                    //     // Redirecionar para o WhatsApp
                    //     window.location.href = whatsappUrl;

                    //     // Limpar o spinner e habilitar o botão após o envio
                    //     $("#listcontact-spinner").addClass('d-none');
                    //     $("#listingAuthorContctBtn").removeClass('custom-opacity').attr('disabled', false).addClass('site-btn-effect');
                    // }
                });
            });
        });


    })(jQuery);
</script>



<style>
    .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper {
        width: 100%;
        height: 300px;
        margin-left: auto;
        margin-right: auto;
    }

    .swiper-slide {
        background-size: cover;
        background-position: center;
    }

    .mySwiper2 {
        height: 80%;
        width: 100%;
    }

    .mySwiper {
        height: 20%;
        box-sizing: border-box;
        padding: 10px 0;
    }

    .mySwiper .swiper-slide {
        width: 25%;
        height: 100%;
        opacity: 0.4;
    }

    .mySwiper .swiper-slide-thumb-active {
        opacity: 1;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .swiper-button-next::after {
        content: '' !important;
    }

    .swiper-button-prev::after {
        content: '' !important;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        loop: true, // Adiciona loop
        autoplay: { // Adiciona autoplay
            delay: 3000, // Tempo de atraso em milissegundos
            disableOnInteraction: false // Continua após interação
        }
    });
    var swiper2 = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
        loop: true, // Adiciona loop
        autoplay: { // Adiciona autoplay
            delay: 3000, // Tempo de atraso em milissegundos
            disableOnInteraction: false // Continua após interação
        }
    });
</script>
@endsection