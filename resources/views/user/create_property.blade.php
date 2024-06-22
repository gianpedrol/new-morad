@extends('user.layout')
@section('title')
    <title>{{__('user.Create Property')}}</title>
@endsection
@section('user-dashboard')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
      $('input[name="phone"]').mask('(00)00000-0000');
    });

    $(document).ready(function(){
      
  $('form').on('submit', function(){
    var phone = $('input[name="phone"]').val();
    phone = phone.replace(/\D/g, ''); 
    $('input[name="phone"]').val(phone);
  });
});
</script>
<script>
  $(document).ready(function(){
      // Aplica máscara ao campo CEP
      $('input[name="cep"]').mask('00000-000');
  
      // Evento para quando o CEP é digitado
      $('input[name="cep"]').on('blur', function(){
          let cep = $(this).val().replace(/\D/g, '');
          if (cep.length === 8) {
              $.ajax({
                  url: `https://viacep.com.br/ws/${cep}/json/`,
                  dataType: 'json',
                  success: function(data){
                      if (!data.erro) {
                          $('input[name="address"]').val(data.logradouro);
                          $('input[name="neighborhood"]').val(data.bairro);
                          // Outros campos, se necessário
                      } else {
                          alert('CEP não encontrado.');
                      }
                  },
                  error: function(){
                      alert('Erro ao buscar CEP.');
                  }
              });
          }
      });
  });
  </script>

<script>
    var selectedImages = [];

    function addImages(input) {
        var newFiles = input.files;

        if (selectedImages.length + newFiles.length > 20) {
            alert("Você pode selecionar no máximo 20 imagens.");
            return;
        }

        // Adiciona as novas imagens ao array selectedImages
        for (var j = 0; j < newFiles.length; j++) {
            selectedImages.push(newFiles[j]);
        }

        // Cria um novo objeto de arquivo
        var newFileList = new DataTransfer();
        
        // Adiciona todas as imagens selecionadas ao novo objeto de arquivo
        for (var k = 0; k < selectedImages.length; k++) {
            newFileList.items.add(selectedImages[k]);
        }

        // Define o novo objeto de arquivo como o valor do input de arquivo
        document.getElementById('imageInput').files = newFileList.files;
    }
