@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.City List') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.City List') }}</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.city.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                    {{ __('admin.Add New') }}</a>

                <a href="{{ route('admin.city-import-page') }}" class="btn btn-success"><i class="fas fa-file-import"></i>
                    {{ __('admin.Import City') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin.SN') }}</th>
                                            <th>{{ __('admin.City') }}</th>
                                            <th>{{ __('admin.Translations') }}</th>
                                            <th>{{ __('admin.Status') }}</th>
                                            <th>{{ __('admin.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cities as $index => $city)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $city->name }}</td>
                                                <td>
                                                    @forelse($languages as $key => $language)
                                                        <i
                                                            class="fa {{ $city->translation($language->code)?->first()?->name ? 'fa-check' : 'fa-edit' }}"></i>
                                                        <a
                                                            href="{{ route('admin.city.translation', ['id' => $city->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                                                        @if (!$loop->last)
                                                            ||
                                                        @endif
                                                    @empty
                                                        <a
                                                            href="{{ route('admin.city.translation', ['id' => $city->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                    @endforelse
                                                </td>
                                                <td>
                                                    @if ($city->status == 1)
                                                        <a href="javascript:;"
                                                            onclick="changeStateStatus({{ $city->id }})">
                                                            <input id="status_toggle" type="checkbox" checked
                                                                data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                data-off="{{ __('admin.InActive') }}"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        </a>
                                                    @else
                                                        <a href="javascript:;"
                                                            onclick="changeStateStatus({{ $city->id }})">
                                                            <input id="status_toggle" type="checkbox" data-toggle="toggle"
                                                                data-on="{{ __('admin.Active') }}"
                                                                data-off="{{ __('admin.InActive') }}"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.city.edit', $city->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                            aria-hidden="true"></i></a>


                                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="deleteData({{ $city->id }})"><i class="fa fa-trash"
                                                            aria-hidden="true"></i></a>

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{ __('admin.You can not delete this city. Because there are one or more users and seller has been created in this city.') }}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('admin.Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/city/') }}' + "/" + id)
        }

        function changeStateStatus(id) {
            var isDemo = 1
            if (isDemo == 0) {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/admin/city-status/') }}" + "/" + id,
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
