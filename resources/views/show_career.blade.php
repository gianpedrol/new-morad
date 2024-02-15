@extends('layout')
@section('title')
    <title>{{ $career->title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $career->title }}">
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
    CAREER DETAILS START
  ========================-->
  <section class="wsus__career_details mt_45 mb_45">
    <div class="container">
      <div class="row">

        <div class="col-12">
          <div class="wsus__single_career m-0 pb_10">
            <div class="career_text_center">
                <h2>{{ $career->title }}</h2>
                <span>{{ $career->address }}</span>
                <ul class="d-flex flex-wrap">
                    <li><i class="fas fa-hand-holding-usd"></i> {{ $career->salary_range }}</li>
                    <li><i class="fas fa-briefcase"></i> {{ $career->job_nature }}</li>
                    <li><i class="far fa-clock"></i> {{ $career->office_time }}</li>
                </ul>
                <h5><i class="fal fa-calendar-alt"></i> {{__('user.dedline')}} {{ date('d F Y', strtotime($career->deadline)) }}</h5>
            </div>
          </div>
          <div class="col-12">
            <div class="career_details_text">
                {!! clean($career->description) !!}
              <a class="common_btn" href="#" data-bs-toggle="modal" data-bs-target="#carer_modal">{{__('user.apply now')}}</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>


  <!-- Modal modal-dialog-scrollable-->
  <div class="wsus__career_form">
    <div class="modal fade" id="carer_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('user.Apply Form')}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{ route('store-career-application') }}" enctype="multipart/form-data">
                @csrf
              <input type="text" name="name" placeholder="{{__('user.Name')}}">
              <input type="email" name="email" placeholder="{{__('user.Email')}}">
              <input type="text" name="phone" placeholder="{{__('user.Phone')}}">
              <input type="text" name="subject" placeholder="{{__('user.Subject')}}">
              <textarea rows="4" name="description" placeholder="{{__('user.Description')}}"></textarea>
              <input type="file" class="mt_15" name="cv">
              <input type="hidden" value="{{ $career->id }}"  name="career_id">
              <button type="submit" class="common_btn">{{__('user.Submit')}}</button>
          </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!--========================
    CAREER DETAILS END
  ========================-->

@endsection
