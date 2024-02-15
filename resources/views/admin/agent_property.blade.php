@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Agent Property') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Agent Property') }}</h1>
            </div>
            <div class="section-body">
                <a href="{{ route('admin.property.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
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
                                                <th>{{ __('admin.Agent') }}</th>
                                                <th>{{ __('admin.Property') }}</th>
                                                <th>{{ __('admin.Price') }}</th>
                                                <th>{{ __('admin.Type') }}</th>
                                                <th>{{ __('admin.Purpose') }}</th>
                                                <th>{{ __('admin.Translations') }}</th>
                                                <th>{{ __('admin.Status') }}</th>
                                                <th>{{ __('admin.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($properties as $index => $property)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td><a
                                                            href="{{ route('admin.agent-show', $property->user_id) }}">{{ $property->user ? $property->user->name : '' }}</a>
                                                    </td>
                                                    <td><a target="_blank"
                                                            href="{{ route('property.details', $property->slug) }}">{{ $property->title }}</a>
                                                    </td>
                                                    <td>{{ $currency }}{{ $property->price }}</td>
                                                    <td>{{ $property->propertyType->type }}</td>
                                                    <td>{{ $property->propertyPurpose->custom_purpose }}</td>
                                                    <td>
                                                        @forelse($languages as $key => $language)
                                                            <i
                                                                class="fa {{ $property->translation($language->code)?->first()?->title ? 'fa-check' : 'fa-edit' }}"></i>
                                                            <a
                                                                href="{{ route('admin.property.translation', ['id' => $property->id, 'code' => $language->code, 'type' => 'agent']) }}">{{ strtoupper($language->code) }}</a>
                                                            @if (!$loop->last)
                                                                ||
                                                            @endif
                                                        @empty
                                                            <a
                                                                href="{{ route('admin.property.translation', ['id' => $property->id, 'code' => $language->code, 'type' => 'agent']) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if ($property->status == 1)
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $property->id }})">
                                                                <input id="status_toggle" type="checkbox" checked
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @else
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $property->id }})">
                                                                <input id="status_toggle" type="checkbox"
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>


                                                        <a href="{{ route('admin.property.edit', $property->id) }}"
                                                            class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>

                                                        <a href="javascript:;" data-toggle="modal"
                                                            data-target="#deleteModal" class="btn btn-danger btn-sm"
                                                            onclick="deleteData({{ $property->id }})"><i
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
            $("#deleteForm").attr("action", '{{ url('admin/property/') }}' + "/" + id)
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
                url: "{{ url('/admin/property-status/') }}" + "/" + id,
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