</script>
<div class="row">
    <form action="{{ route('user.property.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
          <div class="wsus__add_property">
            <h4 class="heading">{{__('user.Create Property')}}  <button type="submit" class="common_btn">{{__('user.Save')}}</button> </h4>
            <div class="wsus__dash_info p_25 pb_0">
              <div class="row">
                <h5 class="sub_heading">{{__('user.Basic Information')}}</h5>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label>Titulo do Anuncio <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}">
                    <input type="hidden" name="expired_date" value="{{ $expired_date }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">Código do Imóvel</label>
                    <input type="text" name="code_imob" value="{{old('code_property_api') ?? '' }} ">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#" for="slug">{{__('user.Slug')}} <span class="text-danger">*</span></label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Property Types')}} <span class="text-danger">*</span></label>
                    <select class="select_2" name="property_type" id="property_type">
                        <option value="">{{__('user.Select Property Type')}}</option>
                            @foreach ($propertyTypes as $item)
                            <option {{ old('property_type')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->type }}</option>
                            @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.City')}} <span class="text-danger">*</span></label>
                    <select class="select_2" name="city">
                        <option value="">{{__('user.Select City')}}</option>
                        @foreach ($cities as $item)
                        <option {{ old('city')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">CEP<span class="text-danger">*</span></label>
                    <input type="text" name="cep" value="{{ old('cep') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">Digite o Endereço<span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">Numero</label>
                    <input type="text" name="number" value="{{ old('number') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">complemento</label>
                    <input type="text" name="complemento" value="{{ old('complemento') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">Bairro</label>
                    <input type="text" name="neighborhood" value="{{ old('neighborhood') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Phone')}}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Email')}} <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}">
                  </div>
                </div>
                        <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">Negócio: <span class="text-danger">*</span></label>
                    <select class="select_2" name="purpose" id="purpose">
                        @foreach ($purposes as $item)
                        <option value="{{ $item->id }}">{{ $item->custom_purpose }}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="wsus__property_input">
                        <label for="#">{{__('user.Price')}} <span class="text-danger">*</span></label>
                        <input type="text" name="price"  id="price" value="{{ old('price') }}">
                    </div>
                  </div>


                  <div class="col-xl-6 col-md-6">
                    <div class="wsus__property_input">
                        <label for="iptu">Valor IPTU (mensal / anual)<span class="text-danger">*</span></label>
                        <input type="text" id="iptu" name="iptu" class="form-control" value="{{ old('value_iptu') ?? '' }}">
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="wsus__property_input">
                        <label for="value_condominio">Valor Condominio<span class="text-danger">*</span></label>
                        <input type="text" name="value_condominio" id="value_condominio" class="form-control" value="{{ old('value_condominio') ?? '' }}">
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 d-none" id="period_box">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Period')}} <span class="text-danger">*</span></label>
                    <select class="select_2" name="period" id="period">
                        <option value="Daily">{{__('user.Daily')}}</option>
                        <option value="Monthly">{{__('user.Monthly')}}</option>
                        <option value="Yearly">{{__('user.Yearly')}}</option>
                    </select>
                  </div>
                </div>

              </div>
            </div>
            <div class="wsus__dash_info p_25 mt_25 pb_0">
              <div class="row">
                <h5 class="sub_heading">{{__('user.Others Information')}}</h5>
                <p>Adicione um valor em todo os campos, certifique de não deixar nenhum campo em branco</p>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Area')}}({{__('user.Sqft')}}) m2 <span class="text-danger">*</span></label>
                    <input type="text" name="area" value="{{ old('area') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Room')}} <span class="text-danger">*</span></label>
                    <input type="text" name="room" value="{{ old('room') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Bedroom')}} <span class="text-danger">*</span></label>
                    <input type="text" name="bedroom" value="{{ old('bedroom') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Bathroom')}}<span class="text-danger">*</span></label>
                    <input type="text" name="bathroom" value="{{ old('bathroom') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Parking Place')}} <span class="text-danger">*</span></label>
                    <input type="text" name="parking" value="{{ old('parking') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="wsus__dash_info p_25 mt_25 pb_0">
              <div class="row">
                <h5 class="sub_heading">Imagem Miniatura e Imagens do Imóvel</h5>
                <div class="col-xl-6 col-md-6">
                    <div class="wsus__property_input">
                      Essa é a imagem que ficará em destaques nas listas de imóveis, escolha a melhor imagem.
                        <label for="#">{{__('user.Thumbnail Image')}} <span class="text-danger">*</span></label>
                        <input type="file" name="thumbnail_image" id="thumbnailImageInput" onchange="previewThumbnail()">
                        <div id="thumbnailPreview" class="mt-3"></div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-8">
                    <div id="dynamic-img-box">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="wsus__property_input">
                                    <label for="#">Imagens do imóvel<span class="text-danger">*</span></label>
                                    <input id="imageInput" type="file" name="slider_images[]" multiple="multiple" onchange="previewImages()">
                                    <div id="imagesPreview" class="mt-3 row"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>

            @if ($package->number_of_aminities==-1)
                <div class="wsus__dash_info dash_aminities p_25 mt_25 pb_0">
                    <h5 class="sub_heading">Items de condomínio</h5>
                    <div class="row">
                        @foreach ($aminities as $aminity)
                            @if (old('aminities'))
                                @php
                                    $isChecked=false;
                                @endphp
                                @foreach (old('aminities') as $old_aminity)
                                    @if ($aminity->id==$old_aminity)
                                        @php
                                            $isChecked=true;
                                        @endphp
                                    @endif
                                @endforeach

                                <div class="col-xl-4 col-sm-6 col-lg-4">
                                    <div class="form-check">
                                    <input class="form-check-input" {{ $isChecked ? 'checked' : '' }} type="checkbox" name="aminities[]" id="un-aminityId-{{ $aminity->id }}" value="{{ $aminity->id }}">
                                    <label class="form-check-label" for="un-aminityId-{{ $aminity->id }}">
                                        {{ $aminity->aminity }}
                                    </label>
                                    </div>
                                </div>

                            @else
                                <div class="col-xl-4 col-sm-6 col-lg-4">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="aminities[]" id="un-aminityId-{{ $aminity->id }}" value="{{ $aminity->id }}">
                                    <label class="form-check-label" for="un-aminityId-{{ $aminity->id }}">
                                        {{ $aminity->aminity }}
                                    </label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                </div>

                @php
                    $aminityList=[];
                    foreach ($aminities as $index => $aminity) {
                        $aminityList[]=$aminity->id;
                    }
                @endphp
            @else
              <div class="wsus__dash_info dash_aminities p_25 mt_25 pb_0">
                <h5 class="sub_heading">{{__('user.Aminities')}}</h5>
                <div class="row">
                  @foreach ($aminities as $aminity)
                      @if (old('aminities'))
                          @php
                              $isChecked=false;
                          @endphp
                          @foreach (old('aminities') as $old_aminity)
                              @if ($aminity->id==$old_aminity)
                                  @php
                                      $isChecked=true;
                                  @endphp
                              @endif
                          @endforeach

                          <div class="col-xl-4 col-sm-6 col-lg-4">
                              <div class="form-check">
                              <input class="form-check-input is-check" {{ $isChecked ? 'checked' : '' }} type="checkbox" name="aminities[]" id="aminityId-{{ $aminity->id }}" value="{{ $aminity->id }}">
                              <label class="form-check-label" for="aminityId-{{ $aminity->id }}">
                                  {{ $aminity->aminity }}
                              </label>
                              </div>
                          </div>
                      @else
                          <div class="col-xl-4 col-sm-6 col-lg-4">
                              <div class="form-check">
                              <input class="form-check-input is-check" type="checkbox" name="aminities[]" id="aminityId-{{ $aminity->id }}" value="{{ $aminity->id }}">
                              <label class="form-check-label" for="aminityId-{{ $aminity->id }}">
                                  {{ $aminity->aminity }}
                              </label>
                              </div>
                          </div>
                      @endif
                  @endforeach
              </div>
              @php
                    $aminityList=[];
                    foreach ($aminities as $index => $aminity) {
                        $aminityList[]=$aminity->id;
                    }
                @endphp
            @endif
          </div>
          <div class="wsus__dash_info nearest_location p_25 mt_25">
            <h5 class="sub_heading">{{__('user.Nearest Locations')}}</h5>
            <div id="dyamic-nearest-place-box">
                <div class="row">
                    <div class="col-xl-5 col-md-5">
                        <label>{{__('user.Nearest Locations')}}</label>
                        <select class="custom-select-box" name="nearest_locations[]">
                        <option value="">{{__('user.Select')}}</option>
                            @foreach ($nearest_locatoins as $item)
                            <option value="{{ $item->id }}">{{ $item->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-5 col-md-5">
                        <label for="#">{{__('user.Distance')}}({{__('user.KM')}})</label>
                        <input type="text" name="distances[]">
                    </div>
                    <div class="col-xl-2 col-md-2">
                        <button class="common_btn mt_30" id="addDybanamicLocationBtn">{{__('user.New')}}</button>
                    </div>
                </div>
            </div>
          </div>

            <div id="hidden-location-box" class="d-none">
                <div class="delete-dynamic-location">
                    <div class="row mt-3">
                        <div class="col-xl-5 col-md-5">
                            <label>{{__('user.Nearest Locations')}}</label>
                            <select class="custom-select-box" name="nearest_locations[]">
                            <option value="">{{__('user.Select')}}</option>
                                @foreach ($nearest_locatoins as $item)
                                <option value="{{ $item->id }}">{{ $item->location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-5 col-md-5">
                            <label for="#">{{__('user.Distance')}}({{__('user.KM')}})</label>
                            <input type="text" name="distances[]">
                        </div>
                        <div class="col-xl-2 col-md-2">
                            <button class="common_btn mt_30 removeNearstPlaceBtnId" id="addDybanamicLocationBtn">{{__('user.Remove')}}</button>
                        </div>
                    </div>
                </div>
            </div>



          <div class="wsus__dash_info pro_det_map p_25 mt_25 pb_0">
            <h5 class="sub_heading">{{__('user.Property Details And Google Map')}} </h5>
            <div class="wsus__property_input">
              Copie e o código iframe do google maps. 
              Como fazer: Acesse o endereço clique em compartilhar e depois em incorporar código, copie-o e cole aqui.
              <label>{{__('user.Google Map Code')}}</label>
              <textarea cols="3" rows="3"  name="google_map_embed_code">{{ old('google_map_embed_code') }}</textarea>
            </div>
            <div class="wsus__property_input">
              <label>{{__('user.Description')}} <span class="text-danger">*</span>
              <textarea class="form-control summer_note" id="summernote" name="description">{{ old('description') }}</textarea>
            </div>

            <div class="col-12 my-2 ">
              <button type="submit" class="common_btn">{{__('user.Save')}}</button>
            </div>
          </div>
          </div>




      </div>
    </div>
</div>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/cleave.js@latest/dist/cleave.min.js"></script>
<script>
  var cleavePrice = new Cleave('#price', {
      numeral: true,
      numeralThousandsGroupStyle: 'thousand',
      delimiter: '.',
      numeralDecimalMark: ',',
      numeralDecimalScale: 2,
  });

  var cleaveIptu = new Cleave('#iptu', {
      numeral: true,
      numeralThousandsGroupStyle: 'thousand',
      delimiter: '.',
      numeralDecimalMark: ',',
      numeralDecimalScale: 2,
  });

  var cleaveCondominio = new Cleave('#value_condominio', {
      numeral: true,
      numeralThousandsGroupStyle: 'thousand',
      delimiter: '.',
      numeralDecimalMark: ',',
      numeralDecimalScale: 2,
  });
</script>
<script>
    $(document).ready(function () {

        var image=1;
        var maxImage='{{ $package->number_of_photo }}';
        var location=1;
        var maxLocation='{{ $package->number_of_nearest_place }}';
        $("#addDynamicImgBtn").on('click',function(e) {
            e.preventDefault();

            var dynaicImage = $("#dynamic_img_box").html();
            if(maxImage==-1){
                $("#dynamic-img-box").append(dynaicImage);
            }else if(image < maxImage){
                image++;
                $("#dynamic-img-box").append(dynaicImage);
            }


        })

        $(document).on('click', '.removeDynamicImgId', function () {
            $(this).closest('.delete-dynamic-img-row').remove();
            image--;
        });

        $("#addDybanamicLocationBtn").on('click',function(e) {
            e.preventDefault();
            var newRow=$("#hidden-location-box").html()

            if(maxLocation == -1){
                $("#dyamic-nearest-place-box").append(newRow);
            }else if(location < maxLocation){
                location++;
                $("#dyamic-nearest-place-box").append(newRow);
            }

        })

        $(document).on('click', '.removeNearstPlaceBtnId', function () {
                $(this).closest('.delete-dynamic-location').remove();
                location--;
        });

        $("#title").on("focusout",function(e){
            $("#slug").val(convertToSlug($(this).val()));
        })

        $("#purpose").on("change",function(){
            var purposeId=$(this).val()
            if(purposeId==2){
                $("#period_box").removeClass('d-none');
            }else if(purposeId==1){
                $("#period_box").addClass('d-none');
            }
        })


        //start handle aminity
        $(".is-check").on("click",function(e){
            var ids = [];
            var aminityList=<?= json_encode($aminityList)?>;
            var maxAminity= <?= $package->number_of_aminities ?>;
            $('input[name="aminities[]"]:checked').each(function() {
                ids.push(this.value);
            });
            var idsLenth=ids.length;

            const checkedIds = ids.map((i) => Number(i));

            var unCheckedIds=aminityList.filter(d => !checkedIds.includes(d))


            if( maxAminity > idsLenth){
                for(var j=0; j< unCheckedIds.length ; j++){
                    $("#aminityId-"+unCheckedIds[j]).prop("disabled", false);
                }

            }else{
                for(var j=0; j< unCheckedIds.length ; j++){
                    $("#aminityId-"+unCheckedIds[j]).prop("disabled", true);
                }

            }

        })
        //end handle aminity



    });


   function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }
    // $(document).ready(function () {
    //     $('form').on('submit', function (e) {
    //         e.preventDefault(); // Evita o envio do formulário padrão

    //         // Obtém os valores dos campos
    //         var title = $('#title').val();
    //         var city = $('select[name="city"]').val();
    //         var address = $('input[name="address"]').val();
    //         var price = $('input[name="price"]').val();
    //         var images = $('input[name="slider_images[]"]').get(0).files.length;

    //         // Verifica se algum campo obrigatório está vazio
    //         if (title.trim() === '' || city === '' || address.trim() === '' || price.trim() === '' || images === 0) {
    //             alert('Por favor, preencha todos os campos obrigatórios (Título, Cidade, Endereço, Preço, Imagens)');
    //         } else {
    //             // Se todos os campos obrigatórios estiverem preenchidos, envie o formulário
    //             this.submit();
    //         }
    //     });
    // });
</script>
@endsection
