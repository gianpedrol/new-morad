
@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Assign Package')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Assign Package')}}</h1>

          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.store-assign-package') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('admin.User')}}</label>
                                <select name="user" class="form-control select2" required>
                                    <option value="">{{__('admin.Select')}}</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id }}">{{$user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Package')}}</label>
                                <select name="package" class="form-control" required>
                                    <option value="">{{__('admin.Select')}}</option>
                                    @foreach ($packages as $package)
                                    <option value="{{$package->id }}">{{$package->package_name }}</option>
                                    @endforeach
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
