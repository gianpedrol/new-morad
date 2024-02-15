@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Create City')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create City')}}</h1>

          </div>

          <div class="section-body">
            <a href="{{ route('admin.city.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.City List')}}</a>

            <a href="{{ route('admin.city-export') }}" class="btn btn-success"><i class="fas fa-download"></i> {{__('admin.Download XLSX')}}</a>

            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.city-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.File')}} <span class="text-danger">*</span></label>
                                    <input required type="file" id="file" class="form-control"  name="file">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Upload')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>

          <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>{{__('admin.Name')}}</td>
                            <td>{{__('admin.City name will be unique. You can not use duplicate name')}}</td>
                        </tr>

                        <tr>
                            <td>{{__('admin.Slug')}}</td>
                            <td>{{__('admin.Slug will be always small latter and without space. Also you can not use duplicate')}}</td>
                        </tr>

                        <tr>
                            <td>{{__('admin.Status')}}</td>
                            <td>{{__('admin.1 = active , 0 = Inactive')}}</td>
                        </tr>







                    </table>
                </div>
            </div>
          </div>

        </section>
      </div>
@endsection
