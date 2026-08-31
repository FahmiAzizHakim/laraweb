@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Hero Banners</h3>

      <div class="card-tools">
        <a href="{{ url('/website/banner/add') }}">
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
              <th style="width:160px;">Image</th>
              <th>Title</th>
              <th>Text</th>
              <th>Status</th>
              <th style="width: 120px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>
                  @if($row->banner_img)
                    <img src="{{ asset($row->banner_img) }}" alt="{{ $row->banner_title }}"
                         style="max-height:60px;max-width:140px;background:#eee;padding:2px;border-radius:3px;">
                  @else
                    <span class="text-muted">&mdash;</span>
                  @endif
                </td>
                <td>{{ $row->banner_title }}</td>
                <td>{{ \Illuminate\Support\Str::limit($row->banner_text ?? '', 80) }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/website/banner/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ url('/website/banner/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete banner &quot;{{ $row->banner_title }}&quot;?');">
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
