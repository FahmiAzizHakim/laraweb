@extends('layout.website2.inner')

@php
    $brand = $styles['brand_color'] ?? '#40c057';
    $brandHover = $styles['brand_hover'] ?? '#48c960';
    $gold = $styles['second_color'] ?? '#C9A227';
@endphp

@section('content')
<section id="checkout" style="padding-top:40px; padding-bottom:70px;">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Checkout</h2>
    </div>

    @if(session('failed'))
      <div class="alert alert-danger">{{ session('failed') }}</div>
    @endif

    <div class="row">

      {{-- ================= LEFT: catalog ================= --}}
      <div class="col-lg-8">

        <h4 class="co-heading">Pilih Paket atau Layanan</h4>

        @php
          $packages = $packages ?? collect();

          // Landing page "Pilih Paket" links here as ?package=CODE: that opens the
          // Paket tab and drops the chosen package straight into the cart.
          $pickedPackage = request()->filled('package')
              ? $packages->firstWhere('package_code', request('package'))
              : null;

          $packageTabActive = $packages->count() && ($pickedPackage || request('tab') === 'package');

          $activeId = request('service', $services->count() ? $services->first()->id : null);

          // Handed to the cart script so the picked package starts in the cart.
          $preselectPackage = $pickedPackage ? [
              'id'       => 'pkg' . $pickedPackage->id,
              'name'     => $pickedPackage->package_name,
              'price'    => (int) $pickedPackage->package_price,
              'discount' => (int) $pickedPackage->package_discount,
          ] : null;

          // Two across inside this column (the landing page has the full width).
          $pkgCols = $packages->count() === 1 ? 'col-lg-12' : 'col-lg-6';
        @endphp

        @if($services->count() || $packages->count())

          {{-- Package + service tabs --}}
          <ul class="co-tabs">
            @if($packages->count())
              <li>
                <button type="button" class="co-tab {{ $packageTabActive ? 'active' : '' }}"
                        data-tab="pkg-panel">
                  Paket
                </button>
              </li>
            @endif
            @foreach($services as $s)
              <li>
                <button type="button" class="co-tab {{ !$packageTabActive && (string) $s->id === (string) $activeId ? 'active' : '' }}"
                        data-tab="svc-{{ $s->id }}">
                  {{ $s->service_name }}
                </button>
              </li>
            @endforeach
          </ul>

          {{-- ---------- Paket panel: same cards as the landing page ---------- --}}
          @if($packages->count())
            <div class="co-panel {{ $packageTabActive ? '' : 'd-none' }}" id="pkg-panel">

              <div class="co-panel-head">
                <div>
                  <h5 class="mb-0">Paket &amp; Harga</h5>
                  <small class="text-muted">Paket instalasi lengkap — sudah termasuk teknisi bersertifikat dan garansi pengerjaan.</small>
                </div>
              </div>

              {{-- id="packages" so the landing page's .pkg-* styles apply verbatim;
                   its section padding/background is neutralised in the CSS below. --}}
              <div id="packages" class="co-packages mt-3">
                <div class="row pkg-row">
                  @foreach($packages as $pkg)
                    <div class="{{ $pkgCols }} col-md-6">
                      <div class="pkg-card">
                        <div class="pkg-head">
                          <h4 class="pkg-name">{{ $pkg->package_name }}</h4>
                          @if($pkg->package_description)
                            <p class="pkg-tagline">{{ $pkg->package_description }}</p>
                          @endif
                        </div>

                        <div class="pkg-price-wrap">
                          @if($pkg->package_discount > 0)
                            <div class="pkg-old">
                              <span class="pkg-old-price">Rp {{ number_format($pkg->package_price, 0, ',', '.') }}</span>
                              <span class="pkg-discount">- Rp {{ number_format($pkg->package_discount, 0, ',', '.') }}</span>
                            </div>
                          @endif
                          <div class="pkg-price">
                            <span class="pkg-currency">Rp</span>
                            <span class="pkg-amount">{{ number_format($pkg->net_price, 0, ',', '.') }}</span>
                          </div>
                        </div>

                        <button type="button" class="pkg-btn co-pkg-add"
                                data-id="pkg{{ $pkg->id }}"
                                data-name="{{ $pkg->package_name }}"
                                data-price="{{ (int) $pkg->package_price }}"
                                data-discount="{{ (int) $pkg->package_discount }}">
                          <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                        </button>

                        @if($pkg->details->count())
                          <ul class="pkg-features">
                            @foreach($pkg->details as $detail)
                              <li>
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $detail->qty > 1 ? $detail->qty.'x ' : '' }}{{ $detail->line_label }}
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="pkg-guarantee">
                  <span><i class="bi bi-arrow-counterclockwise"></i> Garansi uang kembali 30 hari</span>
                  <span><i class="bi bi-shield-check"></i> Teknisi bersertifikat &amp; terverifikasi</span>
                  <span><i class="bi bi-headset"></i> Dukungan pelanggan 24/7</span>
                </div>
              </div>

            </div>
          @endif

          {{-- One panel per service: its products change with the active tab --}}
          @foreach($services as $s)
            @php $svcProducts = $products->where('service_id', $s->id)->values(); @endphp
            <div class="co-panel {{ !$packageTabActive && (string) $s->id === (string) $activeId ? '' : 'd-none' }}" id="svc-{{ $s->id }}">

              <div class="co-panel-head">
                <div>
                  <h5 class="mb-0">{{ $s->service_name }}</h5>
                  <small class="text-muted">{{ $s->service_title ?: $s->service_subtitle }}</small>
                </div>
                <!-- <button type="button" class="co-add"
                        data-id="s{{ $s->id }}" data-name="{{ $s->service_name }}" data-price="0">
                  <i class="bi bi-plus"></i> Pesan Layanan
                </button> -->
              </div>

              <div class="co-products mt-3">
                @forelse($svcProducts as $p)
                  @php $hasVar = $p->variants->count() > 0; @endphp
                  <div class="co-product">

                    <div class="co-product-head">
                      <div class="co-product-info">
                        <h5>{{ $p->products_name }}</h5>
                        @if($p->products_description)<p>{{ $p->products_description }}</p>@endif
                        @if($hasVar)<small class="co-hint">Pilih varian di bawah</small>@endif
                      </div>

                      {{-- No variants: add the product directly --}}
                      @unless($hasVar)
                        <div class="co-buy">
                          <span class="co-price">Rp {{ number_format($p->products_price, 0, ',', '.') }}</span>
                          <button type="button" class="co-add"
                                  data-id="p{{ $p->id }}" data-name="{{ $p->products_name }}" data-price="{{ (int) $p->products_price }}">
                            <i class="bi bi-plus"></i> Tambah
                          </button>
                        </div>
                      @endunless
                    </div>

                    {{-- Has variants: one add button per variant --}}
                    @if($hasVar)
                      <div class="co-variants">
                        @foreach($p->variants as $v)
                          @php $price = !is_null($v->variant_price) ? $v->variant_price : $p->products_price; @endphp
                          <div class="co-variant">
                            <div class="co-variant-info">
                              <span class="co-variant-name">{{ $v->variant_name }}</span>
                              @if($v->variant_description)<small>{{ $v->variant_description }}</small>@endif
                            </div>
                            <div class="co-buy">
                              <span class="co-price">Rp {{ number_format($price, 0, ',', '.') }}</span>
                              <button type="button" class="co-add"
                                      data-id="p{{ $p->id }}v{{ $v->id }}"
                                      data-name="{{ $p->products_name }} — {{ $v->variant_name }}"
                                      data-price="{{ (int) $price }}">
                                <i class="bi bi-plus"></i> Tambah
                              </button>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @endif

                  </div>
                @empty
                  <p class="text-muted">Belum ada produk untuk layanan ini.</p>
                @endforelse
              </div>

            </div>
          @endforeach
        @else
          <p class="text-muted">Belum ada paket atau layanan.</p>
        @endif

        {{-- ================= Address form ================= --}}
        <h4 class="co-heading mt-5">Alamat Pengiriman</h4>
        <div class="co-address">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Provinsi <span class="req">*</span></label>
              <select id="addr-province" class="form-control co-field">
                <option value="">-- Pilih provinsi --</option>
                @foreach($provinces as $p)
                  <option value="{{ $p->province_code }}">{{ $p->province_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Kota / Kabupaten <span class="req">*</span></label>
              <select id="addr-city" class="form-control co-field">
                <option value="">-- Pilih provinsi dulu --</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Kecamatan <span class="req">*</span></label>
              <select id="addr-district" class="form-control co-field">
                <option value="">-- Pilih kota dulu --</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Kelurahan / Desa <small class="text-muted">(opsional)</small></label>
              <select id="addr-subdistrict" class="form-control">
                <option value="">-- Seluruh kecamatan --</option>
              </select>
            </div>
            <div class="col-12 mb-3">
              <label>Alamat Lengkap <span class="req">*</span></label>
              <textarea id="addr-detail" class="form-control co-field" rows="2"
                        placeholder="Nama jalan, nomor rumah, RT/RW, patokan"></textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Nama Penerima <span class="req">*</span></label>
              <input type="text" id="addr-name" class="form-control co-field" placeholder="Nama lengkap penerima">
            </div>
            <div class="col-md-6 mb-3">
              <label>No. Telepon <span class="req">*</span></label>
              <input type="tel" id="addr-phone" class="form-control co-field" placeholder="08xxxxxxxxxx">
            </div>
            <div class="col-12 mb-3">
              <label>Email <span class="req">*</span></label>
              <input type="email" id="addr-email" class="form-control co-field" placeholder="nama@email.com">
            </div>
          </div>

          {{-- Ongkir shown here too, so the charge is visible without scrolling up.
               Hidden until a district is chosen. --}}
          <div class="co-address-fare d-none" id="addr-fare-box">
            <div>
              <span class="co-address-fare-label"><i class="bi bi-truck"></i> Ongkir wilayah ini</span>
              <small id="addr-fare-note" class="co-address-fare-note d-none"></small>
            </div>
            <span class="co-address-fare-value" id="addr-fare">—</span>
          </div>
        </div>

      </div>

      {{-- ================= RIGHT: sticky cart ================= --}}
      <div class="col-lg-4">
        <div class="co-cart">
          <h4><i class="bi bi-cart3"></i> Keranjang</h4>
          <div id="cart-items">
            <p class="text-muted mb-0">Keranjang masih kosong.</p>
          </div>
          <hr>
          <div class="co-sumline"><span>Subtotal</span><span id="cart-subtotal">Rp 0</span></div>
          <div class="co-sumline co-sumline-disc d-none" id="cart-discount-line"><span>Diskon paket</span><span id="cart-discount">—</span></div>
          <div class="co-sumline d-none" id="cart-shipping-line"><span>Ongkir</span><span id="cart-shipping">—</span></div>
          <p id="fare-note" class="co-fare-note d-none"></p>
          <div class="co-total">
            <span>Total</span>
            <span id="cart-total">Rp 0</span>
          </div>
          <button type="button" class="co-checkout" id="cart-checkout" disabled>
            <i class="bi bi-bag-check"></i> Checkout
          </button>
          <p id="checkout-hint" class="co-hint-text">Lengkapi alamat & keranjang untuk checkout.</p>
        </div>
      </div>

    </div>
  </div>

  {{-- Hidden form: carries the cart + address to the confirmation page --}}
  <form id="order-form" action="{{ route($site.'.checkout.confirm') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="cart_json"        id="f-cart">
    <input type="hidden" name="province_code"    id="f-province">
    <input type="hidden" name="city_code"        id="f-city">
    <input type="hidden" name="district_code"    id="f-district">
    <input type="hidden" name="subdistrict_code" id="f-subdistrict">
    <input type="hidden" name="address_detail"   id="f-detail">
    <input type="hidden" name="recipient_name"   id="f-name">
    <input type="hidden" name="phone"            id="f-phone">
    <input type="hidden" name="email"            id="f-email">
  </form>
</section>

<style>
  #checkout .co-heading { font-weight: 700; color: {{ $brand }}; margin-bottom: 14px; }

  #checkout .co-tabs { list-style: none; padding: 0; margin: 0 0 18px; display: flex; flex-wrap: wrap; gap: 8px; }
  #checkout .co-tab {
    border: 1px solid #e2e2e2; background: #f4f4f4; color: #333;
    padding: 8px 16px; border-radius: 24px; cursor: pointer; font-weight: 600; font-size: 13px; transition: .2s;
  }
  #checkout .co-tab:hover { border-color: {{ $brand }}; }
  #checkout .co-tab.active { background: {{ $brand }}; border-color: {{ $brand }}; color: #fff; }
  #checkout .co-panel-head {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    background: #fafafa; border: 1px solid #eee; border-radius: 8px; padding: 12px 16px;
  }
  #checkout .co-panel-head h5 { font-size: 16px; font-weight: 700; }

  /* per-line products + variants */
  #checkout .co-products { display: flex; flex-direction: column; gap: 12px; }
  #checkout .co-product { border: 1px solid #eee; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.05); overflow: hidden; background: #fff; }
  #checkout .co-product-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 16px; }
  #checkout .co-product-info h5 { font-size: 15px; font-weight: 700; margin: 0 0 2px; }
  #checkout .co-product-info p { font-size: 12px; color: #888; margin: 0; }
  #checkout .co-hint { color: {{ $brand }}; font-size: 11px; font-weight: 600; }
  #checkout .co-buy { display: flex; align-items: center; gap: 12px; white-space: nowrap; }
  #checkout .co-variants { border-top: 1px dashed #e3e3e3; background: #fafafa; }
  #checkout .co-variant { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 16px 10px 28px; border-bottom: 1px dashed #e9e9e9; }
  #checkout .co-variant:last-child { border-bottom: 0; }
  #checkout .co-variant-name { font-weight: 600; font-size: 14px; }
  #checkout .co-variant-info small { display: block; color: #999; font-size: 11px; }

  #checkout .co-item {
    display: flex;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    overflow: hidden;
    height: 100%;
  }
  #checkout .co-thumb { width: 90px; height: 90px; object-fit: cover; background: #f4f4f4; flex-shrink: 0; }
  #checkout .co-info { padding: 10px 12px; display: flex; flex-direction: column; width: 100%; }
  #checkout .co-info h5 { font-size: 15px; font-weight: 600; margin: 0 0 3px; }
  #checkout .co-info p { font-size: 12px; color: #888; margin: 0 0 8px; }
  #checkout .co-bottom { margin-top: auto; display: flex; align-items: center; justify-content: space-between; }
  #checkout .co-price { font-weight: 700; color: {{ $gold }}; font-size: 14px; }
  #checkout .co-add {
    border: 0; background: {{ $brand }}; color: #fff; font-size: 12px; font-weight: 600;
    padding: 6px 12px; border-radius: 20px; cursor: pointer; transition: .2s;
  }
  #checkout .co-add:hover { background: {{ $brandHover }}; }

  #checkout .co-cart {
    position: sticky; top: 130px;
    background: #fff; border: 1px solid #eee; border-radius: 10px;
    box-shadow: 0 2px 14px rgba(0,0,0,.08); padding: 20px;
  }
  #checkout .co-cart > h4 { font-weight: 700; margin-bottom: 15px; }
  #checkout .co-line { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px dashed #eee; }
  #checkout .co-line-name { display: block; font-size: 14px; font-weight: 600; }
  #checkout .co-line small { color: #888; }
  #checkout .co-qty { display: flex; align-items: center; gap: 6px; }
  #checkout .co-qty button { width: 24px; height: 24px; border: 1px solid #ddd; background: #f7f7f7; border-radius: 4px; cursor: pointer; line-height: 1; }
  #checkout .co-remove { color: #c0392b; border-color: #f0d0cc !important; }
  #checkout .co-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 17px; margin: 4px 0 14px; }
  #checkout .co-checkout {
    width: 100%; border: 0; padding: 12px; border-radius: 8px; color: #fff; font-weight: 700;
    background: {{ $brand }}; cursor: pointer; transition: .2s;
  }
  #checkout .co-checkout:hover:not(:disabled) { background: {{ $brandHover }}; }
  #checkout .co-checkout:disabled { opacity: .5; cursor: not-allowed; }

  /* address form */
  #checkout .co-address { background: #fff; border: 1px solid #eee; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.05); padding: 20px 20px 6px; }
  #checkout .co-address label { font-size: 13px; font-weight: 600; margin-bottom: 4px; }
  #checkout .co-address .req { color: #c0392b; }
  #checkout .co-address .form-control { font-size: 14px; }
  #checkout .co-address .form-control:focus { border-color: {{ $brand }}; box-shadow: 0 0 0 .15rem rgba(64,192,87,.20); }

  /* ongkir bar under the address form */
  #checkout .co-address-fare {
    display: flex; justify-content: space-between; align-items: center; gap: 12px;
    margin: 6px -20px -6px; padding: 14px 20px;
    background: rgba(64,192,87,.08); border-top: 1px solid #eee;
    border-radius: 0 0 10px 10px;
  }
  #checkout .co-address-fare-label { font-size: 14px; font-weight: 600; color: #444; }
  #checkout .co-address-fare-note { display: block; font-size: 12px; color: #c0392b; }
  #checkout .co-address-fare-value { font-size: 18px; font-weight: 700; color: {{ $gold }}; white-space: nowrap; }

  /* Paket panel: the landing page's .pkg-* cards, minus the section chrome. */
  #checkout #packages { padding: 0; background: transparent; }
  #checkout #packages .pkg-row { margin-bottom: -30px; }
  #checkout #packages .pkg-amount { font-size: 26px; }
  #checkout #packages .pkg-btn { cursor: pointer; font-family: inherit; }
  #checkout #packages .pkg-guarantee { margin-top: 30px; font-size: 13px; gap: 10px 24px; }

  /* cart summary lines */
  #checkout .co-sumline { display: flex; justify-content: space-between; font-size: 14px; color: #555; padding: 3px 0; }
  #checkout .co-sumline-disc { color: #e03131; }
  #checkout .co-line-disc { display: block; color: #e03131; font-weight: 600; }
  #checkout .co-fare-note { font-size: 12px; color: #c0392b; margin: 4px 0 0; }
  #checkout .co-hint-text { font-size: 12px; color: #999; text-align: center; margin: 10px 0 0; }
