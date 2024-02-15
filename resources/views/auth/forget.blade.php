@extends('layout')
@section('title')
    <title>{{__('user.Forget Password')}}</title>
@endsection
@section('meta')
    <meta name="description" content="lorem ipsum">
@endsection

@section('user-content')
    <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ url($banner_image) }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Forget Password')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Forget Password')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->

<!--=======LOGON PART START=========-->
<section class="wsus__logon mt_45 mb_45">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-md-6">
                <div class="wsus__login_form">
                    <h3>{{__('user.Forget Password')}}</h3>
                    <form id="forgetFormSubmit">
                        @csrf
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fad fa-user-circle"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="email" id="regEmail" name="email" placeholder="{{__('user.Email')}}">
                            </div>
                        </div>

                        @if($recaptcha_setting->status==1)
                            <div class="form-group mt-2">
                                <div class="input-group input-group-lg">
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                                </div>
                            </div>
                            @endif

                        <button id="forgBtn" class="common_btn mt-1 mb-3" type="submit"><i id="forg-spinner" class="loading-icon fa fa-spin fa-spinner d-none"></i> {{__('user.Send Email')}}</button>

                        <div class="wsus__reg_forget">
                            <a href="{{ route('login') }}">{{__('user.Back To Login Page')}}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--======= LOGON PART END========-->



    @php
    $search_url = request()->fullUrl();
@endphp

<script>
    (function($) {
    "use strict";
    $(document).ready(function () {

        $("#forgetFormSubmit").on('submit',function(e){
            e.preventDefault();

                // project demo mode check
                var isDemo="{{ env('PROJECT_MODE') }}"
                var demoNotify="{{ env('NOTIFY_TEXT') }}"
                if(isDemo==0){
                    toastr.error(demoNotify);
                    return;
                }
                // end

            $("#forg-spinner").removeClass('d-none')
            $("#forgBtn").addClass('custom-opacity')
            $("#forgBtn").attr('disabled',true);
            $.ajax({
                url: "{{ route('send.forget.password') }}",
                type:"post",
                data:$('#forgetFormSubmit').serialize(),
                success:function(response){
                    if(response.success){
                        $("#forgetFormSubmit").trigger("reset");
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.success(response.success)
                    }
                    if(response.error){
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.error(response.error)

                        var query_url='<?php echo $search_url; ?>';
                        window.location.href = query_url;

                    }
                },
                error:function(response){
                    if(response.responseJSON.errors.email){
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.error(response.responseJSON.errors.email[0])
                    }else{
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        $("#forgBtn").addClass('site-btn-effect')
                        toastr.error("{{__('user.Please Complete the recaptcha to submit the form')}}")
                    }



                }

            });

        })

        $("#forgBtn").on('click',function(e) {
            e.preventDefault();
                // project demo mode check
                var isDemo="{{ env('PROJECT_MODE') }}"
                var demoNotify="{{ env('NOTIFY_TEXT') }}"
                if(isDemo==0){
                    toastr.error(demoNotify);
                    return;
                }
                // end
            $("#forg-spinner").removeClass('d-none')
            $("#forgBtn").addClass('custom-opacity')
            $("#forgBtn").attr('disabled',true);
            $.ajax({
                url: "{{ route('send.forget.password') }}",
                type:"post",
                data:$('#forgetFormSubmit').serialize(),
                success:function(response){
                    if(response.success){
                        $("#forgetFormSubmit").trigger("reset");
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.success(response.success)
                    }
                    if(response.error){

                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.error(response.error)

                        var query_url='<?php echo $search_url; ?>';
                        window.location.href = query_url;
                    }
                },
                error:function(response){
                    if(response.responseJSON.errors.email){
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        toastr.error(response.responseJSON.errors.email[0])
                    }else{
                        $("#forg-spinner").addClass('d-none')
                        $("#forgBtn").removeClass('custom-opacity')
                        $("#forgBtn").attr('disabled',false);
                        $("#forgBtn").addClass('site-btn-effect')
                        toastr.error("{{__('user.Please Complete the recaptcha to submit the form')}}")
                    }


                }

            });


        })




    });

    })(jQuery);
</script>

@endsection

