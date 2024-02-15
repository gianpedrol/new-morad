@extends('staff.master_layout')
@section('title')
<title>{{__('admin.Create Property')}}</title>
@endsection
@section('staff-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Property')}}</h1>
          </div>

          <div class="section-body property_box">
            <a href="{{ route('staff.property.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Property List')}}</a>
            <div class="row mt-4">
                <form action="{{ route('staff.property.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4>{{__('admin.Basic Information')}}</h4>
                                <hr>

                                <div class="form-group">
                                    <label for="title">{{__('admin.Title')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
                                </div>

                                <div class="form-group">
                                    <label for="slug">{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug') }}">
                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">{{__('admin.Property Type')}} <span class="text-danger">*</span></label>
                                            <select name="property_type" id="property_type" class="form-control select2">
                                                <option value="">{{__('admin.Select')}}</option>
                                                @foreach ($propertyTypes as $item)
                                                <option {{ old('property_type')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->type }}</option>
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
                                                <option {{ old('city')==$item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">{{__('admin.Address')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">{{__('admin.Phone')}}</label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="website">{{__('admin.Website')}}</label>
                                            <input type="url" name="website" value="{{ old('website') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purpose">{{__('admin.Purpose')}} <span class="text-danger">*</span></label>
                                            <select name="purpose" id="purpose" class="form-control">
                                                @foreach ($purposes as $item)
                                                <option value="{{ $item->id }}">{{ $item->custom_purpose }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                            <input type="text" name="price" class="form-control" value="{{ old('price') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-none" id="period_box">
                                        <div class="form-group">
                                            <label for="period">{{__('admin.Period')}} <span class="text-danger">*</span></label>
                                            <select name="period" id="period" class="form-control">
                                                <option value="Daily">{{__('admin.Daily')}}</option>
                                                <option value="Monthly">{{__('admin.Monthly')}}</option>
                                                <option value="Yearly">{{__('admin.Yearly')}}</option>
                                            </select>
                                        </div>
                                    </div>
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
                                            <input type="text" name="area" value="{{ old('area') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Unit')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="unit" value="{{ old('unit') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Room')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="room" value="{{ old('room') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Bedroom')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="bedroom" value="{{ old('bedroom') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Bathroom')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="bathroom" value="{{ old('bathroom') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Floor')}} <span class="text-danger">*</span></label>
                                            <input type="number" name="floor" value="{{ old('floor') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Kitchen')}} </label>
                                            <input type="number" name="kitchen" value="{{ old('kitchen') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Total Parking Place')}}</label>
                                            <input type="number" name="parking" value="{{ old('parking') }}" class="form-control">
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
                                        <div class="form-group">
                                            <label for="">{{__('admin.PDF File')}}</label>
                                            <input type="file" name="pdf_file" class="form-control-file">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">{{__('admin.Bannner Image')}} <span class="text-danger">*</span></label>
                                            <input type="file" name="banner_image" class="form-control-file">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
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
                                        <div class="form-group">
                                            <label for="">{{__('admin.Youtube Video Id')}}</label>
                                            <input type="text" class="form-control" name="video_link" value="{{ old('video_link') }}">
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
                                                        <input id="{{ $aminity->slug }}" {{ $isChecked ? 'checked' :'' }} value="{{ $aminity->id }}" type="checkbox" name="aminities[]" >
                                                        <label class="mx-1" for="{{ $aminity->slug }}">{{ $aminity->aminity }}</label>

                                                    @else
                                                        <input value="{{ $aminity->id }}" type="checkbox" name="aminities[]" id="{{ $aminity->slug }}">
                                                        <label class="mx-1" for="{{ $aminity->slug }}">{{ $aminity->aminity }}</label>
                                                    @endif
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
                                            <textarea class="form-control text-area-5" rows="5" name="google_map_embed_code">{{ old('google_map_embed_code') }}</textarea>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label for="description">{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                            <textarea class="summernote" id="summernote" name="description">{{ old('description') }}</textarea>
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
                                                <option  value="1">{{__('admin.Active')}}</option>
                                                <option  value="0">{{__('admin.Inactive')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="featured">{{__('admin.Featured')}} <span class="text-danger">*</span></label>
                                            <select name="featured" id="featured" class="form-control">
                                                <option {{ old('featured')==0 ? 'selected': '' }} value="0">{{__('admin.No')}}</option>
                                                <option {{ old('featured')==1 ? 'selected': '' }} value="1">{{__('admin.Yes')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="top_property">{{__('admin.Top Property')}} <span class="text-danger">*</span></label>
                                            <select name="top_property" id="top_property" class="form-control">
                                                <option value="1">{{__('admin.Yes')}}</option>
                                                <option value="0">{{__('admin.No')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="urgent_property">{{__('admin.Urgent Property')}} <span class="text-danger">*</span></label>
                                            <select name="urgent_property" id="urgent_property" class="form-control">
                                                <option value="1">{{__('admin.Yes')}}</option>
                                                <option value="0">{{__('admin.No')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="seo_title">{{__('admin.SEO Title')}}</label>
                                            <input type="text" name="seo_title" class="form-control" id="seo_title" value="{{ old('seo_title') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="seo_description">{{__('admin.SEO Description')}}</label>
                                            <textarea name="seo_description" id="seo_description" cols="30" rows="3" class="form-control text-area-5" >{{ old('seo_description') }}</textarea>
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
                    $("#period_box").removeClass('d-none');
                }else if(purposeId==1){
                    $("#period_box").addClass('d-none');
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
    </script>

@endsection
