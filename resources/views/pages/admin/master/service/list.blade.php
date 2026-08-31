@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Services</h3>

      <div class="card-tools">
        <a href="{{ url('/master/service/add') }}">
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
              <th style="width:80px;">Icon</th>
              <th style="width:130px;">Image</th>
              <th>Name</th>
              <th>Title</th>
              <th>Status</th>
              <th style="width: 120px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>
                  @if($row->service_icon)
                    <img src="{{ asset($row->service_icon) }}" alt="icon" style="max-height:40px;max-width:60px;">
                  @else
                    <span class="text-muted">&mdash;</span>
                  @endif
                </td>
                <td>
                  @if($row->service_image)
                    <img src="{{ asset($row->service_image) }}" alt="image" style="max-height:55px;max-width:110px;background:#eee;padding:2px;border-radius:3px;">
                  @else
                    <span class="text-muted">&mdash;</span>
                  @endif
                </td>
                <td>{{ $row->service_name }}</td>
                <td>{{ \Illuminate\Support\Str::limit($row->service_title, 50) }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/master/service/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ url('/master/service/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete service &quot;{{ $row->service_name }}&quot;?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-xs btn-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
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
