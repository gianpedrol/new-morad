@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Dashboard')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>{{__('admin.Dashboard')}}</h1>
      </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Today Order')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $today_order->qty }}
                        </div>
                      </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Today Income')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $currency }}{{ $today_order->amount }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.This Month Order')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $this_month_order->qty }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.This Month Income')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $currency }}{{ $this_month_order->amount }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-success">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.This Year Order')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $this_year_order->qty }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.This Year Income')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $currency }}{{ $this_year_order->amount }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-success">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Total Order')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $total_order->qty }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Total Income')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $currency }}{{ $total_order->amount }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas far fa-building"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Active Property')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $active_property }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas far fa-building"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Inactive Property')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $inactive_property }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Total Agent')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $total_agent }}
                        </div>
                      </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>{{__('admin.Total Subscriber')}}</h4>
                        </div>
                        <div class="card-body">
                          {{ $total_subscriber }}
                        </div>
                      </div>
                    </div>
                </div>

            </div>
        </div>
@endsection
