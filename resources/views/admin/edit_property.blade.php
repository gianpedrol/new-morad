@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Edit Property')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Property')}}</h1>
          </div>

          <div class="section-body property_box">
                <a href="{{ route('admin.property.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Property List')}}</a>
                <div class="row mt-4">
                    <form action="{{ route('admin.property.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Basic Information')}}</h4>
                                    <hr>


                                    <div class="form-group">
                                        <label>{{__('admin.Property Agent')}}<span class="text-danger">*</span></label>
                                        <select name="agent" id="" class="form-control select2">
                                            <option {{ $property->user_id == 0 ? 'selected' : '' }} value="0">{{__('admin.Own Property')}}</option>
                                            @foreach ($agents as $agent)
                                            <option {{ $property->user_id == $agent->id ? 'selected' : '' }} value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="title">{{__('admin.Title')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" id="title" value="{{ $property->title }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" class="form-control" id="slug" value="{{ $property->slug }}">
                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category">{{__('admin.Property Type')}} <span class="text-danger">*</span></label>
                                                <select name="property_type" id="property_type" class="form-control select2">
                                                    <option value="">{{__('admin.Select')}}</option>
                                                    @foreach ($propertyTypes as $item)
                                                    <option {{ $property->property_type_id==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city">{{__('admin.City')}} <span class="text-danger">*</span></label>
                                                <select name="city" id="city" class="form-control select2">
                                                    <option value="">{{__('admin.Select')}}</option>
                                                    @foreach ($cities as $item)
                                                    <option {{ $property->city_id==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="address">{{__('admin.Address')}} <span class="text-danger">*</span></label>
                                                <input type="text" name="address" value="{{ $property->address }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">{{__('admin.Phone')}}</label>
                                                <input type="text" name="phone" value="{{ $property->phone }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="{{ $property->email }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="website">{{__('admin.Website')}}</label>
                                                <input type="url" name="website" value="{{ $property->website }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="purpose">{{__('admin.Purpose')}} <span class="text-danger">*</span></label>
                                                <select name="purpose" id="purpose" class="form-control">
                                                    @foreach ($purposes as $item)
                                                    <option {{ $property->property_purpose_id==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->custom_purpose }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="price">{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                                <input type="text" name="price" class="form-control" value="{{ $property->price }}">
                                            </div>
                                        </div>


                                        @if ($property->property_purpose_id==1)
                                            <div class="col-md-6 d-none period_box" >
                                                <div class="form-group">
                                                    <label for="period">{{__('admin.Period')}} <span class="text-danger">*</span></label>
                                                    <select name="period" id="period" class="form-control">
                                                        <option value="Daily">{{__('admin.Daily')}}</option>
                                                        <option value="Monthly">{{__('admin.Monthly')}}</option>
                                                        <option value="Yearly">{{__('admin.Yearly')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($property->property_purpose_id==2)
                                            <div class="col-md-6 period_box" >
                                                <div class="form-group">
                                                    <label for="period">{{__('admin.Period')}} <span class="text-danger">*</span></label>
                                                    <select name="period" id="period" class="form-control">
                                                        <option {{ $property->period=='Daily' ? 'selected' : '' }} value="Daily">{{__('admin.Daily')}}</option>
                                                        <option {{ $property->period=='Monthly' ? 'selected' : '' }} value="Monthly">{{__('admin.Monthly')}}</option>
                                                        <option {{ $property->period=='Yearly' ? 'selected' : '' }} value="Yearly">{{__('admin.Yearly')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Others Information')}}</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Area(Square Feet)')}} <span class="text-danger">*</span></label>
                                                <input type="text" name="area" value="{{ $property->area }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Unit')}} <span class="text-danger">*</span></label>
                                                <input type="number" name="unit" value="{{ $property->number_of_unit }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Room')}} <span class="text-danger">*</span></label>
                                                <input type="number" name="room" value="{{ $property->number_of_room }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Bedroom')}} <span class="text-danger">*</span></label>
                                                <input type="number" name="bedroom" value="{{ $property->number_of_bedroom }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Bathroom')}} <span class="text-danger">*</span></label>
                                                <input type="number" name="bathroom" value="{{ $property->number_of_bathroom }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Floor')}} <span class="text-danger">*</span></label>
                                                <input type="number" name="floor" value="{{ $property->number_of_floor }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Kitchen')}} </label>
                                                <input type="number" name="kitchen" value="{{ $property->number_of_kitchen }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Total Parking Place')}}</label>
                                                <input type="number" name="parking" value="{{ $property->number_of_parking }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Slider Images')}} </h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <table class="table table-bordered">
                                                @foreach ($property->propertyImages as $item)
                                                <tr class="slider-tr-{{ $item->id }}">
                                                    <td> <img class="w_300 mb-2" src="{{ asset($item->image) }}"  alt=""> </td>
                                                    <td><a onclick="deleteSliderImg('{{ $item->id }}')" href="javascript:;" class="btn btn-danger"><i class="fas fa-trash    "></i></a></td>
                                                </tr>
                                                @endforeach

                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Slider Images (Multiple)')}} <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control-file" name="slider_images[]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Image, PDF and Video')}}</h4>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-12">

                                            @if ($property->file)
                                                <div class="form-group pdf-file-col-{{ $property->id }}">
                                                    <label for="file">{{__('admin.Existing PDF')}} : </label>
                                                    <div>
                                                        <a href="{{ route('download-listing-file',$property->file) }}">{{ $property->file }}</a> <a onclick="deletePdfFile('{{ $property->id }}')" href="javascript:;" class="text-danger ml-3"><i class="fa fa-trash" aria-hidden="true"></i> </a>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label for="">{{__('admin.PDF File')}}</label>
                                                <input type="file" name="pdf_file" class="form-control-file">
                                            </div>
                                        </div>

                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="">{{__('admin.Existing Image')}}</label>
                                                <br>
                                                <img class="w_300" src="{{ asset($property->banner_image) }}" alt="">
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{__('admin.Bannner Image')}} <span class="text-danger">*</span></label>
                                                <input type="file" name="banner_image" class="form-control-file">
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="">{{__('admin.Existing Image')}}</label>
                                                <br>
                                                <img class="w_300" src="{{ asset($property->thumbnail_image) }}" alt="">
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{__('admin.Thumbnail Image')}} <span class="text-danger">*</span></label>
                                                <input type="file" name="thumbnail_image" class="form-control-file">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Slider Images (Multiple)')}} <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control-file" name="slider_images[]" multiple>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($property->video_link)
                                            <div class="form-group">
                                                <label for="">{{__('admin.Existing Video')}}</label>
                                                <br>

                                                <iframe width="300" height="150"
                                                src="https://www.youtube.com/embed/{{ $property->video_link }}">
                                                </iframe>
                                            </div>
                                        @endif

                                            <div class="form-group">
                                                <label for="">{{__('admin.Youtube Video Id')}}</label>
                                                <input type="text" class="form-control" name="video_link" value="{{ $property->video_link }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Aminities')}}</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <div>
                                                    @foreach ($aminities as $aminity)
                                                        @php
                                                            $isChecked=false;
                                                        @endphp
                                                        @foreach ($property->propertyAminities as $amnty)
                                                            @if ($aminity->id==$amnty->aminity_id)
                                                                @php
                                                                $isChecked=true;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        <input id="{{ $aminity->slug }}" {{ $isChecked ? 'checked' :'' }} value="{{ $aminity->id }}" type="checkbox" name="aminities[]" >
                                                        <label class="mx-1" for="{{ $aminity->slug }}">{{ $aminity->aminity }}</label>
                                                    @endforeach
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Nearest Locations')}}</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-8" id="nearest-place-box">

                                            @if ($property->propertyNearestLocations->count()>0)
                                                @foreach ($property->propertyNearestLocations as $property_item)
                                                    <div class="row" id="exist-nearest-location-{{ $property_item->id }}">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Nearest Locations')}}</label>
                                                                <select name="nearest_locations[]" id="" class="form-control">
                                                                    <option value="">{{__('admin.Select')}}</option>
                                                                    @foreach ($nearest_locatoins as $item)
                                                                    <option {{ $property_item->nearest_location_id==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->location }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="">{{__('admin.Distance(km)')}}</label>
                                                                <input type="text" class="form-control" name="distances[]" value="{{ $property_item->distance }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button onclick="existNearestLocation('{{ $property_item->id }}')" type="button" class="btn btn-danger nearest-row-btn plus_btn"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Nearest Locations')}}</label>
                                                        <select name="nearest_locations[]" id="" class="form-control">
                                                            <option value="">{{__('admin.Select')}}</option>
                                                            @foreach ($nearest_locatoins as $item)
                                                            <option value="{{ $item->id }}">{{ $item->location }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">{{__('admin.Distance(km)')}}</label>
                                                        <input type="text" class="form-control" name="distances[]">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button id="addNearestPlaceRow" type="button" class="btn btn-success nearest-row-btn plus_btn"><i class="fas fa-plus" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="hidden-location-box" class="d-none">
                                <div class="delete-dynamic-location">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Nearest Locations')}}</label>
                                                <select name="nearest_locations[]" id="" class="form-control">
                                                    <option value="">{{__('admin.Nearest Locations')}}</option>
                                                    @foreach ($nearest_locatoins as $item)
                                                    <option value="{{ $item->id }}">{{ $item->location }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Distance(km)')}}</label>
                                                <input type="text" class="form-control" name="distances[]">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger nearest-row-btn removeNearestPlaceRow plus_btn"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4>{{__('admin.Property Details and Google Map')}}</h4>
                                    <hr>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label>{{__('admin.Google Map Code')}}</label>
                                                <textarea class="form-control text-area-5" rows="5" name="google_map_embed_code">{{ $property->google_map_embed_code }}</textarea>
                                            </div>

                                            <div class="form-group mt-3">
                                                <label for="description">{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                                <textarea class="summernote" id="summernote" name="description">{{ $property->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="status">{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control">
                                                    <option {{ $property->status==1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                    <option {{ $property->status==0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="featured">{{__('admin.Featured')}} <span class="text-danger">*</span></label>
                                                <select name="featured" id="featured" class="form-control">
                                                    <option {{ $property->is_featured==0 ? 'selected': '' }} value="0">{{__('admin.No')}}</option>
                                                    <option {{ $property->is_featured==1 ? 'selected': '' }} value="1">{{__('admin.Yes')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="top_property">{{__('admin.Top Property')}} <span class="text-danger">*</span></label>
                                                <select name="top_property" id="top_property" class="form-control">
                                                    <option {{ $property->top_property==1 ? 'selected' : '' }} value="1">{{__('admin.Yes')}}</option>
                                                    <option {{ $property->top_property==0 ? 'selected' : '' }} value="0">{{__('admin.No')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="urgent_property">{{__('admin.Urgent Property')}} <span class="text-danger">*</span></label>
                                                <select name="urgent_property" id="urgent_property" class="form-control">
                                                    <option {{ $property->urgent_property==1 ? 'selected' : '' }} value="1">{{__('admin.Yes')}}</option>
                                                    <option {{ $property->urgent_property==0 ? 'selected' : '' }} value="0">{{__('admin.No')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="seo_title">{{__('admin.SEO Title')}}</label>
                                                <input type="text" name="seo_title" class="form-control" id="seo_title" value="{{ $property->seo_title }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="seo_description">{{__('admin.SEO Description')}}</label>
                                                <textarea name="seo_description" id="seo_description" cols="30" rows="3" class="form-control text-area-5" >{{ $property->seo_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-success">{{__('admin.Save')}}</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
          </div>
        </section>
      </div>





      <script>
        (function($) {
        "use strict";
        $(document).ready(function () {
            $("#title").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })

            $("#purpose").on("change",function(){
                var purposeId=$(this).val()
                if(purposeId==2){
                    $(".period_box").removeClass('d-none');
                }else if(purposeId==1){
                    $(".period_box").addClass('d-none');
                }
            })


             //start dynamic nearest place add and remove

             $("#addNearestPlaceRow").on("click",function(){
                var new_row=$("#hidden-location-box").html();
                $("#nearest-place-box").append(new_row)

            })
            $(document).on('click', '.removeNearestPlaceRow', function () {
                $(this).closest('.delete-dynamic-location').remove();
            });
            //end dynamic nearest place add and remove


        });

        })(jQuery);

        function convertToSlug(Text)
            {
                return Text
                    .toLowerCase()
                    .replace(/[^\w ]+/g,'')
                    .replace(/ +/g,'-');
            }

        function deleteSliderImg(id){

            var isDemo = "{{ env('PROJECT_MODE') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "{{ url('admin/property-slider-img/') }}"+"/"+ id,
                success: function (response) {
                    if(response.success){
                        toastr.success(response.success)
                        $(".slider-tr-"+id).remove()
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function deletePdfFile(id){

            var isDemo = "{{ env('PROJECT_MODE') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "{{ url('admin/property-delete-pdf/') }}"+"/"+ id,
                success: function (response) {
                    if(response.success){
                        toastr.success(response.success)
                        $(".pdf-file-col-"+id).remove()
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }


        function existNearestLocation(id){

            var isDemo = "{{ env('PROJECT_MODE') }}"
            if(isDemo == 0){
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "{{ url('admin/exist-nearest-location/') }}"+"/"+ id,
                success: function (response) {
                    if(response.success){
                        toastr.success(response.success)
                        $("#exist-nearest-location-"+id).remove()
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
@endsection
