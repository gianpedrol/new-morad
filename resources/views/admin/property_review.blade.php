@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Property Review')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Property Review')}}</h1>
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
                            <th width="15%">{{__('admin.Name')}}</th>
                            <th width="25%">{{__('admin.Comment')}}</th>
                            <th width="20%">{{__('admin.Rating')}}</th>
                            <th width="20%">{{__('admin.Property')}}</th>
                            <th width="15%">{{__('admin.Status')}}</th>
                            <th width="5%">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $index => $item)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td><a href="{{ route('admin.regular-user-show', $item->user_id) }}">{{ $item->user ? $item->user->name : '' }}</a></td>
                                        <td>{{ $item->comment }}</td>
                            <td>
                                {{__('admin.Service')}}= {{ $item->service_rating }} <i class="fas fa-star" aria-hidden="true"></i>
                                <br>
                                {{__('admin.Location')}}= {{ $item->location_rating }} <i class="fas fa-star" aria-hidden="true"></i>
                                <br>
                                {{__('admin.Value')}}= {{ $item->money_rating }} <i class="fas fa-star" aria-hidden="true"></i>
                                <br>
                                {{__('admin.Cleanliness')}}= {{ $item->clean_rating }} <i class="fas fa-star" aria-hidden="true"></i>
                                <br>
                                {{__('admin.Avarage')}}= {{ $item->avarage_rating }} <i class="fas fa-star" aria-hidden="true"></i>
                            </td>
                            <td><a target="_blank" href="{{ route('property.details',$item->property->slug) }}">{{ $item->property->title }}</a></td>

                                        <td>
                                            @if($item->status == 1)
                                            <a href="javascript:;" onclick="changeBlogCategoryStatus({{ $item->id }})">
                                                <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>

                                            @else
                                            <a href="javascript:;" onclick="changeBlogCategoryStatus({{ $item->id }})">
                                                <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                            </a>

                                            @endif
                                        </td>
                                        <td>

                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $item->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>

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
        $("#deleteForm").attr("action",'{{ url("admin/property-review-delete/") }}'+"/"+id)
    }

    function changeBlogCategoryStatus(id){
        var isDemo = "{{ env('PROJECT_MODE') }}"
        if(isDemo == 0){
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }

        $.ajax({
            type:"put",
            data: { _token : '{{ csrf_token() }}' },
            url:"{{url('/admin/property-review-status/')}}"+"/"+id,
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
