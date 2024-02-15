@extends('user.layout')
@section('title')
    <title>{{ $user->name }}</title>
@endsection
@section('user-dashboard')
<div class="row">
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
          <div class="wsus__my_profile">
            <h4 class="heading">{{__('user.My Profile')}}</h4>
            <div class="wsus__dash_info">
              <div class="row">
                <div class="col-xl-4 col-sm-6 col-lg-4">
                  <div class="wsus__dash_info_img">
                    <img src="{{ $user->image ? url($user->image) : url($default_image) }}" alt="my images" class="img-fluid w-100">
                  </div>
                </div>
                <div class="col-xl-8 col-sm-6 col-lg-8">
                  <div class="wsus__dash_info_text">
                    <h3>{{ $user->name }}</h3>

                    @if ($user->phone)
                    <a href="callto:{{ $user->phone }}"><i class="fas fa-phone-alt"></i> {{ $user->phone }}</a>
                    @endif

                    @if ($user->email)
                    <a href="mailto:{{ $user->email }}"><i class="fas fa-envelope-open"></i> {{ $user->email }}</a>
                    @endif

                    @if ($user->website)
                    <p><i class="fas fa-globe"></i> {{ $user->website }}</p>
                    @endif

                    @if ($user->address)
                    <p><i class="fas fa-location-circle"></i> {{ $user->address }}</p>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="wsus__dash_info wsus__my_profile_text p_25 mt_25">
                <form action="{{ route('user.update.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="row">
                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Name')}}" value="{{ $user->name }}" name="name">
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <input type="email" placeholder="{{__('user.Email')}}" name="email" value="{{ $user->email }}" readonly>
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Phone')}}" name="phone" value="{{ $user->phone }}">
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Website')}}" name="website" value="{{ $user->website }}">
                  </div>

                  <div class="col-12">
                    <select name="city_id" id="" class="select_2">
                        <option value="">{{__('user.Select City')}}</option>
                        @foreach ($cities as $city)
                        <option {{ $city->id == $user->city_id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                  </div>


                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Address')}}" name="address" value="{{ $user->address }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input class="file" type="file" name="image">
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Icon One')}}" class="custom-icon-picker" name="icon_one" value="{{ $user->icon_one }}">
                  </div>
                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Link One')}}" name="link_one" value="{{ $user->link_one }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Icon Two')}}" class="custom-icon-picker" name="icon_two" value="{{ $user->icon_two }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Link Two')}}" name="link_two" value="{{ $user->link_two }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Icon three')}}" class="custom-icon-picker" name="icon_three" value="{{ $user->icon_three }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Icon three')}}" name="link_three" value="{{ $user->link_three }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Icon Four')}}" class="custom-icon-picker" name="icon_four" value="{{ $user->icon_four }}">
                  </div>

                  <div class="col-xl-6 col-md-6">
                    <input type="text" placeholder="{{__('user.Link Four')}}" name="link_four" value="{{ $user->link_four }}">
                  </div>

                  <div class="col-12">
                    <textarea class="form-control summer_note" name="about">{{ $user->about }}</textarea>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="common_btn mt-4">{{__('user.Update')}}</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="wsus__dash_info p_25 mt_25">
                    <form action="{{ route('user.update.password') }}" method="POST" class="wsus__dash_change_pass">
                        @csrf
                    <div class="row">
                      <div class="col-12 col-md-6 col-xl-12">
                        <label class="my-1" for="">{{__('user.Old Password')}}</label>
                        <input type="password" name="current_password">
                      </div>
                      <div class="col-12 col-md-6 col-xl-12">
                        <label class="my-1" for="">{{__('user.New Password')}}</label>
                        <input type="password" name="password">
                      </div>
                      <div class="col-12">
                        <label class="my-1" for="">{{__('user.Confirm Password')}}</label>
                        <input type="password" name="password_confirmation">
                      </div>
                      <div class="col-12">
                        <button type="submit" class="common_btn">{{__('user.Change Password')}}</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-xl-6">
                    <form action="{{ route('user.update.profile.banner') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="wsus__dash_info wsus__dash_banner_img p_25 mt_25">
                            <h5 class="sub_heading">{{__('user.Existing Banner Image')}}</h5>
                            <div class="wsus__dash_banner_main">
                                <img src="{{ $user->banner_image ? url($user->banner_image ) : url($agent_default_banner) }}" alt="banner img" class="img-fluid w-100">
                            </div>
                            <label>{{__('user.New Image')}}</label>
                            <input type="file" name="banner_image">
                            <button type="submit" class="common_btn">{{__('user.Update')}}</button>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
