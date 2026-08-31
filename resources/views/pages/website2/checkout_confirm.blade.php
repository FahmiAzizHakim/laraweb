@extends('layout.website2.inner')

@php
    $brand = $styles['brand_color'] ?? '#40c057';
    $brandHover = $styles['brand_hover'] ?? '#48c960';
    $gold = $styles['second_color'] ?? '#C9A227';
@endphp

@section('content')
<section id="confirm" style="padding-top:40px; padding-bottom:70px;">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Konfirmasi Pesanan</h2>
      <p>Periksa kembali pesanan dan alamat pengiriman Anda sebelum melanjutkan.</p>
    </div>

    <div class="row">

      {{-- ================= LEFT: order summary ================= --}}
      <div class="col-lg-8">
        <div class="cf-card">
          <h4 class="cf-title"><i class="bi bi-receipt"></i> Rincian Pesanan</h4>

          <div class="table-responsive">
            <table class="cf-table">
              <thead>
                <tr>
                  <th>Item</th>
                  <th class="text-center" style="width:70px;">Qty</th>
                  <th class="text-right" style="width:130px;">Harga</th>
                  <th class="text-right" style="width:140px;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($items as $it)
                  <tr>
                    <td>
                      {{ $it['name'] }}
                      @if(($it['kind'] ?? '') === 'package')
                        <span class="cf-kind">paket</span>
                        {{-- What the package covers; all of it is included in its price. --}}
                        @if(!empty($it['included']))
                          <ul class="cf-included">
                            @foreach($it['included'] as $line)
                              <li><i class="bi bi-check2"></i> {{ $line }}</li>
                            @endforeach
                          </ul>
                        @endif
                      @endif
                    </td>
                    <td class="text-center">{{ $it['qty'] }}</td>
                    <td class="text-right">{{ $it['price'] ? 'Rp '.number_format($it['price'], 0, ',', '.') : 'Hubungi' }}</td>
                    <td class="text-right">
                      {{ $it['price'] ? 'Rp '.number_format($it['price'] * $it['qty'], 0, ',', '.') : '—' }}
                      @if(!empty($it['discount']))
                        <div class="cf-line-disc">- Rp {{ number_format($it['discount'], 0, ',', '.') }}</div>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="cf-totals">
            <div class="cf-sumline"><span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
            @if($discount > 0)
              <div class="cf-sumline cf-sumline-disc"><span>Diskon paket</span><span>- Rp {{ number_format($discount, 0, ',', '.') }}</span></div>
            @endif
            <div class="cf-sumline">
              <span>Ongkir</span>
              <span>
                @if(is_null($fare))
                  <em class="cf-unavailable">Belum tersedia</em>
                @else
                  Rp {{ number_format($fare, 0, ',', '.') }}
                @endif
              </span>
            </div>
            <div class="cf-total"><span>Total</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
            @if(is_null($fare))
              <p class="cf-note"><i class="bi bi-exclamation-triangle"></i> Ongkir untuk wilayah ini belum diatur dan belum termasuk dalam total.</p>
            @endif
          </div>
        </div>
      </div>

      {{-- ================= RIGHT: address + actions ================= --}}
      <div class="col-lg-4">
        <div class="cf-card cf-address">
          <h4 class="cf-title"><i class="bi bi-geo-alt"></i> Alamat Pengiriman</h4>

          <p class="cf-recipient">{{ $address['recipient_name'] }}</p>
          <p class="cf-phone"><i class="bi bi-telephone"></i> {{ $address['phone'] }}</p>
          <p class="cf-phone"><i class="bi bi-envelope"></i> {{ $address['email'] }}</p>

          <p class="cf-addr-detail">{{ $address['detail'] }}</p>
          <ul class="cf-region">
            @if($address['subdistrict'])<li>{{ $address['subdistrict'] }}</li>@endif
            <li>{{ $address['district'] }}</li>
            <li>{{ $address['city'] }}</li>
            @if($address['province'])<li>{{ $address['province'] }}</li>@endif
          </ul>

          <hr>

          <form action="{{ route($site.'.checkout.place') }}" method="POST">
            @csrf
            {{-- Re-submit the validated order verbatim; place() rebuilds &
                 re-prices it server-side. --}}
            <input type="hidden" name="cart_json" value="{{ $raw['cart_json'] }}">
            <input type="hidden" name="province_code" value="{{ $raw['province_code'] ?? '' }}">
            <input type="hidden" name="city_code" value="{{ $raw['city_code'] }}">
            <input type="hidden" name="district_code" value="{{ $raw['district_code'] }}">
            <input type="hidden" name="subdistrict_code" value="{{ $raw['subdistrict_code'] ?? '' }}">
            <input type="hidden" name="address_detail" value="{{ $raw['address_detail'] }}">
            <input type="hidden" name="recipient_name" value="{{ $raw['recipient_name'] }}">
            <input type="hidden" name="phone" value="{{ $raw['phone'] }}">
            <input type="hidden" name="email" value="{{ $raw['email'] }}">
            <button type="submit" class="cf-confirm">
              <i class="bi bi-check2-circle"></i> Buat Pesanan
            </button>
          </form>
          <a href="{{ route($site.'.checkout') }}" class="cf-back">
            <i class="bi bi-arrow-left"></i> Ubah Pesanan
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<style>
  #confirm .cf-card {
    background:#fff; border:1px solid #eee; border-radius:10px;
    box-shadow:0 2px 14px rgba(0,0,0,.06); padding:22px; margin-bottom:24px;
  }
  #confirm .cf-title { font-size:17px; font-weight:700; color:{{ $brand }}; margin-bottom:16px; }
  #confirm .cf-title i { margin-right:6px; }

  #confirm .cf-table { width:100%; border-collapse:collapse; }
  #confirm .cf-table thead th { font-size:12px; text-transform:uppercase; letter-spacing:.03em; color:#999; border-bottom:2px solid #eee; padding:8px 10px; }
  #confirm .cf-table tbody td { padding:12px 10px; border-bottom:1px solid #f0f0f0; font-size:14px; }
  #confirm .cf-table tbody tr:last-child td { border-bottom:0; }
  #confirm .text-right { text-align:right; }
  #confirm .text-center { text-align:center; }

  #confirm .cf-totals { margin-top:18px; border-top:1px dashed #e3e3e3; padding-top:14px; }
  #confirm .cf-sumline { display:flex; justify-content:space-between; font-size:14px; color:#555; padding:4px 0; }
  #confirm .cf-total { display:flex; justify-content:space-between; font-weight:700; font-size:19px; margin-top:8px; padding-top:8px; border-top:1px solid #eee; }
  #confirm .cf-total span:last-child { color:{{ $gold }}; }
  #confirm .cf-sumline-disc { color:#e03131; }
  #confirm .cf-unavailable { color:#c0392b; font-style:normal; }

  /* package lines */
  #confirm .cf-kind {
    display:inline-block; margin-left:6px; padding:1px 7px; border-radius:10px;
    background:rgba(64,192,87,.12); color:{{ $brand }}; font-size:10px; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; vertical-align:middle;
  }
  #confirm .cf-included { list-style:none; margin:6px 0 0; padding:0; }
  #confirm .cf-included li { font-size:12px; color:#888; padding:1px 0; }
  #confirm .cf-included li i { color:{{ $brand }}; margin-right:4px; }
  #confirm .cf-line-disc { font-size:12px; color:#e03131; font-weight:600; }
  #confirm .cf-note { font-size:12px; color:#c0392b; margin:10px 0 0; }

  #confirm .cf-address { position:sticky; top:130px; }
  #confirm .cf-recipient { font-weight:700; font-size:15px; margin:0 0 2px; }
  #confirm .cf-phone { color:#666; font-size:13px; margin:0 0 12px; }
  #confirm .cf-addr-detail { font-size:14px; color:#333; margin:0 0 8px; }
  #confirm .cf-region { list-style:none; padding:0; margin:0; font-size:13px; color:#777; }
  #confirm .cf-region li { padding:1px 0; }

  #confirm .cf-confirm {
    width:100%; border:0; padding:12px; border-radius:8px; color:#fff; font-weight:700;
    background:{{ $brand }}; cursor:pointer; transition:.2s;
  }
  #confirm .cf-confirm:hover { background:{{ $brandHover }}; }
  #confirm .cf-back { display:block; text-align:center; margin-top:12px; font-size:13px; color:#888; text-decoration:none; }
  #confirm .cf-back:hover { color:{{ $brand }}; }
</style>
@endsection
