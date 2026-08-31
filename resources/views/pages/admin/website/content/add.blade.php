@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Create Content</h3>
      <div class="card-tools">
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
          <form action="{{ url('/website/content') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('pages.admin.website.content._form')

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Save
            </button>
            <a href="{{ url('/website/content') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
