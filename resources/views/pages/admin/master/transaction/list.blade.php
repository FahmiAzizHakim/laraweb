@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Transactions</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-striped dtgenerals">
            <thead>
            <tr>
              <th style="width:60px;">No</th>
              <th>Receipt</th>
              <th style="width:110px;">Date</th>
              <th>Customer</th>
              <th style="width:150px;" class="text-right">Grand Total</th>
              <th style="width:130px;">Status</th>
              <th style="width:90px;">Action</th>
            </tr>
            </thead>
            <tbody>
              @foreach($data as $i => $row)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->receipt_no ?: '—' }}</td>
                <td>{{ optional($row->transaction_date)->format('d M Y') }}</td>
                <td>{{ $row->customer_name ?: '—' }}</td>
                <td class="text-right">{{ number_format($row->grandtotal, 2) }}</td>
                <td>
                  <span class="badge badge-info">{{ optional($row->statusCode)->name ?? $row->status ?? '—' }}</span>
                </td>
                <td>
                  <a href="{{ url('/master/transaction/'.$row->id) }}" class="btn btn-xs btn-primary">
                    <i class="fas fa-eye"></i> View
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
