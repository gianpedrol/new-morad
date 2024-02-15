@extends('admin.master_layout')
@section('title')
<title>{{ $title }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $title }}</h1>

          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th width="5%">{{__('admin.SN')}}</th>
                                    <th width="10%">{{__('admin.Agent')}}</th>
                                    <th width="10%">{{__('admin.Email')}}</th>
                                    <th width="10%">{{__('admin.Package')}}</th>
                                    <th width="15%">{{__('admin.Purchase Date')}}</th>
                                    <th width="15%">{{__('admin.Expired Date')}}</th>
                                    <th width="5%">{{__('admin.Price')}}</th>
                                    <th width="5%">{{__('admin.Payment')}}</th>
                                    <th width="10%">{{__('admin.Active')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $index => $order)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $order->user ? $order->user->name : '' }}</td>
                                        <td>{{ $order->user ? $order->user->email : '' }}</td>
                                        <td>{{ $order->package->package_name }}</td>
                                        <td>{{ $order->purchase_date }}</td>
                                        <td>
                                            @if ($order->expired_date==null)
                                            {{__('admin.Unlimited')}}
                                            @else
                                                {{ $order->expired_date }}
                                            @endif
                                        </td>
                                        <td>{{ $currency }}{{ $order->amount_real_currency }}</td>
                                        <td>
                                            @if ($order->renew_payment_status==1)
                                            <span class="badge badge-success">{{__('admin.success')}}</span>

                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>

                                        <td>

                                        <a href="{{ route('admin.renew-order-show',$order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                        </td>
                                    </tr>
                                  @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


<script>
    function deleteData(id){
        $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
    }
</script>
@endsection
