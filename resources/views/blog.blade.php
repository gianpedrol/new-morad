@extends('layout')
@section('title')
    <title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_text->seo_description }}">
@endsection

@section('user-content')

  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ $banner_image ? url($banner_image->image) : '' }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Blog')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Blog')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
  </section>
  <!--===BREADCRUMB PART END====-->



  <!--=====BLOG START=====-->
  <section class="wsus__blog mt_45 mb_45">
    <div class="container">
      <div class="row">
        @if ($blogs->count() > 0)
            @php
                $colorId=1;
            @endphp
            @foreach ($blogs as $index => $blog_item)
                @php
                    if($index %4 ==0){
                        $colorId=1;
                    }

                    $color="";
                    if($colorId==1){
                        $color="";
                    }else if($colorId==2){
                        $color="oreangr";
                    }else if($colorId==3){
                        $color="gren";
                    }else if($colorId==4){
                        $color="blur";
                    }
                @endphp
                <div class="col-xl-4 col-md-6">
                <div class="wsus__single_blog">
                    <div class="wsus__blog_img">
                    <img src="{{ asset($blog_item->image) }}" alt="blog items" class="img-fluid w-100">
                    <span class="category {{ $color }}">{{ $blog_item->category->translated_name }}</span>
                    </div>
                    <div class="wsus__blog_text">
                    <p class="blog_date">
                        <span>{{ $blog_item->created_at->format('d') }}</span>
                        <span>{{ $blog_item->created_at->format('m') }}</span>
                        <span>{{ $blog_item->created_at->format('Y') }}</span>
                    </p>
                    <span class="comment"><i class="fal fa-comment-dots"></i> {{ $blog_item->totalComment }}</span>
                    <div class="wsus__blog_header d-flex flex-wrap align-items-center justify-content-between">
                        <div class="blog_header_images d-flex flex-wrap align-items-center w-100">
                        <img src="{{ $blog_item->admin->image ? url($blog_item->admin->image) : url($default_profile_image) }}" alt="bloger" class="img-fluid img-thumbnail">
                        <span>{{ $blog_item->admin->name }}</span>
                        </div>
                    </div>
                    <a href="{{ route('blog.details',$blog_item->slug) }}" class="blog_title">{{ $blog_item->translated_title }}</a>
                    <p>{{ $blog_item->translated_short_description }}</p>
                    </div>
                </div>
                </div>
                @php
                    $colorId ++;
                @endphp
            @endforeach
            <div class="col-12 mt_20">
                {{ $blogs->links('custom_paginator') }}
            </div>
        @else
            <div class="col-12 text-center">
                <h3 class="text-danger">{{__('user.Blog Not Found')}}</h3>
            </div>
        @endif
      </div>
    </div>
  </section>
  <!--=====BLOG END=====-->
@endsection
