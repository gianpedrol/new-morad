@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Send Mail')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Send Mail')}}</h1>
          </div>

        <div class="section-body">
            <a href="{{ route('admin.regular-user') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Regular User')}}</a>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h1>{{__('admin.Send Mail to all User')}}</h1>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.send-mail-to-all-user') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{__('admin.Subject')}}</label>
                                    <input type="text" name="subject" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">{{__('admin.Message')}}</label>
                                    <textarea name="message" id="message" class="summernote" cols="30" rows="10"></textarea>
                                </div>
                                <button class="btn btn-primary">{{__('admin.Send Email')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
      </div>
@endsection
