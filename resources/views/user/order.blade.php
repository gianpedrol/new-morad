@extends('user.layout')
@section('title')
    <title>{{__('user.My Orders')}}</title>
@endsection
@section('user-dashboard')
<div class="row">
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
          <h4 class="heading">{{__('user.My Orders')}}</h4>
          <div class="wsus__dash_order mb_25">
            <div class="table-responsive">
              <table class="table">
                <tbody>
                  <tr>
                    <th  class="serial">{{__('user.Serial')}}</th>
                    <th class="package">{{__('user.Package')}}</th>
                    <th class="p_date">{{__('user.Purchase Date')}}</th>
                    <th class="e_date">{{__('user.Expired Date')}}</th>
                    <th class="price">{{__('user.Price')}}</th>
                    <th  class="action">{{__('user.Action')}}</th>

                  </tr>
                    @foreach ($orders as $index => $order)
                    <tr>
                        <td class="serial">{{ ++$index }}</td>
                        <td class="package">
                            {{ $order->package->package_name }}
                            <br>
                            @if ($order->status==1)
                                @if ($order->expired_date==null)
                                    <span class="custom-badge">{{__('user.Currently Active')}}</span>
                                @else
                                    @if (date('Y-m-d') < $order->expired_date)
                                        <span class="custom-badge">{{__('user.Currently Active')}}</span>
                                    @else
                                        <span class="custom-badge">{{__('user.Expired')}}</span>
                                    @endif
                                @endif
                            @endif

                        </td>
                        <td class="p_date">{{ $order->purchase_date }}</td>
                        <td class="e_date">{{ $order->expired_date == null ? trans('Unlimited') :$order->expired_date }}</td>
                        <td class="price">{{ $order->currency_icon }}{{ $order->amount_real_currency }}</td>
                        <td class="action">
                            <a href="{{ route('user.order.details',$order->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
          {{ $orders->links('custom_paginator') }}
        </div>
    </div>
</div>
@endsection
