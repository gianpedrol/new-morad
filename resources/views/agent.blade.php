@extends('layout')
@section('title')
<title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
<meta name="description" content="{{ $seo_text->seo_description }}">
@endsection

@section('user-content')

  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ $banner_image ? url($banner_image) : '' }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4></h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Our Agent')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->


<!--=========AGENT START============-->
<section class="wsus__agents mt_45 mb_20">
  <div class="container">
    <div class="row">
        <div class="col-12">
            <form class="agent_top" action="{{ route('agents') }}">
                <select name="location" id="" class="form-control select_2">
                    <option value="">{{__('user.Select Location')}}</option>
                    @foreach ($cities as $search_city)
                    @if (request()->has('location'))
                    <option {{ request()->get('location') == $search_city->slug ? 'selected' : '' }} value="{{ $search_city->slug }}">{{ $search_city->translated_name }}</option>
                    @else
                    <option value="{{ $search_city->slug }}">{{ $search_city->translated_name }}</option>
                    @endif
                    @endforeach
                </select>
                <button class="common_btn2">{{__('user.Search')}}</button>
            </form>
        </div>
    </div>

    <div class="row">

        @if ($agents->count() > 0)
            @foreach ($agents as $agent)
                @php
                    $isOrder=$orders->where('user_id',$agent->id)->count();
                @endphp
                @if ($isOrder >0)
                <div class="col-xl-3 col-sm-6 col-lg-4">
                    <div class="wsus__single_team">
                    <a href="{{ route('agent.show',['user_type' => '2','user_name'=>$agent->slug]) }}" class="wsus__single_team_img">
                        <img src="{{ $agent->image ? url($agent->image) : url($default_profile_image) }}" alt="team images" class="imf-fluid w-100">
                    </a>
                    <a href="{{ route('agent.show',['user_type' => '2','user_name'=>$agent->slug]) }}" class="title">{{ $agent->translated_name }}</a>
                    <p><i class="fal fa-location-circle"></i> {{ $agent->translated_address }}</p>
                    <ul class="agent_link">
                        @if ($agent->icon_one && $agent->link_one)
                        <li><a href="{{ $agent->link_one }}"><i class="{{ $agent->icon_one }}"></i></a></li>
                        @endif

                        @if ($agent->icon_two && $agent->link_two)
                        <li><a href="{{ $agent->link_two }}"><i class="{{ $agent->icon_two }}"></i></a></li>
                        @endif

                        @if ($agent->icon_three && $agent->link_three)
                        <li><a href="{{ $agent->link_three }}"><i class="{{ $agent->icon_three }}"></i></a></li>
                        @endif

                        @if ($agent->icon_four && $agent->link_four)
                        <li><a href="{{ $agent->link_four }}"><i class="{{ $agent->icon_four }}"></i></a></li>
                        @endif
                    </ul>
                    </div>
                </div>
                @endif
            @endforeach

            <div class="col-12 mt_25">
                {{ $agents->links('custom_paginator') }}
            </div>
        @else
            <div class="col-12 text-center text-danger mt-5">
                <h4>{{__('user.Agent Not Found!')}}</h4>
            </div>
        @endif
    </div>
  </div>
</section>
<!--=========AGENT END==========-->
@endsection
