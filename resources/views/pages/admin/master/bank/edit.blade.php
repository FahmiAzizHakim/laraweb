@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Edit Bank &mdash; {{ $data->bank_name }}</h3>
      <div class="card-tools">
        <a href="{{ url('/master/bank') }}">
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
          <form action="{{ url('/master/bank/'.$data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('pages.admin.master.bank._form', ['data' => $data])

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ url('/master/bank') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
