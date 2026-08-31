@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Delivery Prices</h3>

      <div class="card-tools">
        <a href="{{ url('/master/deliveryprice/add') }}">
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
              <th style="width:110px;">Level</th>
              <th>City</th>
              <th>District</th>
              <th>Subdistrict</th>
              <th style="width:150px;">Price</th>
              <th style="width:90px;">Status</th>
              <th style="width:120px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $row)
              <tr>
                <td>
                  @php $lvl = $row->level; @endphp
                  <span class="badge {{ $lvl==='subdistrict' ? 'badge-info' : ($lvl==='district' ? 'badge-primary' : 'badge-dark') }}">
                    {{ ucfirst($lvl) }}
                  </span>
                </td>
                <td>{{ $row->city_name ?? $row->city_code }}</td>
                <td>{{ $row->district_name ?? ($row->district_code ? $row->district_code : '—') }}</td>
                <td>{{ $row->subdistrict_name ?? ($row->subdistrict_code ? $row->subdistrict_code : '—') }}</td>
                <td class="text-right">{{ number_format((float) $row->price, 2) }}</td>
                <td>
                  @if($row->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </td>
                <td>
                  <a href="{{ url('/master/deliveryprice/'.$row->id.'/edit') }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ url('/master/deliveryprice/'.$row->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Delete this delivery price?');">
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
