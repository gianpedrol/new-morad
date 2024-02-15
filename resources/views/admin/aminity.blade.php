@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Aminities') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Aminities') }}</h1>
            </div>
            <div class="section-body">
                <a href="{{ route('admin.aminity.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                    {{ __('admin.Add New') }}</a>
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin.SN') }}</th>
                                                <th>{{ __('admin.Aminity') }}</th>
                                                <th>{{ __('admin.Translations') }}</th>
                                                <th>{{ __('admin.Status') }}</th>
                                                <th>{{ __('admin.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($aminities as $index => $aminity)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $aminity->aminity }}</td>
                                                    <td>
                                                        @forelse($languages as $key => $language)
                                                            <i
                                                                class="fa {{ $aminity->translation($language->code)?->first()?->aminity ? 'fa-check' : 'fa-edit' }}"></i>
                                                            <a
                                                                href="{{ route('admin.aminity.translation', ['id' => $aminity->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                                                            @if (!$loop->last)
                                                                ||
                                                            @endif
                                                        @empty
                                                            <a
                                                                href="{{ route('admin.aminity.translation', ['id' => $aminity->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if ($aminity->status == 1)
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $aminity->id }})">
                                                                <input id="status_toggle" type="checkbox" checked
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @else
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $aminity->id }})">
                                                                <input id="status_toggle" type="checkbox"
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <a href="{{ route('admin.aminity.edit', $aminity->id) }}"
                                                            class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>

                                                        <a href="javascript:;" data-toggle="modal"
                                                            data-target="#deleteModal" class="btn btn-danger btn-sm"
                                                            onclick="deleteData({{ $aminity->id }})"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/aminity/') }}' + "/" + id)
        }

        function changeBlogCategoryStatus(id) {

            var isDemo = "{{ env('PROJECT_MODE') }}"
            if (isDemo == 0) {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/admin/aminity-status/') }}" + "/" + id,
                success: function(response) {
                    toastr.success(response)
                },
                error: function(err) {
                    console.log(err);

                }
            })
        }
    </script>
@endsection
