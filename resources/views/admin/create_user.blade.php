
@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Create User')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create User')}}</h1>

          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.store-user') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="">{{__('admin.Name')}}</label>
                                <input type="text" name="name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Email')}}</label>
                                <input type="text" name="email" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Password')}}</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Status')}}</label>
                                <select name="status" class="form-control">
                                    <option value="1">{{__('admin.Active')}}</option>
                                    <option value="0">{{__('admin.Inactive')}}</option>
                                </select>
                            </div>


                            <button class="btn btn-primary">{{__('admin.Save')}}</button>



                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>

@endsection
