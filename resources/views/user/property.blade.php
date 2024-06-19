@extends('user.layout')
@section('title')
    <title>{{ __('user.My Property') }}</title>
@endsection


@section('user-dashboard')
    <div class="row">
        <div class="col-xl-9 ms-auto">
            <div class="wsus__dashboard_main_content">
                <div class="wsus__my_property">
                    <h4 class="heading">{{ __('user.My Property') }} <a href="{{ route('user.create.property') }}"
                            class="common_btn"><i class="fal fa-plus-octagon"></i> {{ __('user.Creae New') }}</a></h4>
                    <div class="row">

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th class="image">
                                            {{ __('user.Image') }}
                                        </th>
                                        <th class="title">
                                            {{ __('user.Property') }}
                                        </th>
                                        <th class="purpose">
                                            {{ __('user.Purpose') }}
                                        </th>
                                        <th class="status">
                                            {{ __('user.Status') }}
                                        </th>
                                        <th class="actions">
                                            {{ __('user.Action') }}
                                        </th>
                                    </tr>
                                    @foreach ($properties as $item)
                                        <tr>
                                            <td class="image">
                                                <a href="{{ route('property.details', $item->slug) }}" target="_blank">
                                                    <img src="{{ isset($item->thumbnail_image) ? url($item->thumbnail_image) : '' }}" alt="img" class="img-fluid w-100">
                                                </a>
                                            </td>
                                            <td class="title">
                                                <h5><a
                                                        href="{{ route('property.details', $item->slug) }}" target="_blank">{{ $item->translated_title }}</a>
                                                </h5>
                                                <p class="address">
                                                    {{ $item->address }}
                                                    @if($item->number)
                                                        , {{ $item->number }}
                                                    @endif
                                                    @if($item->complemento)
                                                    , {{ $item->complemento }}
                                                    @endif
                                                    , {{ $item->city->translated_name }}
                                                </p>

                                            </td>
                                            <td class="purpose">
                                                <p>{{ $item->propertyPurpose->translated_custom_purpose == 'Comprar' ? 'Venda' : 'Locação'}}</p>
                                            </td>
                                            <td class="status">
                                                @if ($item->status == 1)
                                                    @if ($item->expired_date == null)
                                                        <p class="active">{{ __('user.Active') }}</p>
                                                    @elseif($item->expired_date >= date('Y-m-d'))
                                                        <p class="active">{{ __('user.Active') }}</p>
                                                    @else
                                                        <p>{{ __('user.Inactive') }}</p>
                                                    @endif
                                                @else
                                                    <p>{{ __('user.Inactive') }}</p>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <ul class="d-flex">
                                                    <li><a href="{{ route('property.details', $item->slug) }}" target="_blank"><i
                                                                class="far fa-eye"></i></a></li>
                                                    <li><a href="{{ route('user.property.edit', $item->id) }}"><i
                                                                class="fal fa-edit"></i></a></li>
                                                    <li><a onclick="deleteProperty('{{ $item->id }}')"><i
                                                                class="far fa-trash-alt"></i></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt_25">
                            {{ $properties->links('custom_paginator') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteProperty(id) {
            Swal.fire({
                title: "{{ __('user.Are You Sure ?') }}",
                text: "{{ __('user.Do you want to delete this item ?') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('user.Yes, Delete') }}",
                cancelButtonText: "{{ __('user.Cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        "{{ __('user.Congratulations!') }}",
                        "{{ __('user.Delete Successfully') }}",
                        'success'
                    )
                    location.href = "{{ url('user/delete-property/') }}" + "/" + id;
                }
            })
        }
    </script>
@endsection
