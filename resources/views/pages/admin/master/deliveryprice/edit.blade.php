@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Edit Delivery Price</h3>
      <div class="card-tools">
        <a href="{{ url('/master/deliveryprice') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <form action="{{ url('/master/deliveryprice/'.$data->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('pages.admin.master.deliveryprice._form', [
              'data' => $data,
              'districts' => $districts,
              'subdistricts' => $subdistricts,
            ])

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ url('/master/deliveryprice') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
