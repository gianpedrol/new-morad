@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Testimonial')}} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Testimonial')}} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.testimonial.index') }}">{{__('admin.Testimonial')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Testimonial')}} {{__('admin.Translations')}} ({{ strtoupper(request('code')) }})</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.testimonial.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Testimonial')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.testimonial.translation.update', ['id' => request('id'), 'code' => request('code')]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $testimonial->name }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Desgination')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="designation" class="form-control"  name="designation" value="{{ $testimonial->designation }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Comment')}} <span class="text-danger">*</span></label>
                                    <textarea name="comment" id="comment" cols="30" rows="10" class="form-control text-area-5">{{ $testimonial->comment }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
@endsection
