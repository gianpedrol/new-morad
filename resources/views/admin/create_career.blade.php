@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Career')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Career')}}</h1>

          </div>

          <div class="section-body">
            <a href="{{ route('admin.career.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Career')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.career.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" id="image" class="form-control-file"  name="image">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="title" class="form-control"  name="title" value="{{ old('title') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ old('slug') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Salary Range')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="salary_range" class="form-control"  name="salary_range" value="{{ old('salary_range') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Job Location')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="address" class="form-control"  name="address" value="{{ old('address') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Job Nature')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="job_nature" class="form-control"  name="job_nature" value="{{ old('job_nature') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Office Time')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="office_time" class="form-control"  name="office_time" value="{{ old('office_time') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Deadline')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="deadline" class="form-control datepicker"  name="deadline" value="{{ old('deadline') }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="summernote" cols="30" rows="10" class="summernote">{{ old('description') }}</textarea>
                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
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
