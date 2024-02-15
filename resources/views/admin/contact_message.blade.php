@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Contact Message')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Contact Message')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Contact Message')}}</div>
            </div>
          </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                        <div class="card-body">
                            <form action="{{ route('admin.enable-save-contact-message') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="">{{__('admin.Receive contact message email')}}</label>
                                    <input class="form-control" type="email" name="reciever_mail" value="{{ $message_setting->reciever_mail }}">
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('admin.Want to save email in database?')}}</label>
                                    <select name="save_status" id="" class="form-control">
                                        <option {{ $message_setting->save_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $message_setting->save_status == 0 ? 'selected' : '' }} value="0">{{__('admin.In-Active')}}</option>

                                    </select>
                                </div>

                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
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
                                    <th>{{__('admin.SN')}}</th>
                                    <th >{{__('admin.Name')}}</th>
                                    <th>{{__('admin.Email')}}</th>
                                    <th >{{__('admin.Phone')}}</th>
                                    <th >{{__('admin.Subject')}}</th>
                                    <th>{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($contactMessages as $index => $contactMessage)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $contactMessage->name }}</td>
                                        <td>{{ $contactMessage->email }}</td>
                                        <td>{{ $contactMessage->phone }}</td>
                                        <td>{{ $contactMessage->subject }}</td>

                                        <td>

                                            <a href="{{ route('admin.show-contact-message', $contactMessage->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $contactMessage->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
          function handleSaveContactMessage(){
            var isDemo = "{{ env('APP_VERSION') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
              $.ajax({
                type:"put",
                data: { _token : '{{ csrf_token() }}' },
                url:"{{ url('/admin/enable-save-contact-message') }}",
                success:function(response){
                   toastr.success(response)
                },
                error:function(err){
                    console.log(err);
                }
              })
          }

        function deleteData(id){
            $("#deleteForm").attr("action",'{{ url("admin/delete-contact-message/") }}'+"/"+id)
        }

      </script>
@endsection
