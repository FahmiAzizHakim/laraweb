@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Pesan Masuk</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped dtgenerals">
            <thead>
            <tr>
              <th>Status</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Tanggal</th>
              <th style="width: 90px;">Aksi</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              @php $unread = $row->status !== 'read'; @endphp
              <tr class="{{ $unread ? 'font-weight-bold' : '' }}">
                <td>
                  @if($unread)
                    <span class="badge badge-warning">Unread</span>
                  @else
                    <span class="badge badge-secondary">Read</span>
                  @endif
                </td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ \Illuminate\Support\Str::limit($row->subject, 50) }}</td>
                <td>{{ optional($row->created_at)->format('d M Y H:i') }}</td>
                <td>
                  <a href="{{ url('/message/'.$row->id) }}" class="btn btn-xs btn-info">
                    <i class="fas fa-eye"></i> Lihat
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
@endsection
