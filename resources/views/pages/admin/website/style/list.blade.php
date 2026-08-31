@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Website Styles</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped dtgenerals">
            <thead>
            <tr>
              <th>Group</th>
              <th>Label</th>
              <th>Key</th>
              <th>Value</th>
              <th>Preview</th>
              <th>Type</th>
              <th>Status</th>
              <th style="width: 90px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>{{ $row->style_group }}</td>
                <td>{{ $row->style_label }}</td>
                <td><code>{{ $row->style_key }}</code></td>
                <td>{{ $row->style_value }}</td>
                <td>
                  @if(in_array($row->style_type, ['color', 'gradient']))
                    <span style="display:inline-block;width:60px;height:22px;border:1px solid #ced4da;border-radius:4px;vertical-align:middle;background:{{ $row->style_value }};"></span>
                  @else
                    <span class="text-muted">&mdash;</span>
                  @endif
                </td>
                <td>{{ ucfirst($row->style_type) }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/website/style/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
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
