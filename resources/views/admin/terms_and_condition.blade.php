@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Terms And Conditions') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Terms And Conditions') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.Terms And Conditions') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.terms-and-condition.update', $termsAndCondition->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <div class="d-flex justify-content-between pb-2">
                                                <label>{{ __('admin.Terms And Conditions') }}<span
                                                        class="text-danger">*</span></label>
                                                <div>
                                                    <p>{{ __('admin.Translations') }}</p>
                                                    @forelse($languages as $key => $language)
                                                        <i
                                                            class="fa {{ $termsAndCondition->translation($language->code)?->first()?->terms_and_condition ? 'fa-check' : 'fa-edit' }}"></i>
                                                        <a
                                                            href="{{ route('admin.terms-and-condition.translation', ['id' => $termsAndCondition->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                                                        @if (!$loop->last)
                                                            ||
                                                        @endif
                                                    @empty
                                                        <a
                                                            href="{{ route('admin.terms-and-condition.translation', ['id' => $termsAndCondition->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <textarea name="terms_and_condition" cols="30" rows="10" class="summernote">{{ $termsAndCondition->terms_and_condition }}</textarea>
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
