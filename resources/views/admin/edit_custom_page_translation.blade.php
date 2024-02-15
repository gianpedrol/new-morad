@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Custom Page') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Custom Page') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})
                </h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.custom-page.index') }}">{{ __('admin.Custom Page') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.Edit Custom Page') }} {{ __('admin.Translations') }}
                        ({{ strtoupper(request('code')) }})</div>
                </div>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.custom-page.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('admin.Custom Page') }}</a>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.custom-page.translation.update', ['id' => request('id'), 'code' => request('code')]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Page Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="page_name" class="form-control" name="page_name"
                                                value="{{ $customPage->page_name }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" cols="30" rows="10" class="summernote">{{ $customPage->description }}</textarea>
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
