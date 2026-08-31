@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">List Users</h3>

      <div class="card-tools">
        <a href="{{ url('/master/user/add') }}">
        <button type="button" class="btn btn-sm btn-success">
          <i class="fas fa-plus"></i>
          <span>&nbsp; Create</span>
        </button>
        </a>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped dtgenerals">
            <thead>
            <tr>
              <th>Nama</th>
              <th>Email</th>
              <th>Akses Group</th>
              <th>Status</th>
              <th style="width: 120px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->email }}</td>
                <td><code>{{ $row->roles_code }}</code></td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Aktif</span>
                  @else
                    <span class="badge badge-secondary">Nonaktif</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/master/user/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  @if(auth()->id() != $row->id)
                  <form action="{{ url('/master/user/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete user &quot;{{ $row->email }}&quot;?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                  @endif
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
