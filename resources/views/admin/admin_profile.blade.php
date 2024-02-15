@extends('admin.master_layout')
@section('title')
<title>{{__('admin.My Profile')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.My Profile')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.My Profile')}}</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row mt-sm-4">
              <div class="col-12">
                <div class="card profile-widget">

                  <div class="profile-widget-description">
                    <form action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">{{__('admin.Banner Image Preview')}}</label>
                                    @if ($admin->banner_image)
                                    <div class="banner-image w_300">
                                        <img class="w_300" src="{{ asset($admin->banner_image) }}" alt="">
                                    </div>
                                     @else
                                     <div class="banner-image w_300">
                                        <img class="w_300" src="{{ asset($banner_image) }}" alt="">
                                    </div>
                                    @endif

                                    <label class="mt-1">{{__('admin.New Image')}}</label>
                                    <div class="ls-inputicon-box">
                                        <input class="form-control-file wt-form-control" name="banner_image" type="file">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.Image Preview')}}</label>
                                    <div>
                                        @if ($admin->image)
                                        <img class="img-thumbnail" src="{{ url($admin->image) }}" alt="default user image" width="100px">

                                        @else
                                        <img class="img-thumbnail" src="{{ url($defaultProfile) }}" alt="default user image" width="100px">

                                        @endif
                                    </div>
                                    <label for="" class="mt-2">{{__('admin.New Image')}}</label>
                                    <input type="file" class="form-control-file" name="image">

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.Name')}}</label>
                                    <input type="text" class="form-control" value="{{ $admin->name }}" name="name">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.Email')}}</label>
                                    <input type="text" class="form-control" value="{{ $admin->email }}" name="email">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.Phone')}}</label>
                                    <input type="text" class="form-control" value="{{ $admin->phone }}" name="phone">

                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook">{{__('admin.Facebook')}}</label>
                                    <input type="text" name="facebook" value="{{ $admin->facebook }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter">{{__('admin.Twitter')}}</label>
                                    <input type="text" name="twitter" value="{{  $admin->twitter }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin">{{__('admin.Linkedin')}}</label>
                                    <input type="text" name="linkedin" value="{{  $admin->linkedin }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">{{__('admin.Whatsapp')}}</label>
                                    <input type="text" name="whatsapp" value="{{  $admin->whatsapp }}" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="webiste">{{__('admin.Website')}}</label>
                                    <input type="text" name="website" value="{{  $admin->website }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">{{__('admin.Address')}}</label>
                                    <input type="text" name="address" value="{{  $admin->address }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about">{{__('admin.About')}}</label>
                                   <textarea name="about" id="about" cols="30" rows="2" class="form-control text-area-5">{{  $admin->about }}</textarea>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.New Password')}}</label>
                                    <input  type="password" class="form-control" name="password">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{__('admin.Confirm Password')}}</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>

                        </div>

                        <button class="btn btn-primary" type="submit">{{__('admin.Update')}}</button>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
@endsection
