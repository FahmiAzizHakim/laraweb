@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">About Sections</h3>
      <div class="card-tools">
        <a href="{{ url('/website/about/add') }}">
        <button type="button" class="btn btn-sm btn-success">
          <i class="fas fa-plus"></i> <span>&nbsp; Create</span>
        </button>
        </a>
      </div>
    </div>
    <div class="card-body">
      <p class="text-muted">
        The public site shows these in order &mdash; the first one is the main
        &ldquo;About&rdquo; block. Add more and they render underneath it.
      </p>

      <table class="table table-bordered table-striped dtgenerals">
        <thead>
          <tr>
            <th style="width:70px;">Order</th>
            <th style="width:110px;">Image</th>
            <th>Title</th>
            <th>Content</th>
            <th style="width:90px;">Status</th>
            <th style="width:120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $row)
          <tr>
            <td>
              {{ $row->order }}
              @if($loop->first && $row->is_active)
                <br><span class="badge badge-info">main</span>
              @endif
            </td>
            <td>
              @if($row->about_image)
                <img src="{{ asset($row->about_image) }}" alt="" style="max-height:50px;max-width:90px;background:#eee;padding:2px;border-radius:3px;">
              @else
                <span class="text-muted">&mdash;</span>
              @endif
            </td>
            <td>
              {{ $row->about_title }}
              @if($row->about_subtitle)<br><small class="text-muted">{{ $row->about_subtitle }}</small>@endif
            </td>
            <td>
              <small class="text-muted">{{ \Illuminate\Support\Str::limit($row->about_content, 140) }}</small>
              @if(count($row->paragraphs) > 1)
                <br><span class="badge badge-secondary">{{ count($row->paragraphs) }} paragraphs</span>
              @endif
            </td>
            <td>
              @if($row->is_active)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <a href="{{ url('/website/about/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form action="{{ url('/website/about/'.$row->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete about &quot;{{ $row->about_title }}&quot;?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted">No about section yet</td></tr>
          @endforelse
        </tbody>
        <tfoot></tfoot>
      </table>
    </div>
  </div>
@endsection
