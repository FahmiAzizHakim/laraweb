@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Create Category</h3>
      <div class="card-tools">
        <a href="{{ url('/master/categories') }}">
          <button type="button" class="btn btn-sm btn-default"><i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span></button>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <form action="{{ url('/master/categories') }}" method="POST">
            @csrf
            @include('pages.admin.master.categories._form')
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            <a href="{{ url('/master/categories') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
