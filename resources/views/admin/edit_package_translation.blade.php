@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Edit Package') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Edit Package') }} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</h1>
            </div>
            <div class="section-body">
                <a href="{{ route('admin.package.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('admin.Package') }}</a>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.package.translation.update', ['id' => request('id'), 'code' => request('code')]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="package_name">{{ __('admin.Package Name') }}<span class="text-danger">*</span></label>
                                                <input type="text" name="package_name" class="form-control"
                                                    id="package_name" value="{{ $package->package_name }}">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">{{ __('admin.Save') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>



    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#package_type").on('change', function() {
                    var type = $("#package_type").val()
                    if (type == 0) {
                        $("#price-row").addClass('d-none')
                    }
                    if (type == 1) {
                        $("#price-row").removeClass('d-none')
                    }

                })

                $("#feature").on('change', function() {
                    var type = $("#feature").val()
                    if (type == 0) {
                        $("#feature-row").addClass('d-none')
                    }
                    if (type == 1) {
                        $("#feature-row").removeClass('d-none')
                    }

                })

                $("#top_property").on('change', function() {
                    var type = $("#top_property").val()
                    if (type == 0) {
                        $("#top-row").addClass('d-none')
                    }
                    if (type == 1) {
                        $("#top-row").removeClass('d-none')
                    }

                })

                $("#urgent").on('change', function() {
                    var type = $("#urgent").val()
                    if (type == 0) {
                        $("#urgent-row").addClass('d-none')
                    }
                    if (type == 1) {
                        $("#urgent-row").removeClass('d-none')
                    }

                })

                $("#package_order").on('keyup', function() {
                    var text = $("#package_order").val()
                    if (isNaN(text)) {
                        $("#order-error").text('Please insert positive number')
                        $("#order-error").removeClass('d-none')
                    } else {
                        if (text < 0) {
                            $("#order-error").text('Please insert positive number')
                            $("#order-error").removeClass('d-none')
                        } else {
                            $("#order-error").addClass('d-none')
                        }
                    }
                })


            });

        })(jQuery);
    </script>
@endsection