</style>

<script>
  (function () {
    var cart = {};                 // id -> { name, price, qty, disc } (price gross, disc per unit)
    var MAX_QTY = {{ \App\Services\Masterdata\CartService::MAX_QTY }};   // matches the server-side cap

    // Package carried over from the landing page's "Pilih Paket" link, or null.
    var PRESELECT = @json($preselectPackage);

    var fare = null;               // resolved ongkir (number) or null
    var fareStatus = 'none';       // 'none' | 'ok' | 'unavailable'

    // The cart saved for this browser, already re-priced server-side.
    var SAVED_CART = @json($cartLines ?? []);

    var CART_URL = "{{ route($site.'.cart.save') }}";
    var CSRF     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var CITY_URL = "{{ url('/'.$site.'/regions/cities') }}";
    var DIST_URL = "{{ url('/'.$site.'/regions/districts') }}";
    var SUB_URL  = "{{ url('/'.$site.'/regions/subdistricts') }}";
    var FARE_URL = "{{ url('/'.$site.'/fare') }}";

    var $ = function (id) { return document.getElementById(id); };
    function fmt(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

    /* ---------------- saved cart ---------------- */

    var saveTimer = null;

    // Written back to the server after every change, coalesced so holding the
    // + button is one request. Only keys and quantities are sent -- the server
    // re-prices from the catalogue and ignores anything it does not know.
    function persist() {
      clearTimeout(saveTimer);
      saveTimer = setTimeout(function () {
        var items = Object.keys(cart).map(function (k) {
          return { id: k, qty: cart[k].qty };
        });

        fetch(CART_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
          },
          credentials: 'same-origin',
          keepalive: true,          // survives navigating away right after a click
          body: JSON.stringify({ items: items })
        }).catch(function () { /* the cart still works locally */ });
      }, 400);
    }

    /* ---------------- cart ---------------- */

    function subtotal() {
      return Object.keys(cart).reduce(function (sum, k) {
        return sum + cart[k].price * cart[k].qty;
      }, 0);
    }

    // Package discounts. Kept apart from the subtotal so the cart, the
    // confirmation page and the receipt all break the price down the same way.
    function discountTotal() {
      return Object.keys(cart).reduce(function (sum, k) {
        return sum + (cart[k].disc || 0) * cart[k].qty;
      }, 0);
    }

    function renderCart() {
      var wrap = $('cart-items');
      var keys = Object.keys(cart);

      if (!keys.length) {
        wrap.innerHTML = '<p class="text-muted mb-0">Keranjang masih kosong.</p>';
      } else {
        var html = '';
        keys.forEach(function (k) {
          var it = cart[k];
          html += '<div class="co-line">'
                +   '<div class="co-line-info"><span class="co-line-name">' + it.name + '</span>'
                +     '<small>' + (it.price ? fmt(it.price) : 'Hubungi') + '</small>'
                +     (it.disc ? '<small class="co-line-disc">- ' + fmt(it.disc) + '</small>' : '')
                +   '</div>'
                +   '<div class="co-qty">'
                +     '<button type="button" class="co-dec" data-id="' + k + '">-</button>'
                +     '<span>' + it.qty + '</span>'
                +     '<button type="button" class="co-inc" data-id="' + k + '">+</button>'
                +     '<button type="button" class="co-remove" data-id="' + k + '">&times;</button>'
                +   '</div>'
                + '</div>';
        });
        wrap.innerHTML = html;
      }
      renderTotals();
    }

    function renderTotals() {
      var sub  = subtotal();
      var disc = discountTotal();
      $('cart-subtotal').textContent = fmt(sub);

      if (disc > 0) {
        $('cart-discount').textContent = '- ' + fmt(disc);
        $('cart-discount-line').classList.remove('d-none');
      } else {
        $('cart-discount-line').classList.add('d-none');
      }

      var shipEl   = $('cart-shipping');
      var noteEl   = $('fare-note');
      var addrEl   = $('addr-fare');
      var addrNote = $('addr-fare-note');
      var addrBox  = $('addr-fare-box');
      var shipLine = $('cart-shipping-line');
      noteEl.classList.add('d-none');
      addrNote.classList.add('d-none');

      if (fareStatus === 'none') {
        // No district chosen yet -> don't show ongkir at all.
        addrBox.classList.add('d-none');
        shipLine.classList.add('d-none');
      } else {
        addrBox.classList.remove('d-none');
        shipLine.classList.remove('d-none');
        if (fareStatus === 'ok') {
          shipEl.textContent = fmt(fare);
          addrEl.textContent = fmt(fare);
        } else { // unavailable
          shipEl.textContent = 'Belum tersedia';
          addrEl.textContent = 'Belum tersedia';
          noteEl.textContent = 'Ongkir untuk wilayah ini belum diatur.';
          noteEl.classList.remove('d-none');
          addrNote.textContent = 'Ongkir untuk wilayah ini belum diatur.';
          addrNote.classList.remove('d-none');
        }
      }

      var ship = (fareStatus === 'ok') ? fare : 0;
      $('cart-total').textContent = fmt(sub - disc + ship);

      updateGating();
    }

    /* ---------------- address cascade ---------------- */

    function resetSelect(sel, placeholder) {
      sel.innerHTML = '<option value="">' + placeholder + '</option>';
    }

    function fillOptions(sel, rows, valKey, textKey, decorate) {
      rows.forEach(function (r) {
        var opt = document.createElement('option');
        opt.value = r[valKey];
        opt.textContent = decorate ? decorate(r) : r[textKey];
        sel.appendChild(opt);
      });
    }

    var provSel = $('addr-province'), citySel = $('addr-city'),
        distSel = $('addr-district'), subSel = $('addr-subdistrict');

    provSel.addEventListener('change', function () {
      resetSelect(citySel, '-- Memuat... --');
      resetSelect(distSel, '-- Pilih kota dulu --');
      resetSelect(subSel, '-- Seluruh kecamatan --');
      clearFare();
      if (!provSel.value) { resetSelect(citySel, '-- Pilih provinsi dulu --'); return; }
      fetch(CITY_URL + '/' + encodeURIComponent(provSel.value))
        .then(function (r) { return r.json(); })
        .then(function (rows) {
          resetSelect(citySel, '-- Pilih kota --');
          fillOptions(citySel, rows, 'city_code', 'city_name', function (r) {
            return r.city_name + (r.city_type ? ' (' + r.city_type + ')' : '');
          });
        });
    });

    citySel.addEventListener('change', function () {
      resetSelect(distSel, '-- Memuat... --');
      resetSelect(subSel, '-- Seluruh kecamatan --');
      clearFare();
      if (!citySel.value) { resetSelect(distSel, '-- Pilih kota dulu --'); return; }
      fetch(DIST_URL + '/' + encodeURIComponent(citySel.value))
        .then(function (r) { return r.json(); })
        .then(function (rows) {
          resetSelect(distSel, '-- Pilih kecamatan --');
          fillOptions(distSel, rows, 'district_code', 'district_name');
        });
      // Ongkir stays hidden until a district is chosen.
    });

    distSel.addEventListener('change', function () {
      resetSelect(subSel, '-- Memuat... --');
      if (!distSel.value) { resetSelect(subSel, '-- Seluruh kecamatan --'); fetchFare(); return; }
      fetch(SUB_URL + '/' + encodeURIComponent(distSel.value))
        .then(function (r) { return r.json(); })
        .then(function (rows) {
          resetSelect(subSel, '-- Seluruh kecamatan --');
          fillOptions(subSel, rows, 'subdistrict_code', 'subdistrict_name');
        });
      fetchFare();
    });

    subSel.addEventListener('change', fetchFare);

    /* ---------------- fare ---------------- */

    function clearFare() {
      fare = null; fareStatus = 'none';
      renderTotals();
    }

    function fetchFare() {
      // Ongkir is only shown once a district is chosen.
      if (!citySel.value || !distSel.value) { clearFare(); return; }
      var params = 'city_code=' + encodeURIComponent(citySel.value)
                 + '&district_code=' + encodeURIComponent(distSel.value || '')
                 + '&subdistrict_code=' + encodeURIComponent(subSel.value || '');
      fetch(FARE_URL + '?' + params)
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (res.found) { fare = res.fare; fareStatus = 'ok'; }
          else { fare = null; fareStatus = 'unavailable'; }
          renderTotals();
        })
        .catch(function () { clearFare(); });
    }

    /* ---------------- gating ---------------- */

    function requiredFilled() {
      var ids = ['addr-province', 'addr-city', 'addr-district', 'addr-detail', 'addr-name', 'addr-phone', 'addr-email'];
      return ids.every(function (id) { return $(id).value.trim() !== ''; });
    }

    function updateGating() {
      var hasItems = Object.keys(cart).length > 0;
      var ok = hasItems && requiredFilled();
      $('cart-checkout').disabled = !ok;

      var hint = $('checkout-hint');
      if (ok) {
        hint.classList.add('d-none');
      } else {
        hint.classList.remove('d-none');
        hint.textContent = !hasItems
          ? 'Keranjang masih kosong.'
          : 'Lengkapi alamat pengiriman untuk checkout.';
      }
    }

    // Re-check gating on any required field change.
    document.querySelectorAll('.co-field').forEach(function (el) {
      el.addEventListener('input', updateGating);
      el.addEventListener('change', updateGating);
    });

    /* ---------------- product add / qty ---------------- */

    document.querySelectorAll('.co-add, .co-pkg-add').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = btn.dataset.id;
        if (cart[id]) { cart[id].qty = Math.min(MAX_QTY, cart[id].qty + 1); }
        else {
          cart[id] = {
            name:  btn.dataset.name,
            price: parseInt(btn.dataset.price || '0', 10),
            disc:  parseInt(btn.dataset.discount || '0', 10),
            qty:   1
          };
        }
        renderCart();
        persist();
      });
    });

    $('cart-items').addEventListener('click', function (e) {
      var id = e.target.dataset.id;
      if (!id || !cart[id]) return;
      if (e.target.classList.contains('co-inc')) cart[id].qty = Math.min(MAX_QTY, cart[id].qty + 1);
      else if (e.target.classList.contains('co-dec')) { cart[id].qty--; if (cart[id].qty <= 0) delete cart[id]; }
      else if (e.target.classList.contains('co-remove')) delete cart[id];
      else return;
      renderCart();
      persist();
    });

    $('cart-checkout').addEventListener('click', function () {
      if (this.disabled) return;

      var items = Object.keys(cart).map(function (k) { return { id: k, qty: cart[k].qty }; });
      $('f-cart').value        = JSON.stringify(items);
      $('f-province').value    = provSel.value;
      $('f-city').value        = citySel.value;
      $('f-district').value    = distSel.value;
      $('f-subdistrict').value = subSel.value;
      $('f-detail').value      = $('addr-detail').value;
      $('f-name').value        = $('addr-name').value;
      $('f-phone').value       = $('addr-phone').value;
      $('f-email').value       = $('addr-email').value;
      $('order-form').submit();
    });

    // Service tabs -> show that service's product panel
    document.querySelectorAll('.co-tab').forEach(function (tab) {
      tab.addEventListener('click', function () {
        document.querySelectorAll('.co-tab').forEach(function (t) { t.classList.remove('active'); });
        tab.classList.add('active');
        document.querySelectorAll('.co-panel').forEach(function (p) { p.classList.add('d-none'); });
        var panel = $(tab.dataset.tab);
        if (panel) panel.classList.remove('d-none');
      });
    });

    // Start from whatever this browser had saved...
    SAVED_CART.forEach(function (it) {
      cart[it.id] = { name: it.name, price: it.price, disc: it.disc, qty: it.qty };
    });

    // ...then make sure the package chosen on the landing page is in there.
    // Already present: leave the quantity alone, so coming back through the
    // same link does not keep stacking it up.
    if (PRESELECT && !cart[PRESELECT.id]) {
      cart[PRESELECT.id] = {
        name: PRESELECT.name, price: PRESELECT.price, disc: PRESELECT.discount, qty: 1
      };
      persist();
    }

    renderCart();
  })();
</script>
@endsection
