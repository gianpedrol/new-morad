@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Edit Property') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Property') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</h1>
            </div>
            <div class="section-body">
                @if (request('type') == 'agent')
                <a href="{{ route('admin.agent-property') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('admin.Property List') }}</a>
                @else
                <a href="{{ route('admin.property.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('admin.Property List') }}</a>
                @endif
                <div class="row mt-4">
                    @if (request('type') == 'agent')
                    <form action="{{ route('admin.property.translation.update', ['id' => request('id'), 'code' => request('code'), 'type' => request('type')]) }}" method="POST">
                    @else
                    <form action="{{ route('admin.property.translation.update', ['id' => request('id'), 'code' => request('code')]) }}" method="POST">
                    @endif
                        @csrf
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{ __('admin.Basic Information') }}</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label for="title">{{ __('admin.Title') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" id="title"
                                            value="{{ $property->title }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{ __('admin.Description') }}</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group mt-3">
                                            <label for="description">{{ __('admin.Description') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="summernote" id="summernote" name="description">{{ $property->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <button class="btn btn-success">{{ __('admin.Save') }}</button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
    </div>
    </section>
    </div>
@endsection
