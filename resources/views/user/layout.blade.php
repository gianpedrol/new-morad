@php
    $setting=App\Models\Setting::first();
@endphp



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi" />
    @yield('title')
    <link rel="icon" type="image/png" href="{{ url($setting->favicon) }}">

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;900&family=Poppins:wght@400;500;600;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('user/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/add_row_custon.css') }}">

    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/iconpicker/fontawesome-iconpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/responsive.css') }}">

    @if ($setting->text_direction=="RTL")
        <link rel="stylesheet" href="{{ asset('user/css/rtl.css') }}">
    @endif

    <link rel="stylesheet" href="{{ asset('user/css/dev.css') }}">

    <!--jquery library js-->
    <script src="{{ asset('user/js/jquery-3.6.0.min.js') }}"></script>

    <script src="{{ asset('user/js/sweetalert2@11.js') }}"></script>

    <style>
        .fade.in {
            opacity: 1 !important;
        }
        .ck-content {
            min-height: 6rem !important;
        }
    </style>
</head>

<body>

@php
    $user=Auth::guard('web')->user();
    $default_image=App\Models\BannerImage::find(15);
@endphp

  <!--============================
    DASHBOARD PAGE START
  ==============================-->
  <section class="wsus__dashboard">
      <div class="container-fluid">
          <span class="wsus__menu_icon"><i class="fas fa-bars"></i></span>
          <div class="wsus__dashboard_side_bar">
            <span class="wsus__close_icon"><i class="fas fa-times"></i></span>
            <a class="wsus__dashboard_logo" href="{{ route('home') }}">
                <img src="{{ asset($setting->logo) }}" alt="logo" class="img-fluid">
              </a>
              <div class="wsus__agent_img">
                  <img src="{{ $user->image ? url($user->image) : url($default_image->image) }}" alt="agent" class="img-fluid">
                  <h5>{{ $user->name }}</h5>
              </div>
              <ul class="wsus__deshboard_menu">
                    <li><a class="{{ Route::is('user.dashboard') ? 'dash_active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fal fa-fw fa-tachometer-alt"></i> {{__('user.Dashboard')}}</a></li>

                    <li><a href="{{ route('home') }}"><i class="fal fa-globe"></i> {{__('user.Go to Homepage')}}</a></li>

                    <li><a class="{{ Route::is('user.my.properties') || Route::is('user.property.edit') || Route::is('user.create.property') ? 'dash_active' : '' }}" href="{{ route('user.my.properties') }}"><i class="far fa-list"></i> {{__('user.My Property')}}</a></li>

                    <li><a class="{{ Route::is('user.my-profile') ? 'dash_active' : '' }}" href="{{ route('user.my-profile') }}"><i class="fas fa-user-tie"></i> {{__('user.My Profile')}}</a></li>

                    <li><a  class="{{ Route::is('user.my-order') || Route::is('user.order.details') ? 'dash_active' : '' }}" href="{{ route('user.my-order') }}"><i class="far fa-list"></i> {{__('user.Order Log')}}</a></li>


                    <li><a class="{{ Route::is('user.pricing.plan') ? 'dash_active' : '' }}" href="{{ route('user.pricing.plan') }}"><i class="fas fa-box-full"></i> {{__('user.Pricing Plan')}}</a></li>


                    <li><a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> {{__('user.Logout')}}</a></li>
              </ul>
          </div>


          @yield('user-dashboard')



        </div>
    </section>
    <!--============================
      DASHBOARD PART END
    ==============================-->


    <!---=====SCROLL BUTTON START=====-->
    <div class="wsus__scroll_btn">
      <i class="fas fa-chevron-up"></i>
    </div>
    <!---=====SCROLL BUTTON END=====-->
    @php
    $setting=App\Models\Setting::first();
    @endphp

    <!--bootstrap js-->
    <script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
    <!--font-awesome js-->
    <script src="{{ asset('user/js/Font-Awesome.js') }}"></script>
    <!--slick js-->
    <script src="{{ asset('user/js/slick.min.js') }}"></script>
    <!--select-2 js-->
    <script src="{{ asset('user/js/select2.min.js') }}"></script>
    <!--counter-2 js-->
    <script src="{{ asset('user/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('user/js/jquery.countup.min.js') }}"></script>
    <!--add row custon js-->
    <script src="{{ asset('user/js/add_row_custon.js') }}"></script>
    <!--sticky sidebar js-->
    <script src="{{ asset('user/js/sticky_sidebar.js') }}"></script>
    <!--summer note js-->


    <!--main/custom js-->
    <script src="{{ asset('user/js/main.js') }}"></script>
    <script src="{{ asset('toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/iconpicker/fontawesome-iconpicker.min.js') }}"></script>

    <script src="{{ asset('backend/ckeditor4/ckeditor.js') }}"></script>

    <script>
        @if(Session::has('messege'))
          var type="{{Session::get('alert-type','info')}}"
          switch(type){
              case 'info':
                   toastr.info("{{ Session::get('messege') }}");
                   break;
              case 'success':
                  toastr.success("{{ Session::get('messege') }}");
                  break;
              case 'warning':
                 toastr.warning("{{ Session::get('messege') }}");
                  break;
              case 'error':
                  toastr.error("{{ Session::get('messege') }}");
                  break;
          }
        @endif
    </script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif



    <script>
        (function($) {
        "use strict";
        $(document).ready(function () {

            CKEDITOR.replace( 'summernote' );
            CKEDITOR.replace( 'summernote2' );
            CKEDITOR.replace( 'summernote3' );

            $('.custom-icon-picker').iconpicker({
                templates: {
                    popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
                        '<div class="popover-title"></div><div class="popover-content"></div></div>',
                    footer: '<div class="popover-footer"></div>',
                    buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
                        ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
                    search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
                    iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                    iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
                }
            })
        });

        })(jQuery);
    </script>


    </body>

    </html>
