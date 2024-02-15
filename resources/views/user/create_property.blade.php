@extends('user.layout')
@section('title')
    <title>{{__('user.Create Property')}}</title>
@endsection
@section('user-dashboard')
<div class="row">
    <form action="{{ route('user.property.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="col-xl-9 ms-auto">
        <div class="wsus__dashboard_main_content">
          <div class="wsus__add_property">
            <h4 class="heading">{{__('user.Create Property')}} <a href="{{ route('user.my.properties') }}" class="common_btn"><i class="fal fa-plus-octagon"></i> {{__('user.All Properties')}}</a></h4>
            <div class="wsus__dash_info p_25 pb_0">
              <div class="row">
                <h5 class="sub_heading">{{__('user.Basic Information')}}</h5>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label>{{__('user.Title')}} <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}">
                    <input type="hidden" name="expired_date" value="{{ $expired_date }}">
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
                    <label for="#">{{__('user.Address')}} <span class="text-danger">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}">
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
                    <label for="#">{{__('user.Website')}}</label>
                    <input type="url" name="website" value="{{ old('website') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Purpose')}} <span class="text-danger">*</span></label>
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
                        <input type="text" name="price" value="{{ old('price') }}">
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
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Area')}}({{__('user.Sqft')}}) <span class="text-danger">*</span></label>
                    <input type="text" name="area" value="{{ old('area') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Unit')}} <span class="text-danger">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit') }}">
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
                    <label for="#">{{__('user.Total Floor')}} <span class="text-danger">*</span></label>
                    <input type="text" name="floor" value="{{ old('floor') }}">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Total Kitchen')}} <span class="text-danger">*</span></label>
                    <input type="text" name="kitchen" value="{{ old('kitchen') }}">
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
                <h5 class="sub_heading">{{__('user.Image, PDF And Video')}}</h5>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.PDF File')}}</label>
                    <input type="file" name="pdf_file">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Bannner Image')}} <span class="text-danger">*</span></label>
                    <input type="file" name="banner_image">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Thumbnail Image')}} <span class="text-danger">*</span></label>
                    <input type="file" name="thumbnail_image">
                  </div>
                </div>
                <div class="col-xl-6 col-md-6">
                  <div class="wsus__property_input">
                    <label for="#">{{__('user.Youtube Video Id')}}</label>
                    <input type="text" name="video_link">
                  </div>
                </div>
                <div class="col-xl-8 col-md-8 ">
                    <div id="dynamic-img-box">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="wsus__property_input">
                                    <label for="#">{{__('user.Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" name="slider_images[]">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="medicine_row_input">
                                    <button class="mt_30" type="button" id="addDynamicImgBtn"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="dynamic_img_box" class="d-none">
                    <div class="row delete-dynamic-img-row">
                        <div class="col-md-9">
                            <div class="wsus__property_input">
                                <label for="#">{{__('user.Image')}} <span class="text-danger">*</span></label>
                                <input type="file" name="slider_images[]">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="medicine_row_input">
                                <button class="mt_30 danger_btn removeDynamicImgId" type="button"><i class="fas fa-trash" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

              </div>
            </div>

            @if ($package->number_of_aminities==-1)
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
              <label>{{__('user.Google Map Code')}}</label>
              <textarea cols="3" rows="3"  name="google_map_embed_code">{{ old('google_map_embed_code') }}</textarea>
            </div>
            <div class="wsus__property_inpuT">
              <label>{{__('user.Description')}} <span class="text-danger">*</span>
              <textarea class="form-control summer_note" id="summernote" name="description">{{ old('description') }}</textarea>
            </div>
          </div>
          <div class="wsus__dash_info featured p_25 mt_25">
            <div class="row">
                @if ($package->is_featured)
                    @if ($package->number_of_feature_property==-1)
                    <div class="col-12">
                        <div class="wsus__property_input">
                            <label for="#">{{__('user.Featured')}} <span class="text-danger">*</span></label>
                            <select class="select_2" name="featured">
                                <option value="1">{{__('user.Yes')}}</option>
                                <option value="0">{{__('user.No')}}</option>
                            </select>
                        </div>
                      </div>
                    @elseif($package->number_of_feature_property > $existFeaturedProperty)
                        <div class="col-12">
                            <div class="wsus__property_input">
                                <label for="#">{{__('user.Featured')}} <span class="text-danger">*</span></label>
                                <select class="select_2" name="featured">
                                    <option value="1">{{__('user.Yes')}}</option>
                                    <option value="0">{{__('user.No')}}</option>
                                </select>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($package->is_top)
                    @if ($package->number_of_top_property==-1)
                    <div class="col-12">
                        <div class="wsus__property_input">
                        <label for="#">{{__('user.Top Property')}} <span class="text-danger">*</span></label>
                        <select class="select_2" name="top_property">
                            <option value="1">{{__('user.Yes')}}</option>
                            <option value="0">{{__('user.No')}}</option>
                        </select>
                        </div>
                    </div>
                    @elseif($package->number_of_top_property > $existTopProperty)
                    <div class="col-12">
                        <div class="wsus__property_input">
                        <label for="#">{{__('user.Top Property')}} <span class="text-danger">*</span></label>
                        <select class="select_2" name="top_property">
                            <option value="1">{{__('user.Yes')}}</option>
                            <option value="0">{{__('user.No')}}</option>
                        </select>
                        </div>
                    </div>
                    @endif
              @endif

              @if ($package->is_urgent)
                @if ($package->number_of_urgent_property==-1)
                    <div class="col-xl-6 col-md-6">
                        <div class="wsus__property_input">
                            <label for="#">{{__('user.Urgent Property')}} <span class="text-danger">*</span></label>
                            <select class="select_2" name="urgent_property">
                                <option value="1">{{__('user.Yes')}}</option>
                                <option value="0">{{__('user.No')}}</option>
                            </select>
                        </div>
                    </div>
                @elseif($package->number_of_urgent_property > $existUrgentProperty)
                    <div class="col-xl-6 col-md-6">
                        <div class="wsus__property_input">
                            <label for="#">{{__('user.Urgent Property')}} <span class="text-danger">*</span></label>
                            <select class="select_2" name="urgent_property">
                                <option value="1">{{__('user.Yes')}}</option>
                                <option value="0">{{__('user.No')}}</option>
                            </select>
                        </div>
                    </div>
                @endif
              @endif


              <div class="col-12">
                <div class="wsus__property_input">
                    <label for="#">{{__('user.SEO Title')}}</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title') }}">
                </div>
              </div>
              <div class="col-xl-12">
                <div class="wsus__property_input">
                    <label for="#">{{__('user.SEO Description')}}</label>
                    <textarea cols="3" rows="3" name="seo_description">{{ old('seo_description') }}</textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="common_btn">{{__('user.Save')}}</button>
              </div>
            </div>

          </div>
        </div>
    </div>
</div>
</form>
</div>

<script>
    (function($) {
    "use strict";
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

    })(jQuery);


    function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/[^\w ]+/g,'')
            .replace(/ +/g,'-');
    }
</script>
@endsection
