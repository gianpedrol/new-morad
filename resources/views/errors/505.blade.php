@php
    $error_505=App\Models\ErrorPage::find(3);
@endphp
@extends('layout')
@section('title')
    <title>{{ $error_505->page_name }}</title>
@endsection
@section('user-content')

<!--============================
        404 PAGE START
    ==============================-->
    <section id="wsus__404">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-md-10 col-lg-8 col-xxl-6 m-auto">
                    <div class="wsus__404_text">
                        <h2>{{ $error_505->page_number }}</h2>
                        <h4>{{ $error_505->header }}</h4>
                        <p>{{ $error_505->description }}</p>
                        <a href="{{ route('home') }}" class="common_btn">{{__('Go Back Home')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--============================
        404 PAGE END
    ==============================-->
@endsection


