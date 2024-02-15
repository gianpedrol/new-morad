@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Edit Language')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Language')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item active"><a href="{{ route('admin.languages.index') }}">{{__('admin.Languages')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Language')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.languages.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Languages')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.languages.update', $language->id) }}" method="POST">
                          @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ old('name', $language->name) }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Direction')}} <span class="text-danger">*</span></label>
                                    <select name="direction" class="form-control">
                                        <option value="ltr" {{old('direction', $language->direction) == 'ltr' ? 'selected' : ''}} >{{__('admin.ltr')}}</option>
                                        <option value="rtl" {{old('direction', $language->direction) == 'rtl' ? 'selected' : ''}}>{{__('admin.rtl')}}</option>
                                    </select>
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
