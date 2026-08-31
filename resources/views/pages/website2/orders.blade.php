@extends('layout.website2.inner')

@php
    $brand      = $styles['brand_color'] ?? '#40c057';
    $brandHover = $styles['brand_hover'] ?? '#48c960';
    $gold       = $styles['second_color'] ?? '#C9A227';

    $rp = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp

@section('content')
<section id="orders" style="padding-top:40px; padding-bottom:70px;">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Pesanan Anda</h2>
      <p>Masukkan email dan 4 digit terakhir nomor telepon yang Anda gunakan saat memesan
         untuk melihat daftar pesanan dan struk Anda.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">

        {{-- ===== Lookup form ===== --}}
        <div class="od-card">
          @if ($errors->any())
            <div class="od-alert od-alert-err">
              <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <form action="{{ route($site.'.orders.lookup') }}" method="POST" class="od-form">
            @csrf
            <div class="od-field">
              <label>Email <span class="od-req">*</span></label>
              <input type="email" name="email" class="od-input" placeholder="email@contoh.com"
                     value="{{ old('email', $email) }}" required>
            </div>
            <div class="od-field">
              <label>4 Digit Terakhir No. Telepon <span class="od-req">*</span></label>
              <input type="text" name="phone_last4" class="od-input" placeholder="1234"
                     inputmode="numeric" maxlength="4" pattern="\d{4}"
                     value="{{ old('phone_last4') }}" required>
            </div>
            <button type="submit" class="od-btn"><i class="bi bi-search"></i> Cari Pesanan</button>
          </form>
        </div>

        {{-- ===== Results ===== --}}
        @if($searched)
          @if($orders->count())
            <div class="od-card">
              <h4 class="od-title"><i class="bi bi-list-check"></i> {{ $orders->count() }} Pesanan Ditemukan</h4>
              <div class="table-responsive">
                <table class="od-table">
                  <thead>
                    <tr>
                      <th>No. Struk</th>
                      <th style="width:110px;">Tanggal</th>
                      <th class="text-right" style="width:140px;">Total</th>
                      <th style="width:120px;">Status</th>
                      <th style="width:140px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orders as $o)
                    <tr>
                      <td class="od-strong">{{ $o->receipt_no ?: '#'.$o->id }}</td>
                      <td>{{ optional($o->transaction_date)->format('d M Y') }}</td>
                      <td class="text-right">{{ $rp($o->grandtotal) }}</td>
                      <td><span class="od-status">{{ optional($o->statusCode)->name ?? $o->status }}</span></td>
                      <td class="text-right">
                        @if($o->receipt_token)
                          <a href="{{ route($site.'.receipt', ['token' => $o->receipt_token]) }}" class="od-view">
                            <i class="bi bi-receipt"></i> Lihat Struk
                          </a>
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @else
            <div class="od-card od-empty">
              <i class="bi bi-inbox"></i>
              <p>Tidak ada pesanan yang cocok dengan email dan nomor telepon tersebut.
                 Pastikan data yang Anda masukkan sama dengan saat memesan.</p>
            </div>
          @endif
        @endif

      </div>
    </div>
  </div>
</section>

<style>
  #orders .od-card {
    background:#fff; border:1px solid #eee; border-radius:12px;
    box-shadow:0 2px 16px rgba(0,0,0,.06); padding:24px; margin-bottom:22px;
  }
  #orders .od-title { font-size:16px; font-weight:800; color:{{ $brand }}; margin-bottom:16px; }
  #orders .od-title i { margin-right:6px; }

  #orders .od-alert { border-radius:8px; padding:12px 16px; font-size:14px; margin-bottom:16px; }
  #orders .od-alert-err { background:#fdecea; color:#a83228; border:1px solid #f5c6c0; }
  #orders .od-alert-err ul { padding-left:18px; }

  #orders .od-form { display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; }
  #orders .od-field { flex:1 1 200px; }
  #orders .od-field label { display:block; font-size:13px; color:#555; margin-bottom:5px; }
  #orders .od-req { color:#c0392b; }
  #orders .od-input { width:100%; border:1px solid #ddd; border-radius:8px; padding:11px 13px; font-size:14px; }
  #orders .od-input:focus { outline:none; border-color:{{ $brand }}; }
  #orders .od-btn {
    flex:0 0 auto; border:0; padding:12px 22px; border-radius:8px; color:#fff; font-weight:700;
    background:{{ $brand }}; cursor:pointer; transition:.2s; white-space:nowrap;
  }
  #orders .od-btn:hover { background:{{ $brandHover }}; }

  #orders .od-table { width:100%; border-collapse:collapse; }
  #orders .od-table thead th { font-size:12px; text-transform:uppercase; letter-spacing:.03em; color:#999; border-bottom:2px solid #eee; padding:9px 10px; text-align:left; }
  #orders .od-table tbody td { padding:12px 10px; border-bottom:1px solid #f2f2f2; font-size:14px; color: #999;}
  #orders .od-table tbody tr:last-child td { border-bottom:0; }
  #orders .od-strong { font-weight:700; }
  #orders .text-right { text-align:right; }
  #orders .od-status {
    display:inline-block; background:{{ $gold }}; color:#fff; font-size:11px; font-weight:700;
    padding:2px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em;
  }
  #orders .od-view {
    display:inline-flex; align-items:center; gap:5px; background:{{ $brand }}; color:#fff;
    font-size:13px; font-weight:700; padding:7px 12px; border-radius:7px; text-decoration:none; transition:.2s;
  }
  #orders .od-view:hover { background:{{ $brandHover }}; }

  #orders .od-empty { text-align:center; color:#888; }
  #orders .od-empty i { font-size:40px; color:#ccc; display:block; margin-bottom:10px; }
  #orders .od-empty p { margin:0; font-size:14px; }
</style>
@endsection
