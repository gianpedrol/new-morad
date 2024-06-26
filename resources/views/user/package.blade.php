@extends('user.layout')
@section('title')
<title>{{__('user.Pricing Plan')}}</title>
@endsection
@section('user-dashboard')
<div class="row">
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
            <div class="wsus__my_property">
                <h4 class="heading">{{__('user.Pricing Plan')}}</h4>
                <div class="row">
                    @foreach ($packages as $item)
                    <div class="col-xl-6 col-xxl-4 col-md-6 col-lg">
                        <div class="wsus__single_price">
                            <h4>{{ $item->package_name }}</h4>
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
                                <li>{{ $item->number_of_property }} Anúncios</li>
                                @endif


                            </ul>
                            @if ($item->package_type == 0)
                            <a href="javascript:;" onclick="freeEnroll('{{ $item->id }}')" class="common_btn">{{__('user.Start With')}} {{ $item->package_name }}</a>
                            @else

                            <a href="{{ route('user.purchase.package',$item->id) }}" class="common_btn">{{__('user.Start With')}} {{ $item->package_name }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function freeEnroll(id) {
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