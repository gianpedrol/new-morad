@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.About Us') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.About Us') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.About Us') }}</div>
                </div>
            </div>
            ​
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.about-us.update', $aboutUs->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <label for="">{{ __('admin.Existing Image') }}</label>
                                                    <div>
                                                        <img class="sidebar_img" src="{{ asset($aboutUs->image) }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p>{{ __('admin.Translations') }}</p>
                                                    @forelse($languages as $key => $language)
                                                        <i
                                                            class="fa {{ $aboutUs->translation($language->code)?->first()?->about_us ? 'fa-check' : 'fa-edit' }}"></i>
                                                        <a
                                                            href="{{ route('admin.about-us.translation', ['id' => $aboutUs->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                                                        @if (!$loop->last)
                                                            ||
                                                        @endif
                                                    @empty
                                                        <a
                                                            href="{{ route('admin.about-us.translation', ['id' => $aboutUs->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="">{{ __('admin.New Image') }}</label>
                                            <input type="file" name="image" class="form-control-file">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.About Us') }} <span class="text-danger">*</span></label>
                                            <textarea name="about_us" cols="30" rows="10" class="summernote">{{ $aboutUs->about_us }}</textarea>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Service') }} <span class="text-danger">*</span></label>
                                            <textarea name="service" cols="30" rows="10" class="summernote">{{ $aboutUs->service }}</textarea>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.History') }} <span class="text-danger">*</span></label>
                                            <textarea name="history" cols="30" rows="10" class="summernote">{{ $aboutUs->history }}</textarea>
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="">{{ __('admin.Team Title') }}</label>
                                            <input type="text" name="team_title" value="{{ $aboutUs->team_title }}"
                                                class="form-control">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="">{{ __('admin.Team Description') }}</label>
                                            <input type="text" name="team_description"
                                                value="{{ $aboutUs->team_description }}" class="form-control">
                                        </div>

                                        <div class="form-group col-12">
                                            <label for="">{{ __('admin.Team Status') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="team_visibility" class="form-control" id="">
                                                <option {{ $aboutUs->team_visibility == 1 ? 'selected' : '' }}
                                                    value="1">{{ __('admin.Active') }}</option>
                                                <option {{ $aboutUs->team_visibility == 0 ? 'selected' : '' }}
                                                    value="0">{{ __('admin.Inactive') }}</option>
                                            </select>

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
