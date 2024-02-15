@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Footer') }} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Footer') }} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.Footer') }} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.footer.translation.update', ['id' => request('id'), 'code' => request('code')]) }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Address') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $footer->address }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.First Column Title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_column" class="form-control"
                                                value="{{ $footer->first_column }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Second Column Title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="second_column" class="form-control"
                                                value="{{ $footer->second_column }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Third Column Title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="third_column" class="form-control"
                                                value="{{ $footer->third_column }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('admin.Copyright') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="copyright" class="form-control"
                                                value="{{ $footer->copyright }}">
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
