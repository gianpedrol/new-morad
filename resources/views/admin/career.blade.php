@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Dashboard')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
      <div class="section-header">
        <h1>{{__('admin.Career')}}</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
          <div class="breadcrumb-item">{{__('admin.Career')}}</div>
        </div>
      </div>



      <div class="section-body">
        <a href="{{ route('admin.career.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>
        <div class="row mt-4">
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive table-invoice">
                    <table class="table table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th >{{__('admin.SN')}}</th>
                                <th >{{__('admin.Title')}}</th>
                                <th >{{__('admin.Deadline')}}</th>
                                <th >{{__('admin.Status')}}</th>
                                <th >{{__('admin.Action')}}</th>
                              </tr>
                        </thead>
                        <tbody>
                            @foreach ($careers as $index => $career)
                                <tr>
                                    <td>{{ ++$index }}</td>
                                    <td><a href="">{{ $career->title }}</a></td>
                                    <td>{{ $career->deadline }}</td>
                                    <td>
                                        @if($career->status == 1)
                                        <a href="javascript:;" onclick="changeBlogStatus({{ $career->id }})">
                                            <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                        </a>

                                        @else
                                        <a href="javascript:;" onclick="changeBlogStatus({{ $career->id }})">
                                            <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                        </a>

                                        @endif
                                    </td>
                                    <td>
                                    <a href="{{ route('admin.career.edit',$career->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $career->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin.career-request', $career->id) }}" class="btn btn-success btn-sm">Request({{ $career->total_application }})</a>
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
    $("#deleteForm").attr("action",'{{ url("admin/career/") }}'+"/"+id)
}
function changeBlogStatus(id){
    var isDemo = "{{ env('PROJECT_MODE') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
    $.ajax({
        type:"put",
        data: { _token : '{{ csrf_token() }}' },
        url:"{{url('/admin/career-status/')}}"+"/"+id,
        success:function(response){
            toastr.success(response)
        },
        error:function(err){
            console.log(err);

        }
    })
}
</script>
@endsection
