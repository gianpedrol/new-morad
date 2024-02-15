@extends('admin.master_layout')
@section('title')
<title>{{ $career->title }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $career->title }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{ $career->title }}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.career.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Career')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="table-responsive table-invoice">
                        <table class="table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th >{{__('admin.SN')}}</th>
                                    <th >{{__('admin.Name')}}</th>
                                    <th >{{__('admin.Phone')}}</th>
                                    <th >{{__('admin.Email')}}</th>
                                    <th >{{__('admin.Subject')}}</th>
                                    <th >{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach ($CareerRequests as $index => $CareerRequest)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $CareerRequest->name }}</td>
                                        <td>{{ $CareerRequest->phone }}</td>
                                        <td>{{ $CareerRequest->email }}</td>
                                        <td>
                                            {{ $CareerRequest->subject }}
                                        </td>
                                        <td>
                                            <a data-toggle="modal" data-target="#modelId-{{ $CareerRequest->id }}" href="javascript:;" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


      @foreach ($CareerRequests as $index => $CareerRequest)
      <!-- Modal -->
      <div class="modal fade" id="modelId-{{ $CareerRequest->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body">
                      <div class="container-fluid">
                        <table class="table table-bordered">
                            <tr>
                                <td>{{__('admin.Name')}}</td>
                                <td>{{ $CareerRequest->name }}</td>
                            </tr>
                            <tr>
                                <td>{{__('admin.Phone')}}</td>
                                <td>{{ $CareerRequest->phone }}</td>
                            </tr>
                            <tr>
                                <td>{{__('admin.Email')}}</td>
                                <td>{{ $CareerRequest->email }}</td>
                            </tr>

                            <tr>
                                <td>{{__('admin.Subject')}}</td>
                                <td>{{ $CareerRequest->subject }}</td>
                            </tr>



                            <tr>
                                <td>{{__('admin.Description')}}</td>
                                <td>{{ $CareerRequest->description }}</td>
                            </tr>

                            <tr>
                                <td>{{__('admin.View Document')}}</td>
                                <td>
                                    <a target="_blank" href="https://drive.google.com/viewerng/viewer?url={{ asset('uploads/custom-images/'.$CareerRequest->cv) }}" class="btn btn-primary btn-sm">{{__('admin.Click Here')}}</a>
                                </td>
                            </tr>
                        </table>

                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                  </div>
              </div>
          </div>
      </div>
      @endforeach
@endsection
