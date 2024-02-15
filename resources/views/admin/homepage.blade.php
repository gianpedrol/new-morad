@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Homepage') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('admin.Homepage') }}</h1>
                <div>
                    <p>{{__('admin.Translations')}}</p>
                    @forelse($languages as $key => $language)
                        <i
                            class="fa {{ $homepage->translation($language->code)?->first()?->top_property_title ? 'fa-check' : 'fa-edit' }}"></i>
                        <a
                            href="{{ route('admin.homepage.translation', ['id' => $homepage->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                        @if (!$loop->last)
                            ||
                        @endif
                    @empty
                        <a
                            href="{{ route('admin.homepage.translation', ['id' => $homepage->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                    @endforelse
                </div>
            </div>

            <div class="section-body homepage_box">
                <form action="{{ route('admin.update-homepage') }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
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

                                    <div class="form-group">
                                        <label for="">{{ __('admin.How many item want to show ?') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->featured_property_item }}"
                                            name="featured_property_item" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('admin.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="featured_visibility" class="form-control" id="">
                                            <option {{ $homepage->featured_visibility == 1 ? 'selected' : '' }}
                                                value="1">{{ __('admin.Active') }}</option>
                                            <option {{ $homepage->featured_visibility == 0 ? 'selected' : '' }}
                                                value="0">{{ __('admin.Inactive') }}</option>
                                        </select>

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

                                    <div class="form-group">
                                        <label for="">{{ __('admin.How many item want to show ?') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" value="{{ $homepage->urgent_property_item }}"
                                            name="urgent_property_item" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('admin.Status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="urgent_visibility" class="form-control" id="">
                                            <option {{ $homepage->urgent_visibility == 1 ? 'selected' : '' }}
                                                value="1">{{ __('admin.Active') }}</option>
                                            <option {{ $homepage->urgent_visibility == 0 ? 'selected' : '' }}
                                                value="0">{{ __('admin.Inactive') }}</option>
                                        </select>

                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">{{ __('admin.Update') }}</button>
                            </div>




                        </div>
                    </div>
                </form>
        </section>
    </div>
@endsection
