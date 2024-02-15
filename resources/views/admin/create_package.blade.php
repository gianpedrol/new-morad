@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Create Package')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Create Package')}}</h1>
          </div>
          <div class="section-body">
            <a href="{{ route('admin.package.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Package')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.package.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-12 py-2">
                                    <label class="text-danger text-bold">* For any Unlimitd Quantity = -1 </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="package_type">{{__('admin.Package Type')}} <span class="text-danger">*</span></label>
                                        <select name="package_type" id="package_type" class="form-control">
                                            <option value="1">{{__('admin.Premium')}}</option>
                                            <option value="0">{{__('admin.Free')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="package_name">{{__('admin.Package Name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="package_name" class="form-control" id="package_name" value="{{ old('package_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4" id="price-row">
                                    <div class="form-group">
                                        <label for="price">{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="price" class="form-control" id="price" value="{{ old('price') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_days">{{__('admin.Number Of Days')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_days" class="form-control" id="number_of_days" value="{{ old('number_of_days') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_property">{{__('admin.Number Of Property')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_property" class="form-control" id="number_of_property" value="{{ old('number_of_property') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_aminities">{{__('admin.Number of Aminities')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_aminities" class="form-control" id="number_of_aminities" value="{{ old('number_of_aminities') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_nearest_place">{{__('admin.Number of Nearest Place')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_nearest_place" class="form-control" id="number_of_nearest_place" value="{{ old('number_of_nearest_place') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="number_of_photo">{{__('admin.Number Of Photo')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_photo" class="form-control" id="number_of_photo" value="{{ old('number_of_photo') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="feature">{{__('admin.Allow Feature')}} <span class="text-danger">*</span></label>
                                        <select name="feature" id="feature" class="form-control">
                                            <option  value="1">{{__('admin.Yes')}}</option>
                                            <option  value="0">{{__('admin.No')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" id="feature-row">
                                    <div class="form-group">
                                        <label for="number_of_feature_property">{{__('admin.Number Of Featured Property')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_feature_property" id="number_of_feature_property" class="form-control" value="{{ old('number_of_feature_property') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="top_property">{{__('admin.Allow Top Property')}} <span class="text-danger">*</span></label>
                                        <select name="top_property" id="top_property" class="form-control">
                                            <option  value="1">{{__('admin.Yes')}}</option>
                                            <option  value="0">{{__('admin.No')}}</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-4" id="top-row">
                                    <div class="form-group">
                                        <label for="number_of_top_property">{{__('admin.Number Of Top Property')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_top_property" id="number_of_top_property" class="form-control" value="{{ old('number_of_top_property') }}">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="urgent">{{__('admin.Allow Urgent Property')}}<span class="text-danger">*</span></label>
                                        <select name="urgent" id="urgent" class="form-control">
                                            <option  value="1">{{__('admin.Yes')}}</option>
                                            <option  value="0">{{__('admin.No')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4" id="urgent-row">
                                    <div class="form-group">
                                        <label for="number_of_urgent_property">{{__('admin.Number Of Urgent Property')}} <span class="text-danger">*</span></label>
                                        <input type="number" name="number_of_urgent_property" id="number_of_urgent_property" class="form-control" value="{{ old('number_of_urgent_property') }}">
                                    </div>
                                </div>




                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">{{__('admin.Package Order')}} <span class="text-danger">*</span></label>
                                        <input type="text" id="package_order"name="package_order" class="form-control">
                                        <span class="text-danger d-none" id="order-error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option  value="1">{{__('admin.Active')}}</option>
                                            <option  value="0">{{__('admin.Inactive')}}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-success">{{__('admin.Save')}}</button>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>



      <script>
        (function($) {
        "use strict";
        $(document).ready(function () {
            $("#package_type").on('change',function(){
                var type=$("#package_type").val()
                if(type==0){
                    $("#price-row").addClass('d-none')
                }
                if(type==1){
                    $("#price-row").removeClass('d-none')
                }

            })

            $("#feature").on('change',function(){
                var type=$("#feature").val()
                if(type==0){
                    $("#feature-row").addClass('d-none')
                }
                if(type==1){
                    $("#feature-row").removeClass('d-none')
                }

            })

            $("#top_property").on('change',function(){
                var type=$("#top_property").val()
                if(type==0){
                    $("#top-row").addClass('d-none')
                }
                if(type==1){
                    $("#top-row").removeClass('d-none')
                }

            })

            $("#urgent").on('change',function(){
                var type=$("#urgent").val()
                if(type==0){
                    $("#urgent-row").addClass('d-none')
                }
                if(type==1){
                    $("#urgent-row").removeClass('d-none')
                }

            })

            $("#package_order").on('keyup',function(){
                var text=$("#package_order").val()
                if(isNaN(text)){
                    $("#order-error").text('Please insert positive number')
                    $("#order-error").removeClass('d-none')
                }else{
                    if(text<0){
                        $("#order-error").text('Please insert positive number')
                        $("#order-error").removeClass('d-none')
                    }else{
                        $("#order-error").addClass('d-none')
                    }
                }
            })


        });

        })(jQuery);
    </script>
@endsection
