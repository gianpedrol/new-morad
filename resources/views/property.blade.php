@extends('layout')
@section('title')
<title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $seo_text->seo_description }}">
@endsection
@section('user-content')

<style>
    @media (max-width: 767px) {
        .wsus__single_property_text .tk {
            position: relative !important;
            margin-top: -20px !important;
            margin-left: -49px !important;
            margin-bottom: 10px;
        }

        .wsus__single_property_text .title {
            font-size: 14px !important;
            margin-top: 15px !important;
        }
    }
    .wsus__single_property_img {
    position: relative; /* Ou defina um valor específico se desejar */
    overflow: hidden !important; /* Esconde o excesso de imagem */
    display: flex !important;
    justify-content: center !important; /* Centraliza horizontalmente */
    align-items: center !important; /* Centraliza verticalmente */
}

.wsus__single_property_img img {
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: cover !important; /* Corta a imagem para preencher a div */
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Seleciona todas as divs com a classe wsus__single_property_img
        const propertyImages = document.querySelectorAll('.wsus__single_property_img');
    
        propertyImages.forEach(div => {
            // Adiciona evento de clique a cada div
            div.addEventListener('click', function(event) {
                // Previne o clique padrão caso seja diretamente na imagem
                event.preventDefault();
                // Seleciona o link <a> dentro da div
                const link = div.querySelector('a');
                if (link) {
                    // Redireciona para a URL do link
                    window.location.href = link.href;
                }
            });
        });
    });
    </script>

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

$page_type='list_view' ;


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
                            <!-- <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                                                <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span>
                                                @elseif ($item->property_purpose_id==2)
                                                <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span> /
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
                                                @if ($item->property_purpose_id==1) <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span>
                                                </span>
                                                @elseif ($item->property_purpose_id==2)
                                                <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span> /
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
                            </div> -->
                            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="row list_view">
                                    @if ($isActivePropertyQty > 0)
                                    @foreach ($properties as $item)
                                    <div class="col-12">
                                        <div class="wsus__single_property">
                                            <div class="wsus__single_property_img">
                                                <a href="{{ route('property.details',$item->slug) }}" >
                                                <img src="{{ asset($item->thumbnail_image) }}" alt="properties" class="img-fluid w-100">
                                                </a>
                                            </div>
                                            <div class="wsus__single_property_text">

                                                <div class="w-100 d-flex align-items-center">
                                                    @if ($item->property_purpose_id==1)
                                                    <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>

                                                    @elseif($item->property_purpose_id==2)
                                                    <span class="sale">{{ $item->propertyPurpose->translated_custom_purpose }}</span>
                                                    @endif

                                                    @if ($item->urgent_property==1)
                                                    <span class="rent">{{__('user.Urgent')}}</span>
                                                    @endif

                                                    @if ($item->property_purpose_id==1)
                                                    <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span>
                                                    @elseif ($item->property_purpose_id==2)
                                                    <span class="tk">{{ $currency }}{{ number_format($item->price, 2, ',', '.') }}</span> /
                                                    @if ($item->period=='Daily')
                                                    <span>{{__('user.Daily')}}</span>
                                                    @elseif ($item->period=='Monthly')
                                                    <span>{{__('user.Monthly')}}</span>
                                                    @elseif ($item->period=='Yearly')
                                                    <span>{{__('user.Yearly')}}</span>
                                                    @endif
                                                    </span>
                                                    @endif

                                                </div>

                                                <a href="{{ route('property.details',$item->slug) }}" class="title w-100">{{ $item->translated_title }}</a>
                                                <ul class="d-flex flex-wrap justify-content-between">
                                                    <li><i class="fal fa-bed"></i> {{ $item->number_of_bedroom }} {{__('user.Bed')}}</li>
                                                    <li><i class="fal fa-shower"></i> {{ $item->number_of_bathroom }} {{__('user.Bath')}}</li>
                                                    <li><i class="fal fa-draw-square"></i> {{ $item->area }} {{__('user.Sqft')}}</li>
                                                </ul>


                                            </div>
                                        </div>
                                    </div>
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
                        <input type="hidden" name="page_type" value="{{ $page_type }}">
                        <div class="wsus__single_property_search">
                            <label>Selecione a Cidade</label>
                            <select class="select_2" name="city_id">
                                <option value="">Cidade</option>
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
                            <label>Selecione o tipo de apartamento</label>
                            <select class="select_2" name="property_type">
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
                            <label>Selecione:</label>
                            <select class="select_2" name="purpose_type">
                                @if (request()->has('purpose_type'))
                                <option {{ request()->get('purpose_type') == 1 ? 'selected' : ''  }} value="1">Comprar</option>
                                <option {{ request()->get('purpose_type') == 2 ? 'selected' : ''  }} value="2">Alugar</option>
                                @else
                                <option value="1">Comprar</option>
                                <option value="2">Alugar</option>
                                @endif
                            </select>
                        </div>

                        <div class="wsus__single_property_search">
                            <label>Selecione a faixa de preço</label>
                            <select class="select_2" name="price_range">
                                <option value="">Selecione a faixa de preço</option>
                                @php
                                $min_price = $minimum_price;
                                @endphp
                                @for ($i = 1; $i <= 10; $i++) @if (request()->has('price_range'))
                                    @php
                                    $max_price = $minimum_price + ($mod_price * $i);
                                    $value = $min_price.':'.$max_price;
                                    @endphp
                                    <option {{ $value == request()->get('price_range') ? 'selected' : '' }} value="{{ $value }}">{{ 'R$'.number_format($min_price, 2, ',', '.').' - '.'R$'.number_format($max_price, 2, ',', '.') }}</option>
                                    @php
                                    $min_price = $max_price;
                                    @endphp
                                    @else
                                    @php
                                    $max_price = $minimum_price + ($mod_price * $i);
                                    $value = $min_price.':'.$max_price;
                                    @endphp
                                    <option value="{{ $value }}">{{ 'R$'.number_format($min_price, 2, ',', '.').' - '.'R$'.number_format($max_price, 2, ',', '.') }}</option>
                                    @php
                                    $min_price = $max_price;
                                    @endphp
                                    @endif
                                    @endfor
                            </select>
                        </div>



                        @php
                        $searhAminities=request()->get('aminity') ;
                        $isCollapse=false;
                        if(request()->has('aminity')){
                        $isCollapse=true;
                        }
                        @endphp

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
        $(document).ready(function() {
            $("#sortingId").on("change", function() {
                var id = $(this).val();

                var isSortingId = '<?php echo $isSortingId; ?>'
                var query_url = '<?php echo $search_url; ?>';

                if (isSortingId == 0) {
                    var sorting_id = "&sorting_id=" + id;
                    query_url += sorting_id;
                } else {
                    var href = new URL(query_url);
                    href.searchParams.set('sorting_id', id);
                    query_url = href.toString()
                }

                window.location.href = query_url;
            })

        });

    })(jQuery);
</script>
@endsection