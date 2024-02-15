@extends('layout')
@section('title')
    <title>{{__('user.Contact Us')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Contact Us')}}">
@endsection

@section('user-content')

  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ $banner_image ? url($banner_image) : '' }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Contact Us')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Contact Us')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->


<!--=========CONTACT PAGE START============-->
<section class="wsus__contact mt_45 mb_45">
  <div class="container">
      <div class="row">
          <div class="col-xl-4 col-md-6 col-lg-4">
              <div class="wsus__contact_single">
                  <i class="fal fa-envelope"></i>
                  <h5>{{__('user.Email')}}</h5>
                  <a href="javascript:;">{!! clean(nl2br($contact->email)) !!}</a>
              </div>
          </div>
          <div class="col-xl-4 col-md-6 col-lg-4">
              <div class="wsus__contact_single">
                  <i class="far fa-phone-alt"></i>
                  <h5>{{__('user.Phone')}}</h5>
                  <a href="javascript:;">{!! clean(nl2br($contact->phone)) !!}</a>
              </div>
          </div>
          <div class="col-xl-4 col-md-6 col-lg-4">
              <div class="wsus__contact_single md_mar">
                  <i class="fal fa-map-marker-alt"></i>
                  <h5>{{__('user.Address')}}</h5>
                  <a href="javascript:;">{!! clean(nl2br($contact->address)) !!}</a>
              </div>
          </div>
      </div>
      <div class="row mt_40 xs_mt_15">
          <div class="col-12">
              <div class="wsus__contact_question">
                  <h5>{{__('user.Contact Us')}}</h5>
                  <form method="POST" action="{{ route('contact.message') }}">
                    @csrf
                      <div class="row">
                          <div class="col-xl-6 col-lg-6">
                              <div class="wsus__con_form_single">
                                  <input type="text" placeholder="{{__('user.Name')}}*" name="name">
                              </div>
                          </div>
                          <div class="col-xl-6 col-lg-6">
                              <div class="wsus__con_form_single">
                                  <input type="email" placeholder="{{__('user.Email')}}*" name="email">
                              </div>
                          </div>
                          <div class="col-xl-6 col-lg-6">
                              <div class="wsus__con_form_single">
                                  <input type="text" placeholder="{{__('user.Subject')}}*" name="subject">
                              </div>
                          </div>
                          <div class="col-xl-6 col-lg-6">
                              <div class="wsus__con_form_single">
                                  <input type="text" placeholder="{{__('user.Phone')}}" name="phone">
                              </div>
                          </div>
                          <div class="col-xl-12">
                              <div class="wsus__con_form_single">
                                <textarea cols="3" rows="5" placeholder="{{__('user.Message')}}*" name="message"></textarea>

                                @if($recaptcha_setting->status==1)
                                    <p class="g-recaptcha mb-3 mt-3" data-sitekey="{{ $recaptcha_setting->site_key }}"></p>
                                @endif

                              </div>
                              <button type="submit" class="common_btn">{{__('user.Send Message')}}</button>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div class="row mt_45">
        <div class="col-12">
          <div class="wsus__con_map">
            {!! $contact->map  !!}
          </div>
      </div>
      </div>
  </div>
</section>
<!--=========CONTACT PAGE END==========-->
@endsection
