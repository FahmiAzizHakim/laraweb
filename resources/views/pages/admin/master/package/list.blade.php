@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Packages</h3>
      <div class="card-tools">
        <a href="{{ url('/master/package/add') }}">
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
            <th style="width:170px;">Service</th>
            <th>Name</th>
            <th style="width:120px;">Code</th>
            <th style="width:130px;" class="text-right">Price</th>
            <th style="width:130px;" class="text-right">Discount</th>
            <th style="width:130px;" class="text-right">Net</th>
            <th>Contents</th>
            <th style="width:90px;">Status</th>
            <th style="width:120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $row)
          <tr>
            <td>
              @if($row->service)
                {{ $row->service->service_name }}
              @else
                <span class="text-muted">&mdash;</span>
              @endif
            </td>
            <td>{{ $row->package_name }}</td>
            <td><code>{{ $row->package_code }}</code></td>
            <td class="text-right">{{ number_format($row->package_price, 2) }}</td>
            <td class="text-right">{{ number_format($row->package_discount, 2) }}</td>
            <td class="text-right"><strong>{{ number_format($row->net_price, 2) }}</strong></td>
            <td>
              @forelse($row->details as $detail)
                @php
                  $badge = ['product' => 'badge-info', 'charge' => 'badge-warning', 'benefit' => 'badge-primary'][$detail->line_type];
                @endphp
                <span class="badge {{ $badge }}">{{ $detail->qty > 1 ? $detail->qty.'x ' : '' }}{{ $detail->line_label }}</span>
              @empty
                <span class="text-muted">-</span>
              @endforelse
            </td>
            <td>
              @if($row->is_active)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-secondary">Inactive</span>
              @endif
            </td>
            <td>
              <a href="{{ url('/master/package/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form action="{{ url('/master/package/'.$row->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete package &quot;{{ $row->package_name }}&quot;?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot></tfoot>
      </table>
    </div>
  </div>
@endsection
