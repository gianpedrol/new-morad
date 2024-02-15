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
                    <h4>{{__('user.Career')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Career')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->



  <!--========================
    CAREER START
  ========================-->
  <section class="wsus__career mt_45 mb_45">
    <div class="container">
        @foreach ($careers as $career)
            <div class="wsus__single_career">
                <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="career_text_center">
                    <h2>{{ $career->title }}</h2>
                    <span>{{ $career->address }}</span>
                    <ul class="d-flex flex-wrap">
                        <li><i class="fas fa-hand-holding-usd"></i> {{ $career->salary_range }}</li>
                        <li><i class="fas fa-briefcase"></i> {{ $career->job_nature }}</li>
                        <li><i class="far fa-clock"></i> {{ $career->office_time }}</li>
                    </ul>
                    <h5><i class="fal fa-calendar-alt"></i> {{__('user.dedline')}} {{ date('d F Y', strtotime($career->deadline)) }}</h5>
                    <p>{!! html_entity_decode(clean(Str::limit($career->description, 500, '...'))) !!}
                    </p>
                    <a class="common_btn" href="{{ route('show-career', $career->slug) }}">{{__('user.view details')}}</a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="wsus__single_img">
                    <img src="{{ asset($career->image) }}" alt="career" class="img-fluid w-100">
                    </div>
                </div>
                </div>
            </div>
      @endforeach
    </div>
  </section>
  <!--========================
    CAREER END
  ========================-->

@endsection
