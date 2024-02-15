@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Invoice')}}</title>
@endsection

@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Invoice')}}</h1>

          </div>
          <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>{{__('admin.Agent')}}</td>
                                        <td>{{ $order->user ? $order->user->name : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Email')}}</td>
                                        <td>{{ $order->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Package')}}</td>
                                        <td>{{ $order->package->package_name }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{__('admin.Amount')}}</td>
                                        <td>{{ $currency }}{{ $order->amount_real_currency }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{__('admin.Payment method')}}</td>
                                        <td>{{ $order->payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Transaction')}}</td>
                                        <td>{!! clean(nl2br(e($order->transaction_id))) !!} </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Purchase Date')}}</td>
                                        <td>{{ $order->purchase_date }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Expired Date')}}</td>
                                        <td>
                                             @if ($order->expired_date==null)
                                                 <span class="badge badge-success">{{__('admin.Unlimited')}}</span>
                                             @else
                                             {{ $order->expired_date }}
                                             @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{__('admin.Status')}}</td>
                                        <td>
                                         @if ($order->status==1)
                                             @if ($order->expired_date==null)
                                                 <span class="badge badge-success">{{__('admin.Active')}}</span>
                                             @else
                                                 @if (date('Y-m-d') < $order->expired_date)
                                                 <span class="badge badge-success">{{__('admin.Active')}}</span>
                                                 @else
                                                 <span class="badge badge-danger">{{__('admin.Expired')}}</span>
                                                 @endif
                                             @endif
                                        @elseif ($order->status == -1)
                                             <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                         @else
                                         <span class="badge badge-danger">{{__('admin.Expired')}}</span>
                                         @endif
                                     </td>
                                    </tr>
                                </table>

                                <button class="btn btn-danger btn-icon icon-left" data-toggle="modal" data-target="#deleteModal" onclick="deleteData({{ $order->id }})"><i class="fas fa-times"></i> {{__('admin.Delete')}}</button>

                                @if ($order->payment_status == 0)
                                <button class="btn btn-success" data-toggle="modal" data-target="#modelIdPaymemnt">{{__('admin.Payment Approved')}}</button>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
          </div>

        </section>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="modelIdPaymemnt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">{{__('admin.Payment Confirmation')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p>{{__('admin.Are You sure approved this payment?')}}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.approved-payment', $order->id) }}" class="btn btn-primary">{{__('admin.Yes, Approved')}}</a>
                </div>
            </div>
        </div>
      </div>


    <script>
        function deleteData(id){
            $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
        }
    </script>




@endsection
