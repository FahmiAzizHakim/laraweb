@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Edit Content &mdash; {{ $data->content_title }}</h3>
      <div class="card-tools">
        <a href="{{ url('/content/'.$data->id) }}" target="_blank">
          <button type="button" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i> <span>&nbsp; View</span>
          </button>
        </a>
        <a href="{{ url('/website/content') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-10">
          <form action="{{ url('/website/content/'.$data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('pages.admin.website.content._form', ['data' => $data])

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ url('/website/content') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
