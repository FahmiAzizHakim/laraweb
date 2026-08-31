@extends('layout.admin.main')

@section('title')
<h1>{{ $title ?? 'Dashboard' }}</h1>
@endsection

@section('content')

  {{-- ============ 0. TRANSACTIONS ============ --}}
  <div class="row">
    <div class="col-12">
      <div class="card card-info card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-receipt"></i>
            Recent Transactions
            <span class="badge badge-info ml-1">{{ $transactionStats['count'] ?? 0 }}</span>
          </h3>
          <div class="card-tools">
            <a href="{{ url('/master/transaction') }}" class="btn btn-xs btn-default">View all</a>
          </div>
        </div>
        <div class="card-body p-0">
          @if(!empty($recentTransactions) && $recentTransactions->count())
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>Receipt</th>
                  <th style="width:110px;">Date</th>
                  <th>Customer</th>
                  <th style="width:150px;" class="text-right">Grand Total</th>
                  <th style="width:120px;">Status</th>
                  <th style="width:90px;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($recentTransactions as $trx)
                <tr>
                  <td class="font-weight-bold">{{ $trx->receipt_no ?: '#'.$trx->id }}</td>
                  <td>{{ optional($trx->transaction_date)->format('d M Y') }}</td>
                  <td>{{ $trx->customer_name ?: '—' }}</td>
                  <td class="text-right">{{ number_format($trx->grandtotal, 2) }}</td>
                  <td><span class="badge badge-info">{{ optional($trx->statusCode)->name ?? $trx->status ?? '—' }}</span></td>
                  <td>
                    <a href="{{ url('/master/transaction/'.$trx->id) }}" class="btn btn-xs btn-info">
                      <i class="fas fa-eye"></i> Detail
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-right">Total</th>
                  <th class="text-right">{{ number_format($transactionStats['grandtotal'] ?? 0, 2) }}</th>
                  <th colspan="2"></th>
                </tr>
              </tfoot>
            </table>
          @else
            <div class="p-3 text-muted"><i class="far fa-clock"></i> No transactions yet.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ============ 1. UNREAD MESSAGES ============ --}}
  <div class="row">
    <div class="col-12">
      <div class="card card-warning card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fa fa-envelope-open-text"></i>
            Unread Messages
            <span class="badge badge-warning ml-1">{{ $unread->count() }}</span>
          </h3>
          <div class="card-tools">
            <a href="{{ url('/message') }}" class="btn btn-xs btn-default">View all</a>
          </div>
        </div>
        <div class="card-body p-0">
          @if($unread->count())
            <table class="table table-striped mb-0">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Subject</th>
                  <th>Tanggal</th>
                  <th style="width:90px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($unread as $msg)
                <tr>
                  <td class="font-weight-bold">{{ $msg->name }}</td>
                  <td>{{ $msg->email }}</td>
                  <td>{{ \Illuminate\Support\Str::limit($msg->subject, 50) }}</td>
                  <td>{{ optional($msg->created_at)->format('d M Y H:i') }}</td>
                  <td>
                    <a href="{{ url('/message/'.$msg->id) }}" class="btn btn-xs btn-info">
                      <i class="fas fa-eye"></i> Lihat
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <div class="p-3 text-muted"><i class="far fa-check-circle"></i> No unread messages.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ============ 2. USER INFORMATION ============ --}}
  <div class="row">
    <div class="col-12">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fa fa-user"></i> User Information</h3>
          <div class="card-tools">
            <a href="{{ route('password.change') }}" class="btn btn-xs btn-primary">
              <i class="fas fa-key"></i> Change Password
            </a>
          </div>
        </div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-3">Name</dt>
            <dd class="col-sm-9">{{ $user->name }}</dd>

            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{ $user->email }}</dd>

            <dt class="col-sm-3">Access Group</dt>
            <dd class="col-sm-9">
              {{ optional($user->menuGroup)->name ?: '-' }}
              <code>{{ $user->roles_code }}</code>
            </dd>

            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">
              @if($user->is_active)
                <span class="badge badge-success">Aktif</span>
              @else
                <span class="badge badge-secondary">Nonaktif</span>
              @endif
            </dd>
          </dl>
        </div>
      </div>
    </div>
  </div>

  {{-- ============ 3. WEBSITE INFORMATION ============ --}}
  <div class="row">
    <div class="col-12">
      <div class="card card-success card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fa fa-globe"></i> Website Information</h3>
          <div class="card-tools">
            <a href="{{ url('/website/setting') }}" class="btn btn-xs btn-default">
              <i class="fas fa-cog"></i> Setting
            </a>
          </div>
        </div>
        <div class="card-body">
          @if($website)
          <div class="row">
            <div class="col-md-3 text-center mb-3">
              @if($website->logo)
                <img src="{{ asset($website->logo) }}" alt="logo" style="max-height:80px;max-width:100%;">
              @endif
              @if($website->logo_white)
                <div class="mt-2" style="background:#333;padding:8px;border-radius:4px;">
                  <img src="{{ asset($website->logo_white) }}" alt="logo light" style="max-height:60px;max-width:100%;">
                </div>
              @endif
            </div>
            <div class="col-md-9">
              <dl class="row mb-0">
                <dt class="col-sm-3">Website Name</dt>
                <dd class="col-sm-9">{{ $website->web_name }}</dd>

                <dt class="col-sm-3">Company</dt>
                <dd class="col-sm-9">{{ $website->company_name }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $website->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $website->phone_number }}</dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">{{ $website->address }}</dd>

                <dt class="col-sm-3">Location</dt>
                <dd class="col-sm-9">
                  {{ $website->location }}
                  @if($website->location)
                    <a href="https://maps.google.com/maps?q={{ $website->location }}" target="_blank" class="ml-1"><i class="fas fa-map-marker-alt"></i> Map</a>
                  @endif
                </dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                  @if($website->is_active)
                    <span class="badge badge-success">Active</span>
                  @else
                    <span class="badge badge-secondary">Inactive</span>
                  @endif
                </dd>
              </dl>
            </div>
          </div>
          @else
            <div class="text-muted">No website configured.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

@endsection
