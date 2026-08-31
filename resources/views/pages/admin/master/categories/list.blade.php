@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Categories</h3>
      <div class="card-tools">
        <a href="{{ url('/master/categories/add') }}">
        <button type="button" class="btn btn-sm btn-success">
          <i class="fas fa-plus"></i> <span>&nbsp; Create</span>
        </button>
        </a>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped dtgenerals">
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Status</th>
            <th style="width: 120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $row)
          <tr>
            <td><code>{{ $row->category_code }}</code></td>
            <td>{{ $row->category_name }}</td>
            <td>{{ optional($row->parent)->category_name ?: '-' }}</td>
            <td>
              @if($row->is_active)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <a href="{{ url('/master/categories/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form action="{{ url('/master/categories/'.$row->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete category &quot;{{ $row->category_name }}&quot;?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
