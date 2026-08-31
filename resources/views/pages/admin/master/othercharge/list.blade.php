@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Other Charges</h3>

      <div class="card-tools">
        <a href="{{ url('/master/othercharge/add') }}">
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
              <th style="width:120px;">Code</th>
              <th>Name</th>
              <th>Description</th>
              <th style="width:140px;" class="text-right">Price</th>
              <th style="width:90px;">Status</th>
              <th style="width: 120px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>{{ $row->code }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ \Illuminate\Support\Str::limit($row->description, 60) }}</td>
                <td class="text-right">{{ number_format($row->price, 2) }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/master/othercharge/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ url('/master/othercharge/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete charge &quot;{{ $row->name }}&quot;?');">
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
