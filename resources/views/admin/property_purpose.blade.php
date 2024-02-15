@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Property Purpose') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Property Purpose') }}</h1>
            </div>
            <div class="section-body">

                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin.SN') }}</th>
                                                <th>{{ __('admin.Purpose') }}</th>
                                                <th>{{ __('admin.Translations') }}</th>
                                                <th>{{ __('admin.Status') }}</th>
                                                <th>{{ __('admin.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purposes as $index => $purpose)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $purpose->purpose }}</td>
                                                    <td>
                                                        @forelse($languages as $key => $language)
                                                            <i
                                                                class="fa {{ $purpose->translation($language->code)?->first()?->custom_purpose ? 'fa-check' : 'fa-edit' }}"></i>
                                                            <a
                                                                href="{{ route('admin.property-purpose.translation', ['id' => $purpose->id, 'code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                                                            @if (!$loop->last)
                                                                ||
                                                            @endif
                                                        @empty
                                                            <a
                                                                href="{{ route('admin.property-purpose.translation', ['id' => $purpose->id, 'code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if ($purpose->status == 1)
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $purpose->id }})">
                                                                <input id="status_toggle" type="checkbox" checked
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @else
                                                            <a href="javascript:;"
                                                                onclick="changeBlogCategoryStatus({{ $purpose->id }})">
                                                                <input id="status_toggle" type="checkbox"
                                                                    data-toggle="toggle" data-on="{{ __('admin.Active') }}"
                                                                    data-off="{{ __('admin.Inactive') }}"
                                                                    data-onstyle="success" data-offstyle="danger">
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <a href="javascript:;" data-toggle="modal"
                                                            data-target="#modelId-{{ $purpose->id }}"
                                                            class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>
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


    @foreach ($purposes as $index => $purpose)
        <!-- Modal -->
        <div class="modal fade" id="modelId-{{ $purpose->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('admin.Edit Property Purpose') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.property-purpose.update', $purpose->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="">{{ __('admin.Default Purpose') }}</label>
                                    <input type="text" name="default_purpose" value="{{ $purpose->purpose }}"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('admin.Custom Purpose') }}</label>
                                    <input type="text" name="purpose" value="{{ $purpose->custom_purpose }}"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>{{ __('admin.Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $purpose->status == 1 ? 'selected' : '' }} value="1">
                                            {{ __('admin.Active') }}</option>
                                        <option {{ $purpose->status == 0 ? 'selected' : '' }} value="0">
                                            {{ __('admin.Inactive') }}</option>
                                    </select>
                                </div>



                                <button type="submit" class="btn btn-primary">{{ __('admin.Update') }}</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach



    <script>
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
                url: "{{ url('/admin/property-purpose-status/') }}" + "/" + id,
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
