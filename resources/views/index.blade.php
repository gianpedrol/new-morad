@extends('layout')
@section('title')
    <title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_text->seo_description }}">
@endsection

@section('user-content')
<style>
#bgVideo {
    right: 0;
    bottom: 0;
    width: auto;
    height: auto;
    background-size: cover;
}  
</style>

<!--=====BANNER START=====-->
<section class="wsus__banner">
  <div class="row" id="bgVideo">

    <video autoplay muted loop >
      <source src={{ asset('uploads/website-images/banner.mp4') }} type="video/mp4">
  </video>

  </div>
  <div class="container wsus__for_search justify-content-center align-items-center d-sm-block d-md-flex">
    <form method="GET" action="{{ route('search-property') }}" class="bg-white p-3">
      @csrf
      <div class="d-inline-flex align-items-center">

        <div class="d-inline-block">
          <div class="form-group input-group-md  border-0 ">
            <select class="form-control form-control-no-border border-0" id="tipo"  name="purpose_type" >
              <option value="" disabled selected hidden>O que você precisa ?</option>
              <option value={{__('user.Rent')}}>{{__('user.Rent')}}</option>
              <option value={{__('user.Sell')}}>{{__('user.Sell')}}</option>
            </select>
          </div>
        </div>
  
        <div class="d-inline-block">
          <div class="form-group input-group-md  border-0 ">
            <select  class="form-control form-control-no-border border-0" name="property_type">
              <option value="">Tipo de Imóvel</option>
              @foreach ($propertyTypes as $property_type_item)
                  <option value="{{ $property_type_item->id }}">{{ $property_type_item->translated_type }}</option>
              @endforeach
          </select>
          </div>
        </div>
    
        <div class="d-inline-block">
          <div class="form-group input-group-md  border-0 ">
            <select class="form-control form-control-no-border border-0" id="dormitorios" name="number_of_rooms" placeholder="Quantos dormitórios?">
              <option value="">Dormitórios</option>
              @for ($i = 1; $i <= $max_number_of_room; $i++)
                  <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>
        </div>
    
        <div class="d-inline-block">
          <div class="form-group input-group-md  border-0 ">
             <select class="form-control form-control-no-border border-0" id="cidade" name="city_id">
              <!-- Adicione as opções de cidades conforme necessário -->
              <option value="">Selecione a cidade</option>
              @foreach ($cities as $city_item)
              <option value="{{ $city_item->id }}">{{ $city_item->translated_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
    
        <div class="d-inline-block">
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-md">Buscar Agora</button>
          </div>
        </div>
      </div>
    </form>
  </div>

</section>
<!--=====BANNER END=====-->


 
<div class="container mt-5 d-flex justify-content-center">
  <h3 class="d-block">Conheça nossos apartamentos </h3>
</div>
<div class="container mt-5 d-flex justify-content-center">
 
  <div class="row">
    <!-- Loft / 1 Dormitório -->
    <div class="col-md-4 mb-4">
        <div class="card border-0">
            <div class="card-body align-items-center mx-auto text-center">
                <form method="GET" action="{{ route('search-property') }}">
                    <div style="width: 200px; height: 200px;">
                        <img src="{{ asset('uploads/website-images/loft.jpg') }}" class="rounded-circle property-image" alt="Card Image"
                             data-dormitorios="1" data-route="{{ route('search-property') }}">
                    </div>
                    <h5 class="card-title mt-2">Loft / 1 Dormitório</h5>
                    <input type="hidden" name="number_of_rooms">
                </form>
            </div>
        </div>
    </div>

    <!-- 2 Dormitório -->
    <div class="col-md-4 mb-4">
        <div class="card custom-card border-0">
            <div class="card-body align-items-center mx-auto text-center">
                <form method="GET" action="{{ route('search-property') }}">
                    <div style="width: 200px; height: 200px;">
                        <img src="{{ asset('uploads/website-images/padrao.jpg') }}" class="rounded-circle property-image" alt="Card Image"
                             data-dormitorios="2" data-route="{{ route('search-property') }}">
                    </div>
                    <h5 class="card-title mt-2">2 Dormitórios</h5>
                    <input type="hidden" name="number_of_rooms">
                </form>
            </div>
        </div>
    </div>

    <!-- +3 Dormitório -->
    <div class="col-md-4 mb-4">
        <div class="card custom-card border-0">
            <div class="card-body align-items-center mx-auto text-center">
                <form method="GET" action="{{ route('search-property') }}">
                    <div style="width: 200px; height: 200px;">
                        <img src="{{ asset('uploads/website-images/3dm.jpg') }}" class="rounded-circle property-image" alt="Card Image"
                             data-dormitorios="3" data-route="{{ route('search-property') }}">
                    </div>
                    <h5 class="card-title text-center mt-2">+3 Dormitórios</h5>
                    <input type="hidden" name="number_of_rooms">
                </form>
            </div>
        </div>
    </div>
</div>
</div>


@if ($featured_properties->featured_visibility)
    <section class="wsus__popular_properties mt_90 xs_mt_65">
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="wsus__section_heading text-center mb_60">
            <h2>{{ $featured_properties->title }}</h2>
            <span>{{ $featured_properties->description }}</span>
            </div>
        </div>
        </div>
        <div class="row">
        @foreach ($featured_properties->featured_properties as $featured_item)
            <div class="col-xl-4 col-md-6">
            <div class="wsus__popular_properties_single">
                <img src="{{ asset($featured_item->thumbnail_image) }}" alt="popular properties">
                <a href="{{ route('property.details',$featured_item->slug) }}" class="wsus__popular_text">
                <h4>{{ $featured_item->translated_title }}</h4>
                <ul class="d-flex flex-wrap mt-3">
                    <li><i class="fal fa-bed"></i> {{ $featured_item->number_of_bedroom }} {{__('user.Bed')}}</li>
                    <li><i class="fal fa-shower"></i> {{ $featured_item->number_of_bathroom }} {{__('user.Bath')}}</li>
                    <li><i class="fal fa-draw-square"></i> {{ $featured_item->area }} {{__('user.Sqft')}}</li>
                </ul>
                </a>
            </div>
            </div>
        @endforeach
        </div>
    </div>
    </section>
@endif


@if ($urgent_properties->urgent_visibility)


        <!--=====TOP PROPERTIES START=====-->
  <section class="wsus__top_properties mt_75 xs_mt_50 pt_90 xs_pt_65 pb_75 xs_pb_50">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="wsus__section_heading text-center mb_60">
            <h2>{{ $urgent_properties->title }}</h2>
            <span>{{ $urgent_properties->description }}</span>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach ($urgent_properties->urgent_properties as $urgent_item)
            <div class="col-xl-4 col-sm-6 col-lg-4">
                <div class="wsus__top_properties_item">
                    <div class="row">
                    <div class="col-xl-6">
                        <div class="wsus__top_properties_img">
                        <img src="{{ asset($urgent_item->thumbnail_image) }}" alt="top properties" class="ifg-fluid w-100">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="wsus__top_properties_text">
                        <a href="{{ route('property.details',$urgent_item->slug) }}">{{ $urgent_item->translated_title }}</a>

                            @if ($urgent_item->property_purpose_id==1)
                                <p>{{ $currency }}{{ $urgent_item->price }}</p>
                            @elseif ($urgent_item->property_purpose_id==2)
                            <p>{{ $currency }}{{ $urgent_item->price }} /
                                @if ($urgent_item->period=='Daily')
                                <span>{{__('user.Daily')}}</span>
                                @elseif ($urgent_item->period=='Monthly')
                                <span>{{__('user.Monthly')}}</span>
                                @elseif ($urgent_item->period=='Yearly')
                                <span>{{__('user.Yearly')}}</span>
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        @endforeach
      </div>
    </div>
  </section>
  <!--=====TOP PROPERTIES END=====-->
  @endif

  <style>
    .property-image {
        cursor: pointer;
        transition: transform 0.3s;
    }

    .property-image:hover {
        transform: scale(1.1);
    }
</style>
<style>
  /* Estilos adicionais para tornar o formulário responsivo */
  @media (max-width: 767.98px) {
    /* Estilos para dispositivos móveis */
    .wsus__for_search .d-inline-block {
      display: block;
      width: 100%;
      margin-bottom: 10px;
    }
    .wsus__for_search .form-group {
      width: 85vw;
    }
    .wsus__for_search .btn {
      width: 100%;
    }
    .wsus__for_search .form-control {
      width: 100%;
    }
    .wsus__for_search .d-inline-flex {
      flex-direction: column;
    }
    .wsus__for_search .input-group-md {
      margin-bottom: 10px;
    }
  }

  @media (min-width: 768px) {
    /* Estilos para dispositivos maiores que 768px (não móveis) */
    .wsus__for_search .d-inline-block {
      display: inline-block;
    }
    .wsus__for_search .d-inline-flex {
      display: flex;
    }
    .wsus__for_search .form-group {
      margin-right: 10px;
    }
  }

</style>
<script>
  // Adiciona um ouvinte de evento de clique a todas as imagens com a classe "property-image"
  document.querySelectorAll('.property-image').forEach(function (img) {
      img.addEventListener('click', function () {
          // Obtém os valores dos atributos de dados
          var numberOfRooms = img.getAttribute('data-dormitorios');
          var route = img.getAttribute('data-route');

          // Encontra o formulário mais próximo
          var form = img.closest('form');

          if (form) {
              // Atualiza o valor do campo oculto no formulário
              var hiddenInput = form.querySelector('input[name="number_of_rooms"]');
              
              if (hiddenInput) {
                  hiddenInput.value = numberOfRooms;

                  // Submete o formulário
                  form.submit();
              } else {
                  console.error('Input "number_of_rooms" not found in the form.');
              }
          } else {
              console.error('Form not found.');
          }
      });
  });
</script>

@endsection
