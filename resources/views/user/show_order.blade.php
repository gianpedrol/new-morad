@extends('user.layout')
@section('title')
    <title>{{__('user.My Orders')}}</title>
@endsection
@section('user-dashboard')
<div class="row">
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
          <div class="wsus__dash_invoice">

            <div class="wsus__dash_info p_25 mb_25">
              <div class="row">
                <div class="col-xl-6 col-md-6">
                  <div class="invoice_left">
                    <a href="{{ route('home') }}">
                      <img src="{{ asset($logo) }}" alt="topland" class="img-fluid">
                    </a>
                    <h4>{{ $order->user->name }}</h4>
                    <p>{{ $order->user->email }}</p>
                    @if ($order->user->phone)
                    <p>{{ $order->user->phone }}</p>
                    @endif
                    @if ($order->user->address)
                    <p>{{ $order->user->address }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="invoice_left invoice_right">
                    <h4>{{__('user.Order Id')}}: {{ $order->order_id }}</h4>
                    <p>{{__('user.Amount')}}: {{ $order->currency_icon }}{{ $order->amount_real_currency }}</p>
                    @if ($order->payment_method)
                    <p>{{__('user.Payment')}}: {{ $order->payment_method }}</p>
                    @endif
                    @if ($order->transaction_id)
                    <p>{{__('user.Transaction')}}: {!! clean(nl2br(e($order->transaction_id))) !!} </p>
                    @endif
                  </div>
                </div>
                <div class="col-12">
                  <div class="table-responsive">
                      <table class="table">
                        <tr>
                            <th class="packages">{{__('user.Package')}}</th>
                            <th class="p_date">{{__('user.Purchase Date')}}</th>
                            <th class="e_date">{{__('user.Expired Date')}}</th>
                            <th class="amount">{{__('user.Amount')}}</th>
                        </tr>
                        <tr>
                            <td class="packages">
                                {{ $order->package->package_name }}
                            </td>
                            <td class="p_date">
                                {{ $order->purchase_date }}
                            </td>
                            <td class="e_date">
                                {{ $order->expired_date == null ? 'Unlimited' :$order->expired_date }}
                            </td>
                            <td class="amount">
                                {{ $order->currency_icon }}{{ $order->amount_real_currency }}
                            </td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <a onclick="window.print()" href="javascript:;" class="common_btn invoice_print">{{__('user.Print')}}</a>
          </div>
        </div>
    </div>
</div>
@endsection
