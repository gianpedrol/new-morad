@extends('layout')
@section('title')
    <title>{{__('user.Reset Password')}}</title>
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
                    <h4>{{__('user.Reset Password')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Reset Password')}}</li>
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

            <div class="col-xl-5 col-md-6 offset-xl-1 ml_to_mr">
                <div class="wsus__login_form ">
                    <h3>{{__('user.Reset Password')}}</h3>
                    <form id="resetPassForm">
                        @csrf
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input value="{{ $user->email }}" class="form-control form-control-lg" type="email" id="resetEmail" name="email" placeholder="{{__('user.Email')}}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="password" id="regPassword" name="password" placeholder="{{__('user.Password')}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="password" id="regPassword" name="password_confirmation" placeholder="{{__('user.Confirm Password')}}">
                            </div>
                        </div>



                        @if($recaptcha_setting->status==1)
                        <div class="form-group mt-2">
                            <div class="input-group input-group-lg">
                                <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                            </div>
                        </div>
                        @endif

                        <button id="resetPassBtn" class="common_btn mt-1 mb-3" type="submit"> {{__('user.Reset Password')}}</button>

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
        $("#resetPassBtn").on('click',function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('store-reset-password/') }}"+"/"+ '{{ $token }}',
                type:"post",
                data:$('#resetPassForm').serialize(),
                success:function(response){
                    if(response.success){
                        window.location.href = "{{ route('login')}}";
                        toastr.success(response.success)

                    }
                    if(response.error){


                        var query_url='<?php echo $search_url; ?>';
                        window.location.href = query_url;

                        toastr.error(response.error)

                    }
                },
                error:function(response){
                    if(response.responseJSON.errors.email)toastr.error(response.responseJSON.errors.email[0])
                    if(response.responseJSON.errors.password){
                        toastr.error(response.responseJSON.errors.password[0])
                    }
                    else{
                        toastr.error("{{__('user.Please Complete the recaptcha to submit the form')}}")
                    }



                }

            });


        })


    });

    })(jQuery);
</script>

@endsection


