@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Menu Visibility')}} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Menu Visibility')}} {{ __('admin.Translations') }} ({{ strtoupper(request('code')) }})</h1>
          </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.menu-visibility.translation.update', request('code')) }}" method="POST">
                                @csrf

                                <table class="table table-bordered">
                                    <tr>
                                        <th width="50%">{{__('admin.Default')}}</th>
                                        <th width="50%">{{__('admin.Custom')}}</th>
                                    </tr>

                                    @foreach ($menus as $menu)
                                        <tr>
                                            <td>{{ $menu->menuVisibility?->menu_name ?? '' }}</td>
                                            <td>
                                                <input class="form-control" type="text" value="{{ $menu->custom_name }}" name="customs[]">
                                            </td>
                                            <input type="hidden" name="ids[]" value="{{ $menu->id }}">
                                        </tr>
                                    @endforeach
                                </table>

                                <button class="btn btn-primary">{{__('admin.Update')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </section>
      </div>

@endsection
