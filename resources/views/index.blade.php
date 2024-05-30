@extends('layout')
@section('title')
    <title>{{ $seo_text->seo_title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $seo_text->seo_description }}">
@endsection

@section('user-content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.5/autoNumeric.min.js" integrity="sha512-EGJ6YGRXzV3b1ouNsqiw4bI8wxwd+/ZBN+cjxbm6q1vh3i3H19AJtHVaICXry109EVn4pLBGAwaVJLQhcazS2w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
  function formatarNumero(input) {
      // Remover caracteres não numéricos e ponto de milhar
      let valor = input.value.replace(/[^\d,]/g, '');
      
      // Adicionar ponto a cada três dígitos na parte inteira
      let partes = valor.split(',');
      let parteInteira = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      let parteDecimal = partes[1] || '';
      
      // Limitar a parte decimal a duas casas
      parteDecimal = parteDecimal.substring(0, 2);
      
      // Formatar o número com virgula nas duas últimas casas decimais
      valor = parteInteira + (parteDecimal ? ',' + parteDecimal : '');
      
      // Definir o valor formatado no input
      input.value = 'R$ ' + valor;
  }
</script>
<style>
#bgVideo {
    right: 0;
    bottom: 0;
    width: auto;
    height: auto;
    background-size: cover;
}  

  /* Estilizando os botões de seleção */
  .btn-group .btn-primary {
    color: #0a547a;
  }

  .btn-group .btn-primary:not(.active) {
    background-color: #e9ecef !important;
  }

  .btn-group .btn-primary:not(:disabled):not(.disabled):active {
    background-color: #0056b3 !important;
  }

  .btn-group .btn-primary:not(:disabled):not(.disabled):active:focus {
    box-shadow: none !important;
  }

    /* Estilizando os botões personalizados */
    .custom-btn {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }

  .btn-primary-filter {
    background-color: #0056b3;
    border-color: #0056b3;
    color: #fff
  }

  .btn-primary-filter:hover {
    background-color: #004fa3;
    border-color: #0056b3;
    color: #dddddd
  }


  .section-apb{
    background-color: #F5F7FB;
    width: 100%;
  }
  div.wsus__top_properties_text > ul > li > a {
    font-size: 12px !important;
  }
  .apb-home > h2 {
  font-size: 4em !important;
}
  /* Estilos para dispositivos móveis */
  @media (max-width: 767px) {

    .apb-home > h2 {
  font-size: 28px !important;
}
    /* Alinhar os elementos do formulário verticalmente */
    .wsus__for_search .d-inline-flex {
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }
    
    /* Ajustar o tamanho dos botões de rádio */
    .wsus__for_search .btn-group label.btn {
      display: block;
      margin-bottom: 10px;
    }
    
    /* Ajustar o tamanho das selects e inputs */
    .wsus__for_search select,
    .wsus__for_search input[type="number"] {
      width: 100%;
    }
    .navbar-toggler{
      background-color: #000 !important;
    }
  }

  .small-font input[type="radio"] {
        font-size: 9px; 
    }




</style>



