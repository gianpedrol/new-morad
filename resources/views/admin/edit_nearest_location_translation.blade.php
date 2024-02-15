@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Edit Nearest Location') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})
    </title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Nearest Location') }} {{ __('admin.Translations') }}
                    ({{ strtoupper(request('code')) }})</h1>
            </div>
            <div class="section-body">
                <a href="{{ route('admin.nearest-location.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('admin.Nearest Location') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.nearest-location.translation.update', ['id' => request('id'), 'code' => request('code')]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Location') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="location"
                                                value="{{ $location->location }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('admin.Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
