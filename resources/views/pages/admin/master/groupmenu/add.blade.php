@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Create Access Group</h3>
      <div class="card-tools">
        <a href="{{ url('/master/groupmenu') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-9">
          <form action="{{ url('/master/groupmenu') }}" method="POST">
            @csrf
            @include('pages.admin.master.groupmenu._form')

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Save
            </button>
            <a href="{{ url('/master/groupmenu') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
