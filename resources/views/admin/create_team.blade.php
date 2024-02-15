@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Create Team')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Team')}}</h1>
          </div>


          <div class="section-body">
            <a href="{{ route('admin.our-team.index') }}" class="btn btn-primary mb-3"> <i class="fa fa-list" aria-hidden="true"></i> {{__('admin.Team List')}} </a>

            <div class="card">
                <div class="card-body">

                    <form action="{{ route('admin.our-team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="image">{{__('admin.Image')}} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" name="image" id="image">
                        </div>





                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">{{__('admin.Designation')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="designation" id="designation">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_icon">{{__('admin.First Icon')}}</label>
                                <input type="text" name="first_icon" class="form-control custom-icon-picker" id="first_icon" value="{{ old('first_icon') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_link">{{__('admin.First Icon Link')}}</label>
                                <input type="text" name="first_link" class="form-control" id="first_link" value="{{ old('first_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="second_icon">{{__('admin.Second icon')}}</label>
                                <input type="text" name="second_icon" class="form-control custom-icon-picker" id="second_icon" value="{{ old('second_icon') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="second_link">{{__('admin.Second Icon Link')}}</label>
                                <input type="text" name="second_link" class="form-control" id="second_link" value="{{ old('second_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="third_icon">{{__('admin.Third Icon')}}</label>
                                <input type="text" name="third_icon" class="form-control custom-icon-picker" id="third_icon" value="{{ old('third_icon') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="third_link">{{__('admin.Third Icon Link')}}</label>
                                <input type="text" name="third_link" class="form-control" id="third_link" value="{{ old('third_link') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="four_icon">{{__('admin.Fourth Icon')}}</label>
                                <input type="text" name="four_icon" class="form-control custom-icon-picker" id="four_icon" value="{{ old('four_icon') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="four_link">{{__('admin.fourth Icon Link')}}</label>
                                <input type="text" name="four_link" class="form-control" id="four_link" value="{{ old('four_link') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">{{__('admin.Status')}} <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">{{__('admin.Active')}}</option>
                            <option value="0">{{__('admin.In-active')}}</option>
                        </select>
                    </div>

                    <button class="btn btn-primary">{{__('admin.Save')}}</button>


                    </form>
                </div>
            </div>
          </div>
        </section>
      </div>

@endsection
