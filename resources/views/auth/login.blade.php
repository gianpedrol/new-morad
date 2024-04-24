@extends('layout')
@section('title')
    <title>{{__('user.Login')}}</title>
@endsection
@section('user-content')

<!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ url($banner_image) }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Login')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Login')}}</li>
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
                    <h3>{{__('user.If You Have An Account? Login Here')}}</h3>
                    <form id="loginFormSubmit">
                        @csrf
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fad fa-user-circle"></i>
                                    </span>
                                </div>
                                <input id="loginEmail" class="form-control form-control-lg" type="email" name="email" placeholder="{{__('user.Email')}}" value="{{ env('PROJECT_MODE')==0 ? 'agent@gmail.com' : '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <input id="loginPassword" class="form-control form-control-lg" type="password" name="password" placeholder="{{__('user.Password')}}" value="{{ env('PROJECT_MODE')==0 ? 1234 : '' }}">
                            </div>
                        </div>

                        @if($recaptcha_setting->status==1)
                            <div class="form-group mt-2">
                                <div class="input-group input-group-lg">
                                    <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                                </div>
                            </div>
                            @endif

                        <div class="wsus__check_area">
                            <div class="form-check">
                                <input name="remember" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{__('user.Remember Me')}}
                                </label>
                            </div>
                            <a href="{{ route('forget.password') }}">{{__('user.Forget Your Password ?')}}</a>
                        </div>
                        <div class="wsus__reg_forget">
                            <button class="common_btn" type="submit" id="userLoginBtn">{{__('user.Login')}}</button>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-xl-5 col-md-6 offset-xl-1 ml_to_mr">
                <div class="wsus__login_form ">
                    <h3>{{__('user.Dont Have An Account? Please Register')}}</h3>
                    <form id="registerFormSubmit">
                        @csrf
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fad fa-user-circle"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="text" name="name" id="regName" placeholder="{{__('user.Name')}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fad fa-user-circle"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="text" name="creci" id="regName" placeholder="Creci">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input class="form-control form-control-lg" type="email" id="regEmail" name="email" placeholder="{{__('user.Email')}}">
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

                        @if($recaptcha_setting->status==1)
                        <div class="form-group mt-2">
                            <div class="input-group input-group-lg">
                                <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                            </div>
                        </div>
                        @endif

                        <button id="registerBtn" class="common_btn" type="submit"><i id="reg-spinner" class="loading-icon fa fa-spin fa-spinner d-none"></i> {{__('user.Register')}}</button>
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
        $("#loginFormSubmit").on('submit',function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('login') }}",
                type:"post",
                data:$('#loginFormSubmit').serialize(),
                success:function(response){
                    if(response.success){
                        window.location.href = "{{ route('user.dashboard')}}";
                        toastr.success(response.success)

                    }
                    if(response.error){
                        toastr.error(response.error)

                        var query_url='<?php echo $search_url; ?>';
                        window.location.href = query_url;

                    }
                },
                error:function(response){
                    if(response.responseJSON.errors.email)toastr.error(response.responseJSON.errors.email[0])
                    if(response.responseJSON.errors.password){
                        toastr.error(response.responseJSON.errors.password[0])
                    }

                    if(!response.responseJSON.errors.email && !response.responseJSON.errors.password){

                        toastr.error("{{__('user.Please Complete the recaptcha to submit the form')}}")
                    }


                }

            });


        })

        $("#registerFormSubmit").on('submit',function(e) {
            e.preventDefault();
            $("#reg-spinner").removeClass('d-none')
            $("#registerBtn").addClass('custom-opacity')
            $("#registerBtn").attr('disabled',true);
            $.ajax({
                url: "{{ route('register') }}",
                type:"post",
                data:$('#registerFormSubmit').serialize(),
                success:function(response){
                    if(response.success){
                        $("#registerFormSubmit").trigger("reset");
                        $("#reg-spinner").addClass('d-none')
                        $("#registerBtn").removeClass('custom-opacity')
                        $("#registerBtn").attr('disabled',false);
                        toastr.success(response.success)
                    }
                    if(response.error){
                        toastr.error(response.error)

                        var query_url='<?php echo $search_url; ?>';
                        window.location.href = query_url;
                    }
                },
                error:function(response){
                    if(response.responseJSON.errors.name){
                        $("#reg-spinner").addClass('d-none')
                        $("#registerBtn").removeClass('custom-opacity')
                        $("#registerBtn").attr('disabled',false);
                        $("#registerBtn").addClass('site-btn-effect')
                        toastr.error(response.responseJSON.errors.name[0])
                    }

                    if(response.responseJSON.errors.email){
                        $("#reg-spinner").addClass('d-none')
                        $("#registerBtn").removeClass('custom-opacity')
                        $("#registerBtn").attr('disabled',false);
                        $("#registerBtn").addClass('site-btn-effect')
                        toastr.error(response.responseJSON.errors.email[0])
                    }

                    if(response.responseJSON.errors.password){
                        $("#reg-spinner").addClass('d-none')
                        $("#registerBtn").removeClass('custom-opacity')
                        $("#registerBtn").attr('disabled',false);
                        $("#registerBtn").addClass('site-btn-effect')
                        toastr.error(response.responseJSON.errors.password[0])
                    }

                    if(!response.responseJSON.errors.name && !response.responseJSON.errors.email && !response.responseJSON.errors.password){
                        $("#reg-spinner").addClass('d-none')
                        $("#registerBtn").removeClass('custom-opacity')
                        $("#registerBtn").attr('disabled',false);
                        $("#registerBtn").addClass('site-btn-effect')
                        toastr.error("{{__('user.Please Complete the recaptcha to submit the form')}}")
                    }


                }

            });


        })

    });

    })(jQuery);
</script>

@endsection


