@extends('layout')
@section('title')
    <title>{{ $property->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $property->seo_description }}">
@endsection
@section('user-content')
     <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ url($property->banner_image) }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Our Property')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Our property')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
  </section>
  <!--===BREADCRUMB PART END====-->


  <!--=====PROPERTY DETAILD START=====-->
  <section class="wsus__property_details mt_45 mb_20">
    <div class="container">
      <div class="row pro_det_slider">
        @foreach ($property->propertyImages as $imag_item)
            <div class="col-12">
                <div class="pro_det_slider_item">
                    <img src="{{ url($imag_item->image) }}" alt="property" class="img-fluid w-100">
                </div>
            </div>
        @endforeach
      </div>
      <div class="row mt_40">
        <div class="col-xl-8 col-lg-7">
            <div class="wsus__property_det_content">
                    <div class="row">
                        <div class="col-12">
                            <div class="wsus__single_details pb-sm-2">
                            <div class="wsus__single_det_top d-flex justify-content-between">
                                <p>
                                    <span class="sale">{{ $property->propertyPurpose->translated_custom_purpose }}</span>
                                    @if ($property->urgent_property==1)
                                        <span class="rent">{{__('user.Urgent')}}</span>
                                    @endif
                                </p>


                                @if ($property->property_purpose_id==1)
                                    <span class="tk">{{ $currency }}{{ $property->price }}</span>
                                @elseif ($property->property_purpose_id==2)
                                    <span class="tk">{{ $currency }}{{ $property->price }} /
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

                            @php
                                $total_review=$property->reviews->where('status',1)->count();
                                if($total_review > 0){
                                    $avg_sum=$property->reviews->where('status',1)->sum('avarage_rating');

                                    $avg=$avg_sum/$total_review;
                                    $intAvg=intval($avg);
                                    $nextVal=$intAvg+1;
                                    $reviewPoint=$intAvg;
                                    $halfReview=false;
                                    if($intAvg < $avg && $avg < $nextVal){
                                        $reviewPoint= $intAvg + 0.5;
                                        $halfReview=true;
                                    }
                                }
                            @endphp
                            @if ($total_review > 0)
                            <p class="wsus__pro_det_top_rating">
                                @for ($i = 1; $i <=5; $i++)
                                    @if ($i <= $reviewPoint)
                                        <i class="fas fa-star"></i>
                                    @elseif ($i> $reviewPoint )
                                        @if ($halfReview==true)
                                        <i class="fas fa-star-half-alt"></i>
                                            @php
                                                $halfReview=false
                                            @endphp
                                        @else
                                        <i class="fal fa-star"></i>
                                        @endif
                                    @endif
                                @endfor
                                <span>{{ sprintf("%.1f", $reviewPoint) }}</span>
                            </p>
                            @else
                            <p class="wsus__pro_det_top_rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span>0.0</span>
                            </p>

                            @endif
                            <p>

                            {{ $property->address }}
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
                                <li><a href="#addReviewSection"><i class="fal fa-comment-alt-dots"></i> {{__('user.Add Review')}}</a></li>
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
                                            <th>{{__('user.Property ID')}}:</th>
                                            <td>{{ $property->property_search_id }}</td>
                                        </tr>

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
                                        <td>{{  $property->number_of_bathroom  }}</td>
                                        </tr>

                                    </tbody>
                                    </table>
                                </div>
                                <div class="col-xl-6">
                                    <table class="table xs_sm_mb">
                                    <tbody>
                                        <tr>
                                            <th>{{__('user.Rooms')}}:</th>
                                            <td>{{ $property->number_of_room }}</td>
                                        </tr>

                                        <tr>
                                        <th>{{__('user.Units')}}:</th>
                                        <td>{{ $property->number_of_unit }}</td>
                                        </tr>
                                        <tr>
                                        <th> {{__('user.Floors')}}:</th>
                                        <td>{{ $property->number_of_floor }}</td>
                                        </tr>
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
                                    <li><span>{{ $item->nearestLocation->translated_location }}:</span>  {{ $item->distance }}{{__('user.KM')}}</li>
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
                                if($intAvg < $avg && $avg < $nextVal){
                                    $reviewPoint= $intAvg + 0.5;
                                    $halfReview=true;
                                }

                            }
                        @endphp

                        @if ($total_review >0 )
                        <div class="col-12">
                            <div class="wsus__total_review wsus__single_details">
                                <h5>{{ $total_review }} {{__('user.Review')}}</h5>
                                @foreach ($propertyReviews as $review_item)
                                    <div class="wsus__single_comment">
                                        <div class="wsus__comm_img">
                                            <img src="{{ $review_item->user->image ? url($review_item->user->image) : url($default_image) }}" alt="comment img" class="img-fluid img-thumbnail">
                                        </div>
                                        <div class="wsus__comm_text">
                                            @php
                                                $avg=$review_item->avarage_rating;
                                                $intAvg=intval($avg);
                                                $nextVal=$intAvg+1;
                                                $reviewPoint=$intAvg;
                                                $halfReview=false;
                                                if($intAvg < $avg && $avg < $nextVal){
                                                    $reviewPoint= $intAvg + 0.5;
                                                    $halfReview=true;
                                                }
                                            @endphp

                                            <p class="wsus__rev_star">
                                                @for ($i = 1; $i <=5; $i++)
                                                    @if ($i <= $reviewPoint)
                                                        <i class="fas fa-star"></i>
                                                    @elseif ($i> $reviewPoint )
                                                        @if ($halfReview==true)
                                                        <i class="fas fa-star-half-alt"></i>
                                                            @php
                                                                $halfReview=false
                                                            @endphp
                                                        @else
                                                        <i class="fal fa-star"></i>
                                                        @endif
                                                    @endif
                                                @endfor
                                            </p>

                                            <h4>{{ $review_item->user->name }}</h4>
                                            <span>{{ $review_item->created_at->format('d M Y') }}</span>
                                            <p>{{ $review_item->comment }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $propertyReviews->links('custom_paginator') }}
                            </div>
                        </div>
                        @endif
                        <div class="col-12" id="addReviewSection">
                            <div class="wsus__single_details details_review">
                            <h5>{{__('user.Write A Review')}}</h5>
                            <form action="{{ route('user.store-review') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-7 col-md-6 col-lg-12">
                                        <div class="wsus__select_review">
                                            <ul>
                                            <li><span>{{__('user.Service')}} :</span>
                                                <i class="service_rat fas fa-star" data-service_rating="1" onclick="serviceReview(1)"></i>
                                                <i class="service_rat fas fa-star" data-service_rating="2" onclick="serviceReview(2)"></i>
                                                <i class="service_rat fas fa-star" data-service_rating="3" onclick="serviceReview(3)"></i>
                                                <i class="service_rat fas fa-star" data-service_rating="4" onclick="serviceReview(4)"></i>
                                                <i class="service_rat fas fa-star" data-service_rating="5" onclick="serviceReview(5)"></i>
                                            </li>
                                            <li><span>{{__('user.Location')}} :</span>
                                                <i class="location_rat fas fa-star" data-location_rating="1" onclick="locationReview(1)"></i>
                                                <i class="location_rat fas fa-star" data-location_rating="2" onclick="locationReview(2)"></i>
                                                <i class="location_rat fas fa-star" data-location_rating="3" onclick="locationReview(3)"></i>
                                                <i class="location_rat fas fa-star" data-location_rating="4" onclick="locationReview(4)"></i>
                                                <i class="location_rat fas fa-star" data-location_rating="5" onclick="locationReview(5)"></i>
                                            </li>
                                            <li><span>{{__('user.Value for Money')}} :</span>
                                                <i class="money_rat fas fa-star" data-money_rating="1" onclick="moneyReview(1)"></i>
                                                <i class="money_rat fas fa-star" data-money_rating="2" onclick="moneyReview(2)"></i>
                                                <i class="money_rat fas fa-star" data-money_rating="3" onclick="moneyReview(3)"></i>
                                                <i class="money_rat fas fa-star" data-money_rating="4" onclick="moneyReview(4)"></i>
                                                <i class="money_rat fas fa-star" data-money_rating="5" onclick="moneyReview(5)"></i>
                                            </li>
                                            <li><span>{{__('user.Cleanliness')}}  :</span>
                                                <i class="clean_rat fas fa-star" data-clean_rating="1" onclick="cleanReview(1)"></i>
                                                <i class="clean_rat fas fa-star" data-clean_rating="2" onclick="cleanReview(2)"></i>
                                                <i class="clean_rat fas fa-star" data-clean_rating="3" onclick="cleanReview(3)"></i>
                                                <i class="clean_rat fas fa-star" data-clean_rating="4" onclick="cleanReview(4)"></i>
                                                <i class="clean_rat fas fa-star" data-clean_rating="5" onclick="cleanReview(5)"></i>
                                            </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-md-6 col-lg-12">
                                        <div class="wsus__total_rating">
                                            <h3 id="avg_rating">5</h3>
                                            <span>{{__('user.Out Of 5.0')}}</span>
                                            <p>{{__('user.Average Rating')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <textarea cols="3" rows="5" placeholder="{{__('user.Comment')}}"  name="comment"></textarea>

                                        @if($recaptcha_setting->status==1)
                                        <p class="g-recaptcha mb-3 mt-3" data-sitekey="{{ $recaptcha_setting->site_key }}"></p>
                                        @endif

                                        <input type="hidden" name="service_rating" id="service_rating" value="5">
                                        <input type="hidden" name="location_rating" id="location_rating" value="5">
                                        <input type="hidden" name="money_rating" id="money_rating" value="5">
                                        <input type="hidden" name="clean_rating" id="clean_rating" value="5">
                                        <input type="hidden" name="avarage_rating" id="avarage_rating" value="5">
                                        <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}">

                                        @auth('web')
                                            @php
                                                $activeUser=Auth::guard('web')->user();
                                            @endphp
                                            @if ($activeUser->id !=$property->user_id)
                                            <button type="submit" class="common_btn">{{__('user.Submit')}}</button>
                                            @endif
                                        @else

                                        <p class="worning"><a href="{{ route('login') }}" class="text-danger">{{__('user.Please Login To Write Review.')}}</a></p>

                                        @endauth

                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
          <div class="wsus__property_sidebar" id="sticky_sidebar">
            <div class="wsus__sidebar_message">


                @if ($property->user_type==1)
                    <div class="wsus__sidebar_message_top">
                        <div class="wsus__sidebar_message_top">
                            @if($property->admin)

                            <img src="{{ $property->admin->image ? url($property->admin->image) :  url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                            @else
                             <img src="{{ url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                            @endif


                             <a class="name" href="{{ route('agent.show',['user_type' => '1','user_name'=>$property->admin->slug]) }}">{{ $property->admin->name }}</a>
                             <a class="mail" href="mailto:{{ $property->admin->email }}"><i class="fal fa-envelope-open"></i> {{ $property->admin->email }}</a>
                    </div>
                @else
                    <div class="wsus__sidebar_message_top">
                        <img src="{{ $property->user->image ? url($property->user->image) : url($default_image) }}" alt="images" class="img-fluid img-thumbnail">
                        <a class="name" href="{{ route('agent.show',['user_type' => '2','user_name'=>$property->user->slug]) }}">{{ $property->user->name }}</a>
                        <a class="mail" href="mailto:{{ $property->user->email }}"><i class="fal fa-envelope-open"></i> {{ $property->user->email }}</a>
                    </div>
                @endif
                <form id="listingAuthContactForm">
                    @csrf
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
                        <label>{{__('user.Subject')}}</label>
                        <input type="text" name="subject">
                    </div>
                    <div class="wsus__sidebar_input">
                        <label>{{__('user.Description')}}</label>
                        <textarea cols="3" rows="3" name="message"></textarea>
                        <input type="hidden" name="user_type" value="{{ $property->user_type }}">
                        @if ($property->user_type==1)
                        <input type="hidden" name="admin_id" value="{{ $property->admin_id }}">
                        @else
                        <input type="hidden" name="user_id" value="{{ $property->user_id }}">
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
            <div class="row">
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
                                        if($intAvg < $avg && $avg < $nextVal){
                                            $reviewPoint= $intAvg + 0.5;
                                            $halfReview=true;
                                        }
                                    }
                                @endphp

                                @if ($total_review > 0)
                                    <span class="rating">{{ sprintf("%.1f", $reviewPoint) }}

                                    @for ($i = 1; $i <=5; $i++)
                                        @if ($i <= $reviewPoint)
                                            <i class="fas fa-star"></i>
                                        @elseif ($i> $reviewPoint )
                                            @if ($halfReview==true)
                                            <i class="fas fa-star-half-alt"></i>
                                                @php
                                                    $halfReview=false
                                                @endphp
                                            @else
                                            <i class="fal fa-star"></i>
                                            @endif
                                        @endif
                                    @endfor
                                    </span>
                                @else
                                    <span class="rating">0.0
                                        @for ($i = 1; $i <=5; $i++)
                                        <i class="fal fa-star"></i>
                                        @endfor
                                    </span>
                                @endif
                                </div>
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

                                @php
                                    $total_review=$similar_item->reviews->where('status',1)->count();
                                    if($total_review > 0){
                                        $avg_sum=$similar_item->reviews->where('status',1)->sum('avarage_rating');

                                        $avg=$avg_sum/$total_review;
                                        $intAvg=intval($avg);
                                        $nextVal=$intAvg+1;
                                        $reviewPoint=$intAvg;
                                        $halfReview=false;
                                        if($intAvg < $avg && $avg < $nextVal){
                                            $reviewPoint= $intAvg + 0.5;
                                            $halfReview=true;
                                        }
                                    }
                                @endphp

                                @if ($total_review > 0)
                                    <span class="rating">{{ sprintf("%.1f", $reviewPoint) }}

                                    @for ($i = 1; $i <=5; $i++)
                                        @if ($i <= $reviewPoint)
                                            <i class="fas fa-star"></i>
                                        @elseif ($i> $reviewPoint )
                                            @if ($halfReview==true)
                                            <i class="fas fa-star-half-alt"></i>
                                                @php
                                                    $halfReview=false
                                                @endphp
                                            @else
                                            <i class="fal fa-star"></i>
                                            @endif
                                        @endif
                                    @endfor
                                    </span>
                                @else
                                    <span class="rating">0.0
                                        @for ($i = 1; $i <=5; $i++)
                                        <i class="fal fa-star"></i>
                                        @endfor
                                    </span>
                                @endif
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
        $(document).ready(function () {
            $("#listingAuthorContctBtn").on('click',function(e) {
                e.preventDefault();

                $("#listcontact-spinner").removeClass('d-none')
                $("#listingAuthorContctBtn").addClass('custom-opacity')
                $("#listingAuthorContctBtn").attr('disabled',true);
                $("#listingAuthorContctBtn").removeClass('site-btn-effect')

                $.ajax({
                    url: "{{ route('user.contact.message') }}",
                    type:"post",
                    data:$('#listingAuthContactForm').serialize(),
                    success:function(response){
                        if(response.success){
                            $("#listingAuthContactForm").trigger("reset");
                            toastr.success(response.success)
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }
                        if(response.error){
                            toastr.error(response.error)
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')

                        }
                    },
                    error:function(response){
                        if(response.responseJSON.errors.name){
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')

                            toastr.error(response.responseJSON.errors.name[0])

                        }

                        if(response.responseJSON.errors.email){
                            toastr.error(response.responseJSON.errors.email[0])
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')

                        }

                        if(response.responseJSON.errors.phone){
                            toastr.error(response.responseJSON.errors.phone[0])
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }

                        if(response.responseJSON.errors.subject){
                            toastr.error(response.responseJSON.errors.subject[0])
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }

                        if(response.responseJSON.errors.message){
                            toastr.error(response.responseJSON.errors.message[0])
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }else{
                            toastr.error('Please Complete the recaptcha to submit the form')
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }

                        if(response.responseJSON.errors.g-recaptcha){
                            toastr.error('Please Complete the recaptcha to submit the form')
                            $("#listcontact-spinner").addClass('d-none')
                            $("#listingAuthorContctBtn").removeClass('custom-opacity')
                            $("#listingAuthorContctBtn").attr('disabled',false);
                            $("#listingAuthorContctBtn").addClass('site-btn-effect')
                        }


                    }

                });


            })
        });

        })(jQuery);


        function serviceReview(rating){

            $("#service_rating").val(rating);
            $(".service_rat").each(function(){
                var service_rat=$(this).data('service_rating')
                if(service_rat > rating){
                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                }else{
                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                }
            })

            var service_rating=$("#service_rating").val();
            var location_rating=$("#location_rating").val();
            var money_rating=$("#money_rating").val();
            var clean_rating=$("#clean_rating").val();
            var avg= (service_rating * 1) + (location_rating*1) + (money_rating*1) + (clean_rating*1);
            avg= avg/4;
            $("#avarage_rating").val(avg);
            $("#avg_rating").text(avg)
        }

        function locationReview(rating){

            $("#location_rating").val(rating);
            $(".location_rat").each(function(){
                var location_rat=$(this).data('location_rating')
                if(location_rat > rating){
                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                }else{
                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                }

            })


            var service_rating=$("#service_rating").val();
            var location_rating=$("#location_rating").val();
            var money_rating=$("#money_rating").val();
            var clean_rating=$("#clean_rating").val();
            var avg= (service_rating * 1) + (location_rating*1) + (money_rating*1) + (clean_rating*1);
            avg= avg/4;
            $("#avarage_rating").val(avg);
            $("#avg_rating").text(avg)

        }

        function moneyReview(rating){
            $("#money_rating").val(rating);
            $(".money_rat").each(function(){
                var money_rat=$(this).data('money_rating')
                if(money_rat > rating){
                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                }else{
                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                }

            })

            var service_rating=$("#service_rating").val();
            var location_rating=$("#location_rating").val();
            var money_rating=$("#money_rating").val();
            var clean_rating=$("#clean_rating").val();
            var avg= (service_rating * 1) + (location_rating*1) + (money_rating*1) + (clean_rating*1);
            avg= avg/4;
            $("#avarage_rating").val(avg);
            $("#avg_rating").text(avg)

        }

        function cleanReview(rating){

            $("#clean_rating").val(rating);
            $(".clean_rat").each(function(){
                var clean_rat=$(this).data('clean_rating')
                if(clean_rat > rating){
                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                }else{
                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                }

            })
            var service_rating=$("#service_rating").val();
            var location_rating=$("#location_rating").val();
            var money_rating=$("#money_rating").val();
            var clean_rating=$("#clean_rating").val();
            var avg= (service_rating * 1) + (location_rating*1) + (money_rating*1) + (clean_rating*1);
            avg= avg/4;
            $("#avarage_rating").val(avg);
            $("#avg_rating").text(avg)
        }




    </script>
@endsection
