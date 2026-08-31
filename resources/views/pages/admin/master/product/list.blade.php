@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Products</h3>
      <div class="card-tools">
        <a href="{{ url('/master/product/add') }}">
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
            <th style="width:90px;">Image</th>
            <th>Name</th>
            <th>Code</th>
            <th>Price</th>
            <th>Service</th>
            <th>Categories</th>
            <th>Specs</th>
            <th>Status</th>
            <th style="width: 120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $row)
          <tr>
            <td>
              @php $first = $row->images->first(); @endphp
              @if($first)
                <img src="{{ asset($first->image_url) }}" alt="" style="max-height:50px;max-width:70px;background:#eee;padding:2px;border-radius:3px;">
              @else
                <span class="text-muted">&mdash;</span>
              @endif
            </td>
            <td>{{ $row->products_name }}</td>
            <td><code>{{ $row->products_code }}</code></td>
            <td>{{ number_format($row->products_price, 0, ',', '.') }}</td>
            <td>{{ optional($row->service)->service_name ?: '-' }}</td>
            <td>
              @forelse($row->categories as $cat)
                <span class="badge badge-info">{{ $cat->category_name }}</span>
              @empty
                <span class="text-muted">-</span>
              @endforelse
            </td>
            <td>
              @if($row->specifications->count())
                <span class="badge badge-secondary" title="{{ $row->specifications->map(fn ($s) => $s->attribute.': '.$s->value)->implode(', ') }}">
                  {{ $row->specifications->count() }} spec{{ $row->specifications->count() > 1 ? 's' : '' }}
                </span>
              @else
                <span class="text-muted">-</span>
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
              <a href="{{ url('/master/product/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                <i class="fas fa-edit"></i> Edit
              </a>
              <form action="{{ url('/master/product/'.$row->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Delete product &quot;{{ $row->products_name }}&quot;?');">
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
