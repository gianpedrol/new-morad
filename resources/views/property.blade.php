@extends('layout')
@section('title')
    <title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_text->seo_description }}">
@endsection
@section('user-content')

  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ url($banner_image) }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Our Property')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Our Property')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->


@php
    $search_url = request()->fullUrl();
    $get_url = substr($search_url, strpos($search_url, "?") + 1);

    $grid_url='';
    $list_url='';
    $isSortingId=0;

    $page_type=request()->get('page_type') ;
    if($page_type=='list_view'){
        $grid_url=str_replace('page_type=list_view','page_type=grid_view',$search_url);
        $list_url=str_replace('page_type=list_view','page_type=list_view',$search_url);
    }else if($page_type=='grid_view'){
        $grid_url=str_replace('page_type=grid_view','page_type=grid_view',$search_url);
        $list_url=str_replace('page_type=grid_view','page_type=list_view',$search_url);
    }
    if(request()->has('sorting_id')){
        $isSortingId=1;
    }
@endphp

<!--=====PROPERTY PAGE START=====-->
<section class="wsus__property_page mt_45 mb_45">
  <div class="container">
    <div class="row">
      <div class="col-xl-8">
        <div class="row">
          <div class="col-12">
            <div class="wsus__property_topbar d-flex justify-content-between mb-4">
              <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                    <i class="fas fa-th-large"></i>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                    <i class="far fa-stream"></i>
                  </button>
                </li>
              </ul>
              <div class="wsus__property_top_select">
                @if ($isSortingId==1)
                <select class="select_2"  id="sortingId">
                    <option {{ request()->get('sorting_id')==1 ? 'selected' : '' }} value="1">{{__('user.Default Order')}}</option>

                    <option {{ request()->get('sorting_id')==2 ? 'selected' : '' }} value="2">{{__('user.Most Views')}}</option>

                    <option {{ request()->get('sorting_id')==3 ? 'selected' : '' }} value="3">{{__('user.Featured')}}</option>

                    <option {{ request()->get('sorting_id')==4 ? 'selected' : '' }} value="4">{{__('user.Top')}}</option>

                    <option {{ request()->get('sorting_id')==5 ? 'selected' : '' }} value="5">{{__('user.New')}}</option>

                    <option {{ request()->get('sorting_id')==6 ? 'selected' : '' }} value="6">{{__('user.Urgent')}}</option>

                    <option {{ request()->get('sorting_id')==7 ? 'selected' : '' }} value="7">{{__('user.Oldest')}}</option>
                </select>
                @else
                <select class="select_2" id="sortingId">
                    <option value="1">{{__('user.Default Order')}}</option>
                    <option value="2">{{__('user.Most Views')}}</option>
                    <option value="3">{{__('user.Featured')}}</option>
                    <option value="4">{{__('user.Top')}}</option>
                    <option value="5">{{__('user.New')}}</option>
                    <option value="6">{{__('user.Urgent')}}</option>
                    <option value="7">{{__('user.Oldest')}}</option>
                </select>
                @endif
              </div>
            </div>
          </div>

          @php
                $isActivePropertyQty=0;
                foreach ($properties as $value) {
                    if($value->expired_date==null){
                        $isActivePropertyQty +=1;
                    }else if($value->expired_date >= date('Y-m-d')){
                        $isActivePropertyQty +=1;
                    }
                }
            @endphp

          <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">

                    @if ($isActivePropertyQty > 0)
                        @foreach ($properties as $item)
                            @if ($item->expired_date==null)
                                <div class="col-xl-6 col-md-6">
                                    <div class="wsus__single_property">
                                    <div class="wsus__single_property_img">
                                        <img src="{{ asset($item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">

                                        @if ($item->property_purpose_id==1)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>

                                        @elseif($item->property_purpose_id==2)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>
                                        @endif

                                        @if ($item->urgent_property==1)
                                            <span class="rent">{{__('user.Urgent')}}</span>
                                        @endif

                                    </div>
                                    <div class="wsus__single_property_text">
                                        @if ($item->property_purpose_id==1)
                                            <span class="tk">{{ $currency }}{{ $item->price }}</span>
                                        @elseif ($item->property_purpose_id==2)
                                        <span class="tk">{{ $currency }}{{ $item->price }} /
                                            @if ($item->period=='Daily')
                                            <span>{{__('user.Daily')}}</span>
                                            @elseif ($item->period=='Monthly')
                                            <span>{{__('user.Monthly')}}</span>
                                            @elseif ($item->period=='Yearly')
                                            <span>{{__('user.Yearly')}}</span>
                                            @endif
                                        </span>
                                        @endif

                                        <a href="{{ route('property.details',$item->slug) }}" class="title w-100">{{ $item->translated_title }}</a>
                                        <ul class="d-flex flex-wrap justify-content-between">
                                            <li><i class="fal fa-bed"></i> {{ $item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                            <li><i class="fal fa-shower"></i> {{ $item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                            <li><i class="fal fa-draw-square"></i> {{ $item->area }} {{__('user.Sqft')}}</li>
                                        </ul>
                                        <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                            <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $item->propertyType->id]) }}" class="category">{{ $item->propertyType->translated_type }}</a>

                                        @php
                                            $total_review=$item->reviews->where('status',1)->count();
                                            if($total_review > 0){
                                                $avg_sum=$item->reviews->where('status',1)->sum('avarage_rating');

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
                            @elseif($item->expired_date >= date('Y-m-d'))
                                <div class="col-xl-6 col-md-6">
                                    <div class="wsus__single_property">
                                    <div class="wsus__single_property_img">
                                        <img src="{{ asset($item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">

                                        @if ($item->property_purpose_id==1)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>

                                        @elseif($item->property_purpose_id==2)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>
                                        @endif

                                        @if ($item->urgent_property==1)
                                            <span class="rent">{{__('user.Urgent')}}</span>
                                        @endif

                                    </div>
                                    <div class="wsus__single_property_text">
                                        @if ($item->property_purpose_id==1)
                                            <span class="tk">{{ $currency }}{{ $item->price }}</span>
                                        @elseif ($item->property_purpose_id==2)
                                        <span class="tk">{{ $currency }}{{ $item->price }} /
                                            @if ($item->period=='Daily')
                                            <span>{{__('user.Daily')}}</span>
                                            @elseif ($item->period=='Monthly')
                                            <span>{{__('user.Monthly')}}</span>
                                            @elseif ($item->period=='Yearly')
                                            <span>{{__('user.Yearly')}}</span>
                                            @endif
                                        </span>
                                        @endif

                                        <a href="{{ route('property.details',$item->slug) }}" class="title w-100">{{ $item->translated_title }}</a>
                                        <ul class="d-flex flex-wrap justify-content-between">
                                            <li><i class="fal fa-bed"></i> {{ $item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                            <li><i class="fal fa-shower"></i> {{ $item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                            <li><i class="fal fa-draw-square"></i> {{ $item->area }} {{__('user.Sqft')}}</li>
                                        </ul>
                                        <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                            <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $item->propertyType->id]) }}" class="category">{{ $item->propertyType->translated_type }}</a>

                                        @php
                                            $total_review=$item->reviews->where('status',1)->count();
                                            if($total_review > 0){
                                                $avg_sum=$item->reviews->where('status',1)->sum('avarage_rating');

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
                    @else
                    <div class="col-12 text-center">
                        <h3 class="text-danger">{{__('user.Property Not Found')}}</h3>
                    </div>
                    @endif

                </div>
              </div>
              <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row list_view">
                    @if ($isActivePropertyQty > 0)
                        @foreach ($properties as $item)
                            @if ($item->expired_date==null)
                                <div class="col-12">
                                    <div class="wsus__single_property">
                                    <div class="wsus__single_property_img">
                                        <img src="{{ asset($item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_property_text">

                                        @if ($item->property_purpose_id==1)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>

                                        @elseif($item->property_purpose_id==2)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>
                                        @endif

                                        @if ($item->urgent_property==1)
                                            <span class="rent">{{__('user.Urgent')}}</span>
                                        @endif

                                        @if ($item->property_purpose_id==1)
                                            <span class="tk">{{ $currency }}{{ $item->price }}</span>
                                        @elseif ($item->property_purpose_id==2)
                                        <span class="tk">{{ $currency }}{{ $item->price }} /
                                            @if ($item->period=='Daily')
                                            <span>{{__('user.Daily')}}</span>
                                            @elseif ($item->period=='Monthly')
                                            <span>{{__('user.Monthly')}}</span>
                                            @elseif ($item->period=='Yearly')
                                            <span>{{__('user.Yearly')}}</span>
                                            @endif
                                        </span>
                                        @endif

                                        <a href="{{ route('property.details',$item->slug) }}" class="title w-100">{{ $item->translated_title }}</a>
                                        <ul class="d-flex flex-wrap justify-content-between">
                                            <li><i class="fal fa-bed"></i> {{ $item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                            <li><i class="fal fa-shower"></i> {{ $item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                            <li><i class="fal fa-draw-square"></i> {{ $item->area }} {{__('user.Sqft')}}</li>
                                        </ul>
                                        <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                            <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $item->propertyType->id]) }}" class="category">{{ $item->propertyType->translated_type }}</a>

                                            @php
                                                $total_review=$item->reviews->where('status',1)->count();
                                                if($total_review > 0){
                                                    $avg_sum=$item->reviews->where('status',1)->sum('avarage_rating');

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
                            @elseif($item->expired_date >= date('Y-m-d'))
                                <div class="col-12">
                                    <div class="wsus__single_property">
                                    <div class="wsus__single_property_img">
                                        <img src="{{ asset($item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">
                                    </div>
                                    <div class="wsus__single_property_text">

                                        @if ($item->property_purpose_id==1)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>

                                        @elseif($item->property_purpose_id==2)
                                        <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>
                                        @endif

                                        @if ($item->urgent_property==1)
                                            <span class="rent">{{__('user.Urgent')}}</span>
                                        @endif

                                        @if ($item->property_purpose_id==1)
                                            <span class="tk">{{ $currency }}{{ $item->price }}</span>
                                        @elseif ($item->property_purpose_id==2)
                                        <span class="tk">{{ $currency }}{{ $item->price }} /
                                            @if ($item->period=='Daily')
                                            <span>{{__('user.Daily')}}</span>
                                            @elseif ($item->period=='Monthly')
                                            <span>{{__('user.Monthly')}}</span>
                                            @elseif ($item->period=='Yearly')
                                            <span>{{__('user.Yearly')}}</span>
                                            @endif
                                        </span>
                                        @endif

                                        <a href="{{ route('property.details',$item->slug) }}" class="title w-100">{{ $item->translated_title }}</a>
                                        <ul class="d-flex flex-wrap justify-content-between">
                                            <li><i class="fal fa-bed"></i> {{ $item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                            <li><i class="fal fa-shower"></i> {{ $item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                            <li><i class="fal fa-draw-square"></i> {{ $item->area }} {{__('user.Sqft')}}</li>
                                        </ul>
                                        <div class="wsus__single_property_footer d-flex justify-content-between align-items-center">
                                            <a href="{{ route('search-property',['page_type' => 'list_view','property_type' => $item->propertyType->id]) }}" class="category">{{ $item->propertyType->translated_type }}</a>

                                            @php
                                                $total_review=$item->reviews->where('status',1)->count();
                                                if($total_review > 0){
                                                    $avg_sum=$item->reviews->where('status',1)->sum('avarage_rating');

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
                    @else
                        <div class="col-12 text-center">
                            <h3 class="text-danger">{{__('user.Property Not Found')}}</h3>
                        </div>
                    @endif


                </div>
              </div>
            </div>
          </div>
          @if ($isActivePropertyQty > 0)
          <div class="col-12">
            {{ $properties->links('custom_paginator') }}
          </div>
          @endif
        </div>
      </div>
      <div class="col-xl-4">
        <div class="wsus__search_property" id="sticky_sidebar">
          <h3>{{__('user.Find Your Property')}} </h3>
          <form method="GET" action="{{ route('search-property') }}">
            <div class="wsus__single_property_search">
              <label>{{__('user.Keyword')}}</label>
              <input type="text" placeholder="{{__('user.Type')}}" name="search" value="{{ request()->has('search') ? request()->get('search') : '' }}">
            </div>
            <input type="hidden" name="page_type" value="{{ $page_type }}">
            <div class="wsus__single_property_search">
              <label>{{__('user.Location')}}</label>
              <select class="select_2" name="city_id">
                <option value="">{{__('user.Location')}}</option>
                @foreach ($cities as $city_item)
                    @if (request()->has('city_id'))
                        <option {{ request()->get('city_id') == $city_item->id ? 'selected' : ''  }} value="{{ $city_item->id }}">{{ $city_item->translated_name }}</option>
                    @else
                        <option value="{{ $city_item->id }}">{{ $city_item->translated_name }}</option>
                    @endif
                @endforeach
            </select>
            </div>
            <div class="wsus__single_property_search">
              <label>{{__('user.Property Type')}}</label>
              <select class="select_2" name="property_type">
                <option value="">{{__('user.Property Type')}}</option>
                @foreach ($propertyTypes as $property_type_item)
                    @if (request()->has('property_type'))
                        <option {{ request()->get('property_type') == $property_type_item->id ? 'selected' : ''  }} value="{{ $property_type_item->id }}">{{ $property_type_item->translated_type }}</option>
                    @else
                        <option value="{{ $property_type_item->id }}">{{ $property_type_item->translated_type }}</option>
                    @endif
                @endforeach
            </select>
            </div>
            <div class="wsus__single_property_search">
              <label>{{__('user.Property Purpose')}}</label>
              <select class="select_2" name="purpose_type">
                    @if (request()->has('purpose_type'))
                        <option value="">{{__('user.Any')}}</option>
                        <option {{ request()->get('purpose_type') == 1 ? 'selected' : ''  }} value="1">{{__('user.Sell')}}</option>
                        <option {{ request()->get('purpose_type') == 2 ? 'selected' : ''  }} value="2">{{__('user.Rent')}}</option>
                    @else
                        <option value="">{{__('user.Any')}}</option>
                        <option value="1">{{__('user.Sell')}}</option>
                        <option value="2">{{__('user.Rent')}}</option>
                    @endif
            </select>
            </div>

            <div class="wsus__single_property_search">
              <label>{{__('user.Price Range')}}</label>
              <select class="select_2" name="price_range">
                <option value="">{{__('user.Price Range')}}</option>
                @php
                    $min_price = $minimum_price;
                @endphp
                @for ($i = 1; $i <= 10; $i++)
                    @if (request()->has('price_range'))
                        @php
                            $max_price = $minimum_price + ($mod_price * $i);
                            $value = $min_price.':'.$max_price;
                        @endphp
                        <option {{ $value == request()->get('price_range') ? 'selected' : '' }} value="{{ $value }}">{{ $currency.$min_price }} - {{ $currency.$max_price }}</option>
                        @php
                            $min_price = $max_price + 1;
                        @endphp
                    @else
                        @php
                            $max_price = $minimum_price + ($mod_price * $i);
                            $value = $min_price.':'.$max_price;
                        @endphp
                        <option value="{{ $value }}">{{ $currency.$min_price }} - {{ $currency.$max_price }}</option>
                        @php
                            $min_price = $max_price + 1;
                        @endphp
                @endif
                @endfor
            </select>
            </div>

            <div class="wsus__single_property_search">
              <label>{{__('user.Number Of Rooms')}}</label>
              <select class="select_2" name="number_of_room">
                <option value="">{{__('user.Number Of Rooms')}}</option>
                @for ($i = 1; $i <= $max_number_of_room; $i++)
                    @if (request()->has('number_of_room'))
                        <option {{ request()->get('number_of_room') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif

                @endfor
            </select>
            </div>

            <div class="wsus__single_property_search">
              <label>{{__('user.Property Id')}}</label>
              <input type="text" placeholder="{{__('user.Property Id')}}" name="property_id" value="{{ request()->has('property_id') ? request()->get('property_id') : '' }}">
            </div>








            @php
                $searhAminities=request()->get('aminity') ;
                $isCollapse=false;
                if(request()->has('aminity')){
                    $isCollapse=true;
                }
            @endphp

            <div class="wsus__single_property_search_check">
              <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        {{__('user.Aminities')}}
                    </button>
                  </h2>
                  <div id="collapseThree" class="accordion-collapse collapse {{ $isCollapse ? 'show' : '' }}" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        @if (request()->has('aminity'))
                            @foreach ($aminities as $aminity)
                                @php
                                    $isChecked=false;
                                @endphp
                                @foreach ($searhAminities as $searhAminity)
                                    @if ($searhAminity==$aminity->id)
                                        @php
                                            $isChecked=true;
                                        @endphp
                                    @endif
                                @endforeach

                                <div class="form-check">
                                    <input {{ $isChecked ? 'checked' : '' }} name="aminity[]" class="form-check-input" type="checkbox" value="{{ $aminity->id }}" id="flexCheckDefault-{{ $aminity->id }}">
                                    <label class="form-check-label" for="flexCheckDefault-{{ $aminity->id }}">
                                        {{ $aminity->translated_aminity }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            @foreach ($aminities as $aminity)
                                <div class="form-check">
                                    <input name="aminity[]" class="form-check-input" type="checkbox" value="{{ $aminity->id }}" id="flexCheckDefault-{{ $aminity->id }}">
                                    <label class="form-check-label" for="flexCheckDefault-{{ $aminity->id }}">
                                        {{ $aminity->translated_aminity }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="common_btn2">{{__('user.Search')}}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!--=====PROPERTY PAGE END=====-->

<script>
    (function($) {
    "use strict";
    $(document).ready(function () {
        $("#sortingId").on("change",function(){
            var id=$(this).val();

            var isSortingId='<?php echo $isSortingId; ?>'
            var query_url='<?php echo $search_url; ?>';

            if(isSortingId==0){
                var sorting_id="&sorting_id="+id;
                query_url += sorting_id;
            }else{
                    var href = new URL(query_url);
                href.searchParams.set('sorting_id', id);
                query_url=href.toString()
            }

            window.location.href = query_url;
        })

    });

    })(jQuery);
</script>
@endsection
