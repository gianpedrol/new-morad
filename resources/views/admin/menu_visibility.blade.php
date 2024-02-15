@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Menu Visibility') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('admin.Menu Visibility') }}</h1>
                <div>
                    <b>{{ __('admin.Translations') }}</b>
                    <br>
                    @forelse($languages as $key => $language)
                        <i
                            class="fa {{ \App\Models\MenuVisibilityTranslation::where('language_code', $language->code)?->count() > 0 ? 'fa-check' : 'fa-edit' }}"></i>
                        <a
                            href="{{ route('admin.menu-visibility.translation', ['code' => $language->code]) }}">{{ strtoupper($language->code) }}</a>
                        @if (!$loop->last)
                            ||
                        @endif
                    @empty
                        <a
                            href="{{ route('admin.menu-visibility.translation', ['code' => $language->code]) }}">{{ strtoupper(config('app.locale')) }}</a>
                    @endforelse
                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-menu-visibility') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="33%">{{ __('admin.Default') }}</th>
                                            <th width="33%">{{ __('admin.Custom') }}</th>
                                            <th width="33%">{{ __('admin.Status') }}</th>
                                        </tr>

                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td>{{ $menu->menu_name }}</td>
                                                <td>
                                                    <input class="form-control" type="text"
                                                        value="{{ $menu->custom_name }}" name="customs[]">
                                                </td>
                                                <input type="hidden" name="ids[]" value="{{ $menu->id }}">
                                                <td>
                                                    <select name="status[]" id="" class="form-control">
                                                        <option {{ $menu->status == 1 ? 'selected' : '' }} value="1">
                                                            {{ __('admin.Active') }}</option>
                                                        <option {{ $menu->status == 0 ? 'selected' : '' }} value="0">
                                                            {{ __('admin.Inactive') }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>

                                    <button class="btn btn-primary">{{ __('admin.Update') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
