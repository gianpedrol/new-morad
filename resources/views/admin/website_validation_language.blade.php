@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Frontend Validation Language') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Frontend Validation Language') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                            <div class="breadcrumb-item active"><a href="{{ route('admin.languages.index') }}">{{ __('admin.Manage Language') }}</a>
                                                </div>
                    <div class="breadcrumb-item">{{ __('admin.Frontend Validation Language') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('admin.Frontend Validation Language') }}</h4>
                                <button type="button" id="translateAll" class="btn btn-primary"
                                    data-code="{{ request('code') }}"
                                    data-file="user_validation">{{ __('admin.Translate All') }}</button>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.update-validation-language', request('code')) }}"
                                    method="post">
                                    @csrf
                                    <table class="table table-bordered">
                                        @foreach ($data as $index => $value)
                                            <tr>
                                                <td width="50%">{{ $index }}</td>
                                                <td width="50%">
                                                    <input type="text" class="form-control"
                                                        name="values[{{ $index }}]" value="{{ $value }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <button type="submit" class="btn btn-primary">{{ __('admin.Update') }}</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>

    <script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
    <script>
        $('#translateAll').click(function() {
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'This will take a while!',
                message: 'Are you sure?',
                position: 'center',
                buttons: [
                    ['<button><b>YES</b></button>', function(instance, toast) {
                        var isDemo = "{{ env('PROJECT_MODE') }}";
                        var code = $('#translateAll').data('code');
                        var file = $('#translateAll').data('file');

                        if (isDemo == 0) {
                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');
                            toastr.error('This Is Demo Version. You Can Not Change Anything');
                            return;
                        }

                        $.ajax({
                            type: "post",
                            data: {
                                _token: '{{ csrf_token() }}',
                                code: code,
                                file: file,
                            },
                            url: "{{ route('admin.translateAll') }}",
                            beforeSend: function() {
                                instance.hide({
                                    transitionOut: 'fadeOut'
                                }, toast, 'button');

                                iziToast.show({
                                    timeout: false,
                                    close: true,
                                    theme: 'dark',
                                    // icon: 'loader',
                                    iconUrl: 'https://hub.izmirnic.com/Files/Images/loading.gif',
                                    title: 'This will take a while! wait....',
                                    position: 'center',
                                });
                            },
                            success: function(response) {
                                if (response.success) {
                                    iziToast.destroy();
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                }
                            },
                            error: function(err) {
                                iziToast.destroy();
                                toastr.error('Failed!')
                                console.log(err);
                            },
                        })

                    }, true],
                    ['<button>NO</button>', function(instance, toast) {

                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');

                    }],
                ],
                onClosing: function(instance, toast, closedBy) {},
                onClosed: function(instance, toast, closedBy) {}
            });
        });
    </script>
@endsection
