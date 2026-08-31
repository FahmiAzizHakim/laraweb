@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Detail Pesan</h3>
      <div class="card-tools">
        <a href="{{ url('/message') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Kembali</span>
          </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-8">
          <dl class="row">
            <dt class="col-sm-3">Nama</dt>
            <dd class="col-sm-9">{{ $data->name }}</dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9"><a href="mailto:{{ $data->email }}">{{ $data->email }}</a></dd>

            @if($data->phone)
            <dt class="col-sm-3">Telepon</dt>
            <dd class="col-sm-9">{{ $data->phone }}</dd>
            @endif

            <dt class="col-sm-3">Subject</dt>
            <dd class="col-sm-9">{{ $data->subject }}</dd>

            <dt class="col-sm-3">Tanggal</dt>
            <dd class="col-sm-9">{{ optional($data->created_at)->format('d M Y H:i') }}</dd>

            <dt class="col-sm-3">Pesan</dt>
            <dd class="col-sm-9" style="white-space:pre-line;">{{ $data->message }}</dd>
          </dl>

          <a href="mailto:{{ $data->email }}?subject=RE: {{ $data->subject }}" class="btn btn-primary">
            <i class="fas fa-reply"></i> Balas via Email
          </a>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection
