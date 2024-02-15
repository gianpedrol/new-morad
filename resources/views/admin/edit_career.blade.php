@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Career')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Career')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.career.index') }}">{{__('admin.Career')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Career')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.career.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Career')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.career.update',$career->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($career->image) }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" id="image" class="form-control-file"  name="image">
                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="title" class="form-control"  name="title" value="{{ $career->title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $career->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Salary Range')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="salary_range" class="form-control"  name="salary_range" value="{{ $career->salary_range }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Job Location')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="address" class="form-control"  name="address" value="{{ $career->address }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Job Nature')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="job_nature" class="form-control"  name="job_nature" value="{{ $career->job_nature }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Office Time')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="office_time" class="form-control"  name="office_time" value="{{ $career->office_time }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Deadline')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="deadline" class="form-control datepicker"  name="deadline" value="{{ $career->deadline }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="summernote" cols="30" rows="10" class="summernote">{{ $career->description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $career->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $career->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

<script>
    (function($) {
        "use strict";
        $(document).ready(function () {
            $("#title").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })
        });
    })(jQuery);

    function convertToSlug(Text)
        {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
        }
</script>
@endsection
