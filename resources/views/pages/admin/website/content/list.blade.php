@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">News &amp; Articles</h3>

      <div class="card-tools">
        <a href="{{ url('/website/content/add') }}">
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
              <th style="width:130px;">Cover</th>
              <th>Title</th>
              <th>Description</th>
              <th>Date</th>
              <th>Status</th>
              <th style="width: 150px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>
                  @if($row->media)
                    <img src="{{ asset($row->media) }}" alt="{{ $row->content_title }}"
                         style="max-height:55px;max-width:110px;background:#eee;padding:2px;border-radius:3px;">
                  @else
                    <span class="text-muted">&mdash;</span>
                  @endif
                </td>
                <td>{{ $row->content_title }}</td>
                <td>{{ \Illuminate\Support\Str::limit($row->description ?? '', 70) }}</td>
                <td>{{ optional($row->created_at)->format('d M Y') }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/content/'.$row->id) }}" target="_blank" class="btn btn-xs btn-info">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ url('/website/content/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ url('/website/content/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete content &quot;{{ $row->content_title }}&quot;?');">
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
