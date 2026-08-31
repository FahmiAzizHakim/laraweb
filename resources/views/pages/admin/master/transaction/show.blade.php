@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@section('content')
  <div class="row">
    <!-- ================= LEFT: transaction detail ================= -->
    <div class="col-md-8">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">
            Receipt {{ $data->receipt_no ?: '#'.$data->id }}
            <span class="badge badge-info ml-2">{{ optional($data->statusCode)->name ?? $data->status }}</span>
          </h3>
          <div class="card-tools">
            <a href="{{ url('/master/transaction') }}">
              <button type="button" class="btn btn-sm btn-default">
                <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
              </button>
            </a>
          </div>
        </div>
        <div class="card-body">
          {{-- Header info --}}
          <div class="row mb-3">
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Date</dt>
                <dd class="col-sm-7">{{ optional($data->transaction_date)->format('d M Y') ?: '—' }}</dd>
                <dt class="col-sm-5">Payment</dt>
                <dd class="col-sm-7">{{ optional($data->paymentTypeCode)->name ?? ($data->payment_type ?: '—') }}</dd>
              </dl>
            </div>
            <div class="col-md-6">
              <dl class="row mb-0">
                <dt class="col-sm-5">Customer</dt>
                <dd class="col-sm-7">{{ $data->customer_name ?: '—' }}</dd>
                <dt class="col-sm-5">Email</dt>
                <dd class="col-sm-7">{{ $data->customer_email ?: '—' }}</dd>
                <dt class="col-sm-5">Phone</dt>
                <dd class="col-sm-7">{{ $data->customer_phone ?: '—' }}</dd>
              </dl>
            </div>
          </div>

          {{-- Packages: priced in full here, their contents are the 0-priced
               rows in Items / Other Charges / Benefits below. --}}
          @if($data->packages->count())
          <h5>Packages</h5>
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>Package</th>
                <th style="width:90px;" class="text-right">Qty</th>
                <th style="width:120px;" class="text-right">Price</th>
                <th style="width:120px;" class="text-right">Disc</th>
                <th style="width:130px;" class="text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data->packages as $pk)
              <tr>
                <td>
                  {{ $pk->package_name }}
                  @if($pk->package_code)<br><small class="text-muted">{{ $pk->package_code }}</small>@endif
                </td>
                <td class="text-right">{{ $pk->qty }}</td>
                <td class="text-right">{{ number_format($pk->price, 2) }}</td>
                <td class="text-right">{{ number_format($pk->discount, 2) }}</td>
                <td class="text-right">{{ number_format($pk->subtotal, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          {{-- Line items --}}
          <h5 class="{{ $data->packages->count() ? 'mt-3' : '' }}">Items</h5>
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th style="width:90px;" class="text-right">Qty</th>
                <th style="width:120px;" class="text-right">Price</th>
                <th style="width:120px;" class="text-right">Disc</th>
                <th style="width:130px;" class="text-right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @forelse($data->details as $d)
              <tr>
                <td>
                  {{ $d->product_name }}
                  @if($d->variant_name)<small class="text-muted">— {{ $d->variant_name }}</small>@endif
                  @if($d->product_code)<br><small class="text-muted">{{ $d->product_code }}</small>@endif
                  @if($d->remark)<br><small class="text-muted font-italic">{{ $d->remark }}</small>@endif
                </td>
                <td class="text-right">{{ $d->qty }} {{ $d->uom }}</td>
                <td class="text-right">{{ number_format($d->price, 2) }}</td>
                <td class="text-right">{{ number_format($d->discount, 2) }}</td>
                <td class="text-right">{{ number_format($d->subtotal, 2) }}</td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted">No items</td></tr>
              @endforelse
            </tbody>
          </table>

          {{-- Other charges --}}
          @if($data->chargeItems->count())
          <h5 class="mt-3">Other Charges</h5>
          <table class="table table-sm table-bordered">
            <tbody>
              @foreach($data->chargeItems as $c)
              <tr>
                <td>{{ $c->name }} @if($c->code)<small class="text-muted">({{ $c->code }})</small>@endif</td>
                <td style="width:130px;" class="text-right">{{ number_format($c->amount, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          {{-- Benefits: perks that came with a package, never priced. --}}
          @if($data->benefits->count())
          <h5 class="mt-3">Benefits</h5>
          <table class="table table-sm table-bordered">
            <tbody>
              @foreach($data->benefits as $b)
              <tr>
                <td>
                  {{ $b->qty > 1 ? $b->qty.'x ' : '' }}{{ $b->benefit_name }}
                  @if($b->package_name)<small class="text-muted">— {{ $b->package_name }}</small>@endif
                </td>
                <td style="width:130px;" class="text-right">{{ number_format($b->subtotal, 2) }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @endif

          {{-- Totals --}}
          <div class="row justify-content-end">
            <div class="col-md-6">
              <table class="table table-sm">
                <tr><th>Price (items + packages)</th><td class="text-right">{{ number_format($data->price, 2) }}</td></tr>
                <tr><th>Discount</th><td class="text-right">-{{ number_format($data->discount, 2) }}</td></tr>
                <tr><th>Delivery Fee</th><td class="text-right">{{ number_format($data->delivery_fee, 2) }}</td></tr>
                <tr><th>Other Charges</th><td class="text-right">{{ number_format($data->chargeItems->sum('amount'), 2) }}</td></tr>
                <tr><th>Total</th><td class="text-right">{{ number_format($data->total, 2) }}</td></tr>
                <tr><th>Tax</th><td class="text-right">{{ number_format($data->tax, 2) }}</td></tr>
                <tr class="font-weight-bold"><th>Grand Total</th><td class="text-right">{{ number_format($data->grandtotal, 2) }}</td></tr>
              </table>
            </div>
          </div>

          @if($data->remark)
            <div class="mt-2"><strong>Remark:</strong> {{ $data->remark }}</div>
          @endif
        </div>
      </div>

      {{-- Shipping address --}}
      @if($data->address)
      <div class="card card-default">
        <div class="card-header"><h3 class="card-title">Shipping Address</h3></div>
        <div class="card-body">
          @if($data->address->recipient_name || $data->address->recipient_phone)
            <p class="mb-1"><strong>{{ $data->address->recipient_name }}</strong> {{ $data->address->recipient_phone }}</p>
          @endif
          <p class="mb-1">{{ $data->address->full_address }}</p>
          <p class="mb-0 text-muted"><small>Delivery fee: {{ number_format($data->address->delivery_fee, 2) }}</small></p>
        </div>
      </div>
      @endif
    </div>

    <!-- ================= RIGHT: status + history ================= -->
    <div class="col-md-4">
      {{-- Update status --}}
      <div class="card card-default">
        <div class="card-header"><h3 class="card-title">Update Status</h3></div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif
          <form action="{{ url('/master/transaction/'.$data->id.'/status') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                @foreach($statuses as $s)
                  <option value="{{ $s->code }}" {{ $data->status == $s->code ? 'selected' : '' }}>
                    {{ $s->name }}@if($s->description) — {{ $s->description }}@endif
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea name="description" class="form-control" rows="2"
                        placeholder="Note about this change (optional)">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success btn-block">
              <i class="fas fa-sync-alt"></i> Update Status
            </button>
          </form>
        </div>
      </div>

      {{-- History timeline --}}
      <div class="card card-default">
        <div class="card-header"><h3 class="card-title">Status History</h3></div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @forelse($data->histories as $h)
            <li class="list-group-item">
              <div class="d-flex justify-content-between">
                <span class="badge badge-info">{{ $h->status_name ?: $h->status_code }}</span>
                <small class="text-muted">{{ optional($h->created_at)->format('d M Y H:i') }}</small>
              </div>
              @if($h->description)<div class="mt-1">{{ $h->description }}</div>@endif
              @if($h->created_by)<small class="text-muted">by {{ $h->created_by }}</small>@endif
            </li>
            @empty
            <li class="list-group-item text-muted text-center">No history yet</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
