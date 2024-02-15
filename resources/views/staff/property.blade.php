@extends('staff.master_layout')
@section('title')
<title>{{__('admin.Dashboard')}}</title>
@endsection
@section('staff-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Dashboard')}}</h1>
          </div>

          <div class="section-body">

            <a href="{{ route('staff.property.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> {{__('admin.Add New')}}</a>

            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>{{__('admin.SN')}}</th>
                                        <th>{{__('admin.Property')}}</th>
                                        <th>{{__('admin.Price')}}</th>
                                        <th>{{__('admin.Type')}}</th>
                                        <th>{{__('admin.Purpose')}}</th>
                                        <th>{{__('admin.Status')}}</th>
                                        <th>{{__('admin.Action')}}</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    @foreach ($properties as $index => $property)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td><a target="_blank" href="{{ route('property.details', $property->slug) }}">{{ $property->translated_title }}</a></td>
                                            <td>{{ $currency }}{{ $property->price }}</td>
                                            <td>{{ $property->propertyType->translated_type }}</td>
                                            <td>{{ $property->propertyPurpose->translated_custom_purpose }}</td>
                                            <td>
                                                @if($property->status == 1)
                                                <a href="javascript:;" onclick="changeBlogCategoryStatus({{ $property->id }})">
                                                    <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>

                                                @else
                                                <a href="javascript:;" onclick="changeBlogCategoryStatus({{ $property->id }})">
                                                    <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Active')}}" data-off="{{__('admin.Inactive')}}" data-onstyle="success" data-offstyle="danger">
                                                </a>

                                                @endif
                                            </td>
                                            <td>


                                            <a href="{{ route('staff.property.edit',$property->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>

                                            <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $property->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>

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
        $("#deleteForm").attr("action",'{{ url("staff/property/") }}'+"/"+id)
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
            url:"{{url('/staff/property-status/')}}"+"/"+id,
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