<!--=====BANNER START=====-->
<section class="wsus__banner">
  <div class="row" id="bgVideo">
    <video class="slider-video video-imob" preload="auto" playsinline="" webkit-playsinline="" autoplay="" muted="" loop="" data-origwidth="0" data-origheight="0" style="width: 1663px;">
      <source type="video/mp4" src={{ asset('uploads/website-images/banner.mp4') }} >
      <source type="video/webm" src="{{ asset('uploads/website-images/banner.webm') }}"> 
      <source type="video/webm" src="{{ asset('uploads/website-images/banner.mov') }}"> 
  </video>

  </div>
  <div class="container wsus__for_search justify-content-center align-items-center d-sm-block d-md-flex">
    <form method="GET" action="{{ route('search-property') }}" class="bg-white p-3">
      @csrf
      <div class="d-inline-flex align-items-center">
        <!-- Mostrar select apenas em dispositivos móveis -->
        <div class="d-md-none">
          <div class="form-group input-group-md border-0">
            <select class="form-control border-0" name="purpose_type">
              <option value="{{ __('user.Sell') }}" selected>Comprar</option>
              <option value="{{ __('user.Rent') }}" >Alugar</option>
            </select>
          </div>
        </div>
        
        <!-- Mostrar botões de rádio apenas em desktop -->
        <div class="d-none d-md-block mr-5">
           <div class="btn-group mr-2" data-toggle="buttons">
            <label class="btn btn-primary custom-btn" id="sellBtn" style="font-size: 12px; display: flex; align-items: center;">
              <input type="radio" name="purpose_type" value="{{ __('user.Sell') }}" checked > Comprar
          </label>
        <label class="btn btn-primary custom-btn" id="rentBtn" style="font-size: 12px; display: flex; align-items: center;">
            <input type="radio" name="purpose_type" value="{{ __('user.Rent') }}"  > Alugar
        </label>

    </div>
        </div>
        <!-- Fim dos botões de rádio -->
        
  
        <div class="d-inline-block ml-5">
          <div class="form-group input-group-md  border-0 ">
            <select  class="form-control form-control-no-border border-0" name="property_type">
              <option value="">Imóvel</option>
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
              <option value="1">1 Dormitório</option>
              <option value="2">2 Dormitórios</option>
              <option value="3">3 Dormitórios</option>
            </select>
          </div>
        </div>
    
        <div class="d-inline-block">
          <div class="form-group input-group-md  border-0 ">
             <select class="form-control form-control-no-border border-0" id="cidade" name="city_id">
              <!-- Adicione as opções de cidades conforme necessário -->
              <option value="">Cidade</option>
              @foreach ($cities as $city_item)
              <option value="{{ $city_item->id }}">{{ $city_item->translated_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="d-inline-block">
          <div class="form-group input-group-md border-0">
              <label for="min_price">Preço Mínimo:</label>
              <input type="text" id="min_price" name="min_price" class="form-control form-control-no-border border-0" placeholder="R$ 1.000,00" oninput="formatarNumero(this)">
          </div>
      </div>
      
      <div class="d-inline-block">
          <div class="form-group input-group-md border-0">
              <label for="max_price">Preço Máximo:</label>
              <input type="text" id="max_price" name="max_price" class="form-control form-control-no-border border-0" placeholder="R$ 1.000.000,00" oninput="formatarNumero(this)">
          </div>
      </div>
    
        <div class="d-inline-block">
          <div class="form-group">
            <button type="submit" class="btn btn-primary-filter btn-md">Buscar</button>
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
                    <h5 class="card-title mt-2">1 Dormitório/Studio</h5>
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
                    <h5 class="card-title text-center mt-2">3 Dormitórios +</h5>
                    <input type="hidden" name="number_of_rooms">
                </form>
            </div>
        </div>
    </div>
</div>
</div>


<div class="section-apb">
  <div class="wsus__section_heading text-center apb-home p-5">
    <h2 >APARTAMENTOS BARATOS</h2>
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
                <ul class="d-flex flex-wrap mt-3">
                    <li><i class="fal fa-bed"></i> {{ $featured_item->number_of_bedroom }} {{__('user.Bed')}}</li>

                    <li>R$ {{ number_format($featured_item->price, 2, ',', '.') }}</li>

                    @if($featured_item->number_of_parking != 0)
                    <li class="m-2">{{ $featured_item->number_of_parking }} Vaga(s) de garagem </li>
                @endif
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
                        <ul class="flex-wrap mt-3">
                            <li><a href="{{ route('property.details', $featured_item->slug) }}"><i class="fal fa-bed"></i> {{ $featured_item->number_of_bedroom }} {{ __('user.Bed') }}</a></li>

                            <li><a href="{{ route('property.details', $featured_item->slug) }}">R$ {{ number_format($featured_item->price, 2, ',', '.') }}</a></li>
                            
                            @if($featured_item->number_of_parking != 0)
                                <li><a href="{{ route('property.details', $featured_item->slug) }}">Vagas de garagem: {{ $featured_item->number_of_parking }}</a></li>
                            @endif
                        </ul>
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

    .apb-home h2{
      color: #96969641 !important;
    }
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

  .wsus__popular_text h4{
    min-height: 80px !important;
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
