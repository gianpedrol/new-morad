@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Homepage') }} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Homepage') }} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</h1>
            </div>

            <div class="section-body homepage_box">
                <form
                    action="{{ route('admin.homepage.translation.update', ['id' => request('id'), 'code' => request('code')]) }}"
                    method="POST">
                    @csrf
                    <div class="row mt-4">
                        <div class="col">
                                  <div class="card mb-3">
                                <div class="card-body">
                                    <h6>{{ __('admin.Featured Property') }}</h6>
                                    <hr>
                                    <div class="form-group">
                                        <label for="">{{ __('admin.Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->featured_property_title }}"
                                            name="featured_property_title" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('admin.Description') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->featured_property_description }}"
                                            name="featured_property_description" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6>{{ __('admin.Urgent Property') }}</h6>
                                    <hr>
                                    <div class="form-group">
                                        <label for="">{{ __('admin.Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->urgent_property_title }}"
                                            name="urgent_property_title" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('admin.Description') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->urgent_property_description }}"
                                            name="urgent_property_description" class="form-control">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </form>
        </section>
    </div>
@endsection
