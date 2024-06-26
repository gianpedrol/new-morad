@extends('layout')
@section('title')
    <title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_text->seo_description }}">
@endsection

@section('user-content')


  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ $banner_image ? url($banner_image) : '' }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Pricing Plan')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Pricing Plan')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!--===BREADCRUMB PART END====-->


<!--=======PRICING START=========-->
<section class="wsus__pricing mt_45 mb_20">
    <div class="container">
        <div class="row">
            @foreach ($packages as $item)
                <div class="col-xl-4 col-md-6 col-lg-4">
                    <div class="wsus__single_price">
                    <h4>{{ $item->translated_package_name }}</h4>
                    <div class="wsus__round_area">
                        <h3>{{ $currency }}{{ $item->price }}</h3>
                        @if ($item->number_of_days==-1)
                        <p>{{__('user.Unlimited')}}</p>
                        @else
                        <p>{{ $item->number_of_days }} {{__('user.Days')}}</p>
                        @endif
                        <i class="fab fa-canadian-maple-leaf right"></i>
                    </div>
                    <ul>

                        @if ($item->number_of_property==-1)
                        <li>{{__('user.Unlimited Property Submission')}}</li>
                        @else
                        <li>{{ $item->number_of_property }} {{__('user.Propertiy Submission')}}</li>
                        @endif
                    </ul>
                    @if ($item->package_type == 0)
                    <a href="javascript:;" onclick="freeEnroll('{{ $item->id }}')" class="common_btn">{{__('user.Start With')}} {{ $item->translated_package_name }}</a>
                    @else

                    <a href="{{ route('user.purchase.package',$item->id) }}" class="common_btn">Anuncie já</a>
                    @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!--=======PRICING END========-->



<script>
    function freeEnroll(id){
        Swal.fire({
            title: "{{__('user.Are You Sure ?')}}",
            text: "{{__('user.You will also upgrade your plan!')}}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('user.Yes, Enroll It')}}",
            cancelButtonText: "{{__('user.Cancel')}}",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    "{{__('user.Enrolled')}}",
                    "{{__('user.Congrats to Enroll our Free Plan')}}",
                    'success'
                )
                location.href = "{{ url('user/purchase-package/') }}" + "/" + id;
            }
        })
    }
</script>

@endsection
