@extends('layout')
@section('title')
    <title>{{ $blog->translated_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $blog->seo_description }}">
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


  <!--=====BLOG DETAILS START=====-->
  <section class="wsus__blog_details mt_45 mb_45 xs_mb_20">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-7">
          <div class="wsus__blog_det_area">
            <div class="wsus__blog_det_img">
              <img src="{{ asset($blog->image) }}" alt="blod images" class="img-fluid w-100">
              <p>
                <span><i class="fal fa-user-clock"></i> {{ $blog->admin->name }}</span>
                <span><i class="fal fa-comment-alt-dots"></i> {{ $blog->total_comment }} {{__('user.Comments')}}</span>
                <span><i class="far fa-eye"></i>{{ $blog->views }} {{__('user.Views')}}</span>
                </p>
            </div>
            <h3>{{ $blog->translated_title }}</h3>
            <p class="details mb-3">{{ $blog->translated_short_description }}</p>

            {!! clean($blog->translated_description) !!}

            @if ($blog_comment_setting->comment_type==1)
            <div class="wsus__blog_comment">
              <h3>{{ $blog->total_comment }} {{__('user.Comments')}}</h3>

              @foreach ($blogComments as $item)
              <div class="wsus__single_comment">
                <div class="wsus__comm_img">
                  <img src="{{ asset($default_profile_image) }}" alt="comment img" class="img-fluid img-thumbnail">
                </div>
                <div class="wsus__comm_text">
                  <h4>{{ $item->name }}</h4>
                  <span>{{ $item->created_at->format('d M Y') }}</span>
                  <p>{{ $item->comment }}</p>
                </div>
              </div>
              @endforeach
              <div class="col-12">
                {{ $blogComments->links('custom_paginator') }}
              </div>
              <h3 class="border-0 pt-0">{{__('user.Submit Comment')}}</h3>
              <form action="{{ route('blog.comment',$blog->id) }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-xl-6">
                    <input type="text" name="name" placeholder="{{__('user.Name')}}">
                  </div>
                  <div class="col-xl-6">
                    <input type="email" name="email" placeholder="{{__('user.Email')}}">
                  </div>
                  <div class="col-xl-12">
                    <textarea cols="3" rows="4" placeholder="{{__('user.Comment')}}" name="comment"></textarea>
                    @if($recaptcha_setting->status==1)
                    <p class="g-recaptcha mb-3" data-sitekey="{{ $recaptcha_setting->site_key }}"></p>
                    @endif

                    <button class="common_btn" type="submit">{{__('user.Submit')}}</button>

                  </div>
                </div>
              </form>
            </div>
            @else
                <div class="wsus__blog_comment">
                    <div class="fb-comments" data-href="{{ Request::url() }}" data-width="" data-numposts="10"></div>
                </div>
            @endif
          </div>
        </div>
        <div class="col-xl-4 col-lg-5">
          <div class="wsus__property_sidebar mb-0" id="sticky_sidebar">
            <div class="wsus__blog_search">
              <h5>{{__('user.Search Blog')}}</h5>
              <form action="{{ route('blog') }}" method="GET">
                <input type="text" placeholder="{{__('user.Type')}}" name="search" required>
                <button class="common_btn" type="submit"><i class="fal fa-search"></i></button>
              </form>
            </div>
            <div class="wsus__search_categoy">
              <h5>{{__('user.Categories')}}</h5>
              <ul>
                @foreach ($blogCategories as $item)
                <li><a href="{{ route('blog',['category' => $item->slug]) }}">{{ $item->translated_name }} <span>{{ $item->blogs->count() }}</span></a></li>
                @endforeach

              </ul>
            </div>
            <div class="wsus__blog_post">
              <h5>{{__('user.Popular Blog')}}</h5>
              <div class="row">
                @php
                    $colorId=1;
                @endphp
                @foreach ($popularBlogs as $index => $popular_item)
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

                <div class="col-xl-12 col-md-6 col-lg-12">
                  <div class="wsus__single_blog">
                    <div class="wsus__blog_img">
                      <img src="{{ asset($popular_item->image) }}" alt="blog items" class="img-fluid w-100">
                      <span class="category {{ $color }}">{{ $popular_item->category->translated_name }}</span>
                    </div>
                    <div class="wsus__blog_text">
                      <p class="blog_date">
                        <span>{{ $popular_item->created_at->format('d') }}</span>
                        <span>{{ $popular_item->created_at->format('m') }}</span>
                        <span>{{ $popular_item->created_at->format('Y') }}</span>
                      </p>
                      <span class="comment"><i class="fal fa-comment-dots"></i> {{ $popular_item->total_comment }}</span>
                      <div class="wsus__blog_header d-flex flex-wrap align-items-center justify-content-between">
                        <div class="blog_header_images d-flex flex-wrap align-items-center w-100">
                          <img src="{{ $popular_item->admin->image ? url($popular_item->admin->image) : url($default_profile_image) }}" alt="bloger" class="img-fluid img-thumbnail">
                          <span>{{ $popular_item->admin->name }}</span>
                        </div>
                      </div>
                      <a href="{{ route('blog.details',$popular_item->slug) }}" class="blog_title">{{ $popular_item->translated_title }}</a>
                      <p>{{ $popular_item->translated_short_description }}</p>
                    </div>
                  </div>
                </div>

                @php
                    $colorId ++;
                @endphp
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--=====BLOG DETAILS  END=====-->


<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0&appId={{ $blog_comment_setting->app_id }}&autoLogAppEvents=1" nonce="MoLwqHe5"></script>

@endsection
