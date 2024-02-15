@extends('layout')
@section('title')
    <title>{{ $page->page_name }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $page->translated_page_name }}">
@endsection

@section('user-content')

<!--===BREADCRUMB PART START====-->
<section class="wsus__breadcrumb" style="background: url({{ $banner_image ? url($banner_image) : '' }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{ $page->translated_page_name }}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->translated_page_name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->


<!--=========PRIVACY PART START============-->
<section class="wsus__custome_page mt_40 mb_20">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wsus__privacy_text">
                    {!! clean($page->translated_description) !!}
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========PRIVACY PART END==========-->


@endsection
