@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Slider') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Slider') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.Edit Slider') }} {{ __('admin.Translations') }}
                        ({{ strtoupper(request('code')) }})</div>
                </div>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.slider.index') }}" class="btn btn-primary"><i class="fas fa-backward"></i>
                    {{ __('admin.Go Back') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.slider.translation.update', ['id' => request('id'), 'code' => request('code')]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Title') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $slider->title }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('admin.Update') }}</button>
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
