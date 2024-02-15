@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Languages')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Languages')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a
                        href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('admin.Languages')}}</div>
            </div>
        </div>

        <div class="section-body">
            <a href="{{ route('admin.languages.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                {{__('admin.Add New')}}</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">{{__('admin.SN')}}</th>
                                            <th width="30%">{{__('admin.Name')}}</th>
                                            <th width="10%">{{__('admin.Code')}}</th>
                                            <th width="15%">{{__('admin.Direction')}}</th>
                                            <th width="15%">{{__('admin.Translations')}}</th>
                                            <th width="15%">{{__('admin.Default')}}</th>
                                            <th width="15%">{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($languages as $index => $language)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $language->name }}</td>
                                            <td>{{ $language->code }}</td>
                                            <td>{{ $language->direction }}</td>
                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton2" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false" title="{{ __('admin.Edit Languages') }}">
                                                        <i class="fas fa-language"></i>
                                                    </button>

                                                    <div class="dropdown-menu" x-placement="top-start"
                                                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);">
                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.admin-language', $language->code) }}"><i
                                                                class="fa fa-cog"></i>
                                                            {{__('admin.Admin Language')}} ({{ $language->code }})</a>
                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.admin-validation-language', $language->code) }}"><i
                                                                class="fa fa-cog"></i>
                                                            {{__('admin.Admin Validation')}} ({{ $language->code }})</a>
                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.website-language', $language->code) }}"><i
                                                                class="fa fa-cog"></i>
                                                            {{__('admin.Frontend Language')}} ({{ $language->code }})</a>
                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.website-validation-language', $language->code) }}"><i
                                                                class="fa fa-cog"></i>
                                                            {{__('admin.Frontend Validation')}} ({{ $language->code }})</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                            @if(strtolower($language->code) === strtolower(app()->getLocale()))
                                            <input id="status_toggle" type="checkbox" checked data-toggle="toggle" data-on="{{__('admin.Yes')}}" data-off="{{__('admin.No')}}" data-onstyle="success" data-offstyle="danger" disabled>
                                            @else
                                            <input id="status_toggle" type="checkbox" data-toggle="toggle" data-on="{{__('admin.Yes')}}" data-off="{{__('admin.No')}}" data-onstyle="success" data-offstyle="danger" disabled>
                                            @endif
                                            </td>
                                            <td>
                                                @if($language->code !== 'en')
                                                <a href="{{ route('admin.languages.edit',$language->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="fa fa-edit"
                                                        aria-hidden="true"></i></a>
                                                @if(strtolower($language->code) !== strtolower(app()->getLocale()))
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="deleteData({{ $language->id }})"><i class="fa fa-trash"
                                                        aria-hidden="true"></i></a>
                                                @endif
                                                @endif
                                            </td>

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
        $("#deleteForm").attr("action", '{{ url("admin/languages/") }}' + "/" + id)
    }

</script>
@endsection
