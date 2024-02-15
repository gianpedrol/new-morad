@extends('user.layout')
@section('title')
    <title>{{__('user.Dashboard')}}</title>
@endsection

@section('user-dashboard')
<div class="row">
    <div class="col-12 col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
            <div class="wsus__manage_dashboard">
                <h4 class="heading">{{__('user.Dashboard')}}</h4>
                <div class="row">
                    <div class="col-xl-4 col-sm-6 col-md-4">
                        <div class="wsus__manage_single">
                            <i class="far fa-list"></i>
                            <span>{{ $publishProperty }}</span>
                            <p>{{__('user.Published Property')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-4">
                        <div class="wsus__manage_single green">
                          <i class="fab fa-buromobelexperte"></i>
                            <span>{{ $expiredProperty }}</span>
                            <p>{{__('user.Expired Property')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-4">
                        <div class="wsus__manage_single sky">
                            <i class="far fa-list"></i>
                            <span>{{ $myReviews }}</span>
                            <p>{{__('user.My Reviews')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-4">
                      <div class="wsus__manage_single blue">
                          <i class="far fa-list"></i>
                          <span>{{ $clientReviews }}</span>
                          <p>{{__('user.Client Reviews')}}</p>
                      </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-4">
                        <div class="wsus__manage_single orange">
                            <i class="far fa-list"></i>
                            <span>{{ $wishlists->count() }}</span>
                            <p>{{__('user.Wishlists')}}</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-sm-6 col-md-4">
                        <div class="wsus__manage_single purple">
                            <i class="far fa-list"></i>
                            <span>{{ $orders->count() }}</span>
                            <p>{{__('user.Orders')}}</p>
                        </div>
                    </div>
                    @if ($activeOrder)
                    <div class="col-xl-12">
                        @php
                            $package=$activeOrder->package;
                        @endphp
                      <div class="wsus__package_details">
                        <h5 class="sub_heading">{{__('user.Active Order')}} <a class="common_btn" href="{{ route('user.purchase.renew', $package->id ) }}">{{__('Renew Package')}}</a></h5>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <td width="50%">{{__('user.Package Name')}}</td>
                                    <td width="50%">{{ $package->package_name }}</td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Price')}}</td>
                                    <td width="50%">{{ $currency }}{{ $package->price }}</td>
                                </tr>

                                 <tr>
                                    <td width="50%">{{__('user.Expired Date')}}</td>
                                    <td width="50%">
                                        @if ($activeOrder->expired_day==-1)
                                            {{__('user.Unlimited')}}
                                        @else
                                            {{ $activeOrder->expired_date }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Property')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_property==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_property }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Aminity')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_aminities==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_aminities }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Nearest Place')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_nearest_place==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_nearest_place }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Photo')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_photo==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_photo }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%">{{__('user.Featured Property')}}</td>
                                    <td width="50%">
                                        @if ($package->is_featured==1)
                                        {{__('user.Available')}}
                                        @else
                                        {{__('user.Not Available')}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Featured Property')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_feature_property==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_feature_property }}
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td width="50%">{{__('user.Top Property')}}</td>
                                    <td width="50%">
                                        @if ($package->is_top==1)
                                        {{__('user.Available')}}
                                        @else
                                        {{__('user.Not Available')}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Top Property')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_top_property==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_top_property }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Urgent Property')}}</td>
                                    <td width="50%">
                                        @if ($package->is_urgent==1)
                                        {{__('user.Available')}}
                                        @else
                                        {{__('user.Not Available')}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">{{__('user.Urgent Property')}}</td>
                                    <td width="50%">
                                        @if ($package->number_of_urgent_property==-1)
                                        {{__('user.Unlimited')}}
                                        @else
                                            {{ $package->number_of_urgent_property }}
                                        @endif
                                    </td>
                                </tr>

                            </table>
                        </div>
                      </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
