@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Edit Team') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Team') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.our-team.index') }}" class="btn btn-primary mb-3"> <i class="fa fa-list"
                        aria-hidden="true"></i> {{ __('admin.Team List') }} </a>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.our-team.translation.update', ['id' => request('id'), 'code' => request('code')]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ __('admin.Name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ $team->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="designation">{{ __('admin.Designation') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designation" id="designation"
                                            value="{{ $team->designation }}">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary">{{ __('admin.Update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
