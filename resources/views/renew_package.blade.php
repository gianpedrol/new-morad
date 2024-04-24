@extends('layout')
@section('title')
    <title>{{__('user.Renew Payment')}}</title>
@endsection
@section('meta')
    <meta name="description" content="lorem ipsum">
@endsection

@section('user-content')

  <!--===BREADCRUMB PART START====-->
  <section class="wsus__breadcrumb" style="background: url({{ url($banner_image) }});">
    <div class="wsus_bread_overlay">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>{{__('user.Renew Payment')}}</h4>
                    <nav style="--bs-breadcrumb-divider: '-';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{__('user.Renew Payment')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
  </section>
  <!--===BREADCRUMB PART END====-->


  <!--=====CHECKOUT START=====-->
  <section class="wsus__checkout mt_45 mb_45">
    <div class="container">
      <div class="row">
        <div class="col-xl-2 col-lg-3 col-md-3">
          <div class="wsus__pay_method" id="sticky_sidebar">
            <h5>{{__('user.Payment Method')}}</h5>
            <div class="d-flex align-items-start">
              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

        
                @if ($mercadopagoPaymentInfo->status==1)
                <button class="nav-link" id="mercadopago-tab" data-bs-toggle="pill" data-bs-target="#marcadopagoTab" type="button" role="tab" aria-controls="marcadopagoTab" aria-selected="false">{{__('user.Mercado Pago')}}</button>
                @endif


              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-5 col-lg-12 col-md-12">
          <div class="wsus__pay_details" id="sticky_sidebar2">
            <h5>{{__('user.Payment Details')}}</h5>
            <div class="tab-content" id="v-pills-tabContent">

                <div class="tab-pane fade" id="marcadopagoTab" role="tabpanel" aria-labelledby="mercadopago-tab">


                    <form action="{{ route('user.pay-with-mercadopago') }}" method="POST" id="mercadopago" >
                        @csrf
                        <div class="row mt-3">
                            <div class="col-12 mb-2 form-group">
                                <label for="cardNumber">{{__('user.Card Number')}}</label>
                                <input class="form-control" type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" autocomplete=off required />
                            </div>

                            <div class="col-md-6 mb-2 form-group">
                                <label for="securityCode">{{__('user.Security Code')}}</label>
                                <input class="form-control" type="text" id="securityCode" data-checkout="securityCode" onselectstart="return false" autocomplete=off required />
                            </div>

                            <div class="col-md-6 mb-2 form-group">
                                <label for="cardExpirationMonth">{{__('user.Expiration Month')}}</label>
                                <input class="form-control" type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" autocomplete=off required />
                            </div>

                            <div class="col-lg-6 mb-2 form-group">
                                <label for="cardExpirationYear">{{__('user.Expiration Year')}}</label>
                                <input class="form-control" type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" autocomplete=off required />
                            </div>

                            <div class="col-lg-6 mb-2 form-group">
                                <label for="cardholderName">{{__('user.Card Holder Name')}}</label>
                                <input class="form-control" type="text" id="cardholderName" data-checkout="cardholderName" required autocomplete="off"/>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="docType" id="dc-label">{{__('user.Document type')}}</label>
                                <select class="form-control" id="docType" data-checkout="docType" required>
                                </select>
                            </div>

                            <div class="col-lg-6 form-group">
                                <label for="docNumber">{{__('user.Document Number')}}</label>
                                <input class="form-control" type="text" id="docNumber" data-checkout="docNumber" required />
                            </div>
                        </div>

                        @php
                            $payableAmount = round($package->price * $mercadopagoPaymentInfo->currency_rate,2);
                        @endphp
                        <input type="hidden" id="installments" value="1" />
                        <input type="hidden" name="amount" id="amount" value="{{ $payableAmount }}"/>
                        <input type="hidden" name="description" />
                        <input type="hidden" name="paymentMethodId" />

                        <input type="hidden" name="package_id"  value="{{ $package->id }}"/>

                        <div class="my-4">
                            <button type="submit" id="payment__button" class="common_btn">{{__('user.Submit')}}</button>
                        </div>
                    </form>

                  </div>


            </div>
          </div>
        </div>

        <div class="col-xl-5 col-lg-5">
          <div class="wsus__package_details">
            <h5>{{__('user.Package Details')}}</h5>
            <div class="table-responsive main_table">
                <table class="table">
                    <tr>
                        <td width="50%">{{__('user.Package Name')}}</td>
                        <td width="50%">{{ $package->package_name }}</td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Price')}}</td>
                        <td width="50%">{{ $currency->currency_icon }}{{ $package->price }}</td>
                    </tr>

                     <tr>
                        <td width="50%">{{__('user.Expire')}}</td>
                        <td width="50%">
                            @if ($package->number_of_days==-1)
                            {{__('user.Unlimited')}}
                            @else
                            {{ $package->number_of_days }}{{__('user.Days')}}

                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Property')}}</td>
                        <td width="50%">
                            @if ($package->number_of_property==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_property }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Aminity')}}</td>
                        <td width="50%">
                            @if ($package->number_of_aminities==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_aminities }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Nearest Place')}}</td>
                        <td width="50%">
                            @if ($package->number_of_nearest_place==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_nearest_place }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Photo')}}</td>
                        <td width="50%">
                            @if ($package->number_of_photo==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_photo }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">{{__('user.Featured Property')}}</td>
                        <td width="50%">
                            @if ($package->is_featured==1)
                            {{__('user.Available')}}
                            @else
                            {{__('user.Not Available')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Featured Property')}}</td>
                        <td width="50%">
                            @if ($package->number_of_feature_property==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_feature_property }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">{{__('user.Top Property')}}</td>
                        <td width="50%">
                            @if ($package->is_top==1)
                            {{__('user.Available')}}
                            @else
                            {{__('user.Not Available')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Top Property')}}</td>
                        <td width="50%">
                            @if ($package->number_of_top_property==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_top_property }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Urgent Property')}}</td>
                        <td width="50%">
                            @if ($package->is_urgent==1)
                            {{__('user.Available')}}
                            @else
                            {{__('user.Not Available')}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">{{__('user.Urgent Property')}}</td>
                        <td width="50%">
                            @if ($package->number_of_urgent_property==-1)
                            {{__('user.Unlimited')}}
                            @else
                                {{ $package->number_of_urgent_property }}
                            @endif
                        </td>
                    </tr>

                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--=====CHECKOUT END=====-->




<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

<script>
    'use strict';

    window.Mercadopago.setPublishableKey("{{ $mercadopagoPaymentInfo->public_key }}");
    window.Mercadopago.getIdentificationTypes();

    function addEvent(to, type, fn){
        if(document.addEventListener){
            to.addEventListener(type, fn, false);
        } else if(document.attachEvent){
            to.attachEvent('on'+type, fn);
        } else {
            to['on'+type] = fn;
        }
    };

    addEvent(document.querySelector('#cardNumber'), 'keyup', guessingPaymentMethod);
    addEvent(document.querySelector('#cardNumber'), 'change', guessingPaymentMethod);

    function getBin() {
    var ccNumber = document.querySelector('input[data-checkout="cardNumber"]');
    return ccNumber.value.replace(/[ .-]/g, '').slice(0, 6);
    };

    function guessingPaymentMethod(event) {
    var bin = getBin();

    if (event.type == "keyup") {
        if (bin.length >= 6) {
            window.Mercadopago.getPaymentMethod({
                "bin": bin
            }, setPaymentMethodInfo);
        }
    } else {
        setTimeout(function() {
            if (bin.length >= 6) {
                window.Mercadopago.getPaymentMethod({
                    "bin": bin
                }, setPaymentMethodInfo);
            }
        }, 100);
    }
    };

    function setPaymentMethodInfo(status, response) {
    if (status == 200) {
        const paymentMethodElement = document.querySelector('input[name=paymentMethodId]');

        if (paymentMethodElement) {
            paymentMethodElement.value = response[0].id;
        } else {
            const input = document.createElement('input');
            input.setAttribute('name', 'paymentMethodId');
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', response[0].id);

            form.appendChild(input);
        }

        Mercadopago.getInstallments({
            "bin": getBin(),
            "amount": parseFloat(document.querySelector('#amount').value),
        }, setInstallmentInfo);

    } else {
        alert(`payment method info error: ${response}`);
    }
    };



    addEvent(document.querySelector('#mercadopago'), 'submit', doPay);
    function doPay(event){
    event.preventDefault();

        var $form = document.querySelector('#mercadopago');

        window.Mercadopago.createToken($form, sdkResponseHandler); // The function "sdkResponseHandler" is defined below

        return false;

    };

    function sdkResponseHandler(status, response) {
    if (status != 200 && status != 201) {
        alert("Some of your information is wrong!");
        $('#preloader').hide();

    }else{
        var form = document.querySelector('#mercadopago');
        var card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        form.appendChild(card);

        form.submit();
    }
    };


    function setInstallmentInfo(status, response) {
        var selectorInstallments = document.querySelector("#installments"),
        fragment = document.createDocumentFragment();
        selectorInstallments.length = 0;

        if (response.length > 0) {
            var option = new Option("Escolha...", '-1'),
            payerCosts = response[0].payer_costs;
            fragment.appendChild(option);

            for (var i = 0; i < payerCosts.length; i++) {
                fragment.appendChild(new Option(payerCosts[i].recommended_message, payerCosts[i].installments));
            }

            selectorInstallments.appendChild(fragment);
            selectorInstallments.removeAttribute('disabled');
        }
    };


</script>

@endsection
