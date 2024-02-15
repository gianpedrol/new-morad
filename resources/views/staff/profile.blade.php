@extends('staff.master_layout')
@section('title')
<title>{{__('admin.My Profile')}}</title>
@endsection
@section('staff-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.My Profile')}}</h1>
          </div>

          <div class="section-body">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('staff.update.profile') }}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Image Preview')}}</label>
                                                <div>
                                                    @if ($admin->image)
                                                    <img class="img-thumbnail" src="{{ url($admin->image) }}" alt="default user image" width="100px">

                                                    @else
                                                    <img class="img-thumbnail" src="{{ url($default_profile) }}" alt="default user image" width="100px">

                                                    @endif
                                                </div>
                                                <label for="" class="mt-2">{{__('admin.New Image')}}</label>
                                                <input type="file" class="form-control-file" name="image">

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Name')}}</label>
                                                <input type="text" class="form-control" value="{{ $admin->name }}" name="name">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Email')}}</label>
                                                <input type="text" class="form-control" value="{{ $admin->email }}" name="email" readonly>

                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.New Password')}}</label>
                                                <input  type="password" class="form-control" name="password">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Confirm Password')}}</label>
                                                <input type="password" class="form-control" name="password_confirmation">
                                            </div>
                                        </div>

                                    </div>

                                    <button class="btn btn-primary" type="submit">{{__('admin.Update')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </section>
      </div>
@endsection
