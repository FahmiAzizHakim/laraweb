@extends('layout.website2.inner')

@php
    $brand      = $styles['brand_color'] ?? '#40c057';
    $brandHover = $styles['brand_hover'] ?? '#48c960';
    $gold       = $styles['second_color'] ?? '#C9A227';

    $rp = fn ($v) => 'Rp ' . number_format((float) $v, 0, ',', '.');
@endphp

@section('content')
<section id="receipt" style="padding-top:40px; padding-bottom:70px;">
  <div class="container" data-aos="fade-up">

    {{-- Success banner --}}
    <div class="rc-banner">
      <div class="rc-check"><i class="bi bi-check-lg"></i></div>
      <div>
        <h3>Pesanan Berhasil Dibuat</h3>
        <p>Simpan atau cetak bukti pesanan ini. Silakan lakukan pembayaran ke salah satu rekening di samping,
           lalu unggah bukti pembayaran Anda.</p>
      </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="rc-alert rc-alert-ok"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('failed'))
      <div class="rc-alert rc-alert-err"><i class="bi bi-exclamation-triangle"></i> {{ session('failed') }}</div>
    @endif

    <div class="row">
      {{-- ================= LEFT: the receipt / invoice ================= --}}
      <div class="col-lg-8">
        <div class="rc-invoice">
          {{-- ===== Invoice header ===== --}}
          <div class="rc-head">
            <div class="rc-seller">
              @if(!empty($website) && $website->logo)
                <img src="{{ asset($website->logo) }}" alt="logo" class="rc-logo">
              @endif
              <div class="rc-seller-name">{{ $website->company_name ?? ($website->web_name ?? 'Toko') }}</div>
              @if(!empty($website->address))<div class="rc-seller-line">{{ $website->address }}</div>@endif
              @if(!empty($website->phone_number))<div class="rc-seller-line"><i class="bi bi-telephone"></i> {{ $website->phone_number }}</div>@endif
              @if(!empty($website->email))<div class="rc-seller-line"><i class="bi bi-envelope"></i> {{ $website->email }}</div>@endif
            </div>
            <div class="rc-meta">
              <div class="rc-inv-title">INVOICE</div>
              <table class="rc-meta-table">
                <tr><td>No.</td><td><strong>{{ $trx->receipt_no ?: '#'.$trx->id }}</strong></td></tr>
                <tr><td>Tanggal</td><td>{{ optional($trx->transaction_date)->format('d M Y') }}</td></tr>
                <tr><td>Status</td><td><span class="rc-status">{{ optional($trx->statusCode)->name ?? $trx->status }}</span></td></tr>
              </table>
            </div>
          </div>

          {{-- ===== Bill to / ship to ===== --}}
          <div class="rc-parties">
            <div class="rc-party">
              <div class="rc-party-label">Pelanggan</div>
              <div class="rc-party-name">{{ $trx->customer_name }}</div>
              @if($trx->customer_phone)<div class="rc-party-line"><i class="bi bi-telephone"></i> {{ $trx->customer_phone }}</div>@endif
              @if($trx->customer_email)<div class="rc-party-line"><i class="bi bi-envelope"></i> {{ $trx->customer_email }}</div>@endif
            </div>
            @if($trx->address)
            <div class="rc-party">
              <div class="rc-party-label">Alamat Pengiriman</div>
              <div class="rc-party-line">{{ $trx->address->address_detail }}</div>
              <div class="rc-party-line">
                {{ collect([
                    $trx->address->subdistrict_name,
                    $trx->address->district_name,
                    $trx->address->city_name,
                    $trx->address->province_name,
                ])->filter()->implode(', ') }}
              </div>
            </div>
            @endif
          </div>

          {{-- ===== Items ===== --}}
          <div class="table-responsive">
            <table class="rc-table">
              <thead>
                <tr>
                  <th>Item</th>
                  <th class="text-center" style="width:60px;">Qty</th>
                  <th class="text-right" style="width:130px;">Harga</th>
                  <th class="text-right" style="width:140px;">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                {{-- Packages first: they carry the price, their contents follow at 0. --}}
                @foreach($trx->packages as $pk)
                <tr>
                  <td>
                    {{ $pk->package_name }} <span class="rc-tag">paket</span>
                    @if($pk->package_code)<div class="rc-code">{{ $pk->package_code }}</div>@endif
                  </td>
                  <td class="text-center">{{ $pk->qty }}</td>
                  <td class="text-right">{{ $rp($pk->price) }}</td>
                  <td class="text-right">
                    {{ $rp($pk->price * $pk->qty) }}
                    @if((float) $pk->discount > 0)
                      <div class="rc-line-disc">- {{ $rp($pk->discount) }}</div>
                    @endif
                  </td>
                </tr>
                @endforeach

                @foreach($trx->details as $d)
                <tr>
                  <td>
                    {{ $d->product_name }}
                    @if($d->variant_name)<span class="rc-variant">— {{ $d->variant_name }}</span>@endif
                    @if($d->product_code)<div class="rc-code">{{ $d->product_code }}</div>@endif
                  </td>
                  <td class="text-center">{{ $d->qty }}</td>
                  @if($d->price > 0)
                    <td class="text-right">{{ $rp($d->price) }}</td>
                    <td class="text-right">{{ $rp($d->subtotal) }}</td>
                  @else
                    {{-- Priced at 0 because a package already covers it. --}}
                    <td class="text-right" colspan="2">
                      <span class="rc-included">{{ $d->remark ?: 'Hubungi' }}</span>
                    </td>
                  @endif
                </tr>
                @endforeach

                @foreach($trx->chargeItems as $c)
                <tr>
                  <td>{{ $c->name }} <span class="rc-variant">(biaya)</span></td>
                  <td class="text-center">1</td>
                  @if((float) $c->amount > 0)
                    <td class="text-right">{{ $rp($c->amount) }}</td>
                    <td class="text-right">{{ $rp($c->amount) }}</td>
                  @else
                    <td class="text-right" colspan="2">
                      <span class="rc-included">{{ $c->remark ?: '—' }}</span>
                    </td>
                  @endif
                </tr>
                @endforeach

                @foreach($trx->benefits as $b)
                <tr>
                  <td>{{ $b->benefit_name }} <span class="rc-variant">(bonus)</span></td>
                  <td class="text-center">{{ $b->qty }}</td>
                  <td class="text-right" colspan="2">
                    <span class="rc-included">{{ $b->remark ?: 'Termasuk paket' }}</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- ===== Totals ===== --}}
          <div class="rc-totals">
            <div class="rc-sumline"><span>Subtotal</span><span>{{ $rp($trx->price) }}</span></div>
            @if((float) $trx->discount > 0)
              <div class="rc-sumline"><span>Diskon</span><span>- {{ $rp($trx->discount) }}</span></div>
            @endif
            <div class="rc-sumline"><span>Ongkir</span><span>{{ $rp($trx->delivery_fee) }}</span></div>
            @if((float) $trx->charges > 0)
              <div class="rc-sumline"><span>Biaya Lainnya</span><span>{{ $rp($trx->charges) }}</span></div>
            @endif
            @if((float) $trx->tax > 0)
              <div class="rc-sumline"><span>Pajak</span><span>{{ $rp($trx->tax) }}</span></div>
            @endif
            <div class="rc-total"><span>Total</span><span>{{ $rp($trx->grandtotal) }}</span></div>
          </div>

          @if($trx->remark)
            <div class="rc-remark"><strong>Catatan:</strong> {{ $trx->remark }}</div>
          @endif
        </div>

        @php
          $waNo  = preg_replace('/[^0-9]/', '', (string) web_property('whatsapp_no'));
          $waMsg = 'Hallo saya ingin memFollow Up Pesanan saya dengan nomor *'
                   . ($trx->receipt_no ?: '#'.$trx->id) . '* atas nama *' . $trx->customer_name . '*.';
        @endphp
        <div class="rc-actions">
          <button type="button" class="rc-btn rc-btn-print" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak
          </button>
          @if($waNo)
          <a href="https://wa.me/{{ $waNo }}?text={{ rawurlencode($waMsg) }}" target="_blank" rel="noopener" class="rc-btn rc-btn-wa">
            <i class="bi bi-whatsapp"></i> Hubungi Kami
          </a>
          @endif
          <a href="{{ route($site.'.home') }}" class="rc-btn rc-btn-home">
            <i class="bi bi-house"></i> Kembali ke Beranda
          </a>
        </div>
      </div>

      {{-- ================= RIGHT: payment banks + upload ================= --}}
      <div class="col-lg-4">
        {{-- Banks to pay --}}
        <div class="rc-side">
          <h4 class="rc-side-title"><i class="bi bi-bank"></i> Pembayaran Transfer</h4>
          @forelse($banks as $bank)
            <div class="rc-bank">
              <div class="rc-bank-head">
                @if($bank->logo)
                  <img src="{{ asset($bank->logo) }}" alt="{{ $bank->bank_name }}" class="rc-bank-logo">
                @endif
                <span class="rc-bank-name">{{ $bank->bank_name }}</span>
              </div>
              <div class="rc-bank-acct">{{ $bank->bank_account }}</div>
              <div class="rc-bank-holder">a.n. {{ $bank->account_name }}</div>
              @if($bank->branch)<div class="rc-bank-branch">{{ $bank->branch }}</div>@endif
            </div>
          @empty
            <p class="rc-empty">Belum ada rekening bank yang tersedia. Silakan hubungi kami.</p>
          @endforelse
        </div>

        {{-- Upload proof --}}
        <div class="rc-side">
          <h4 class="rc-side-title"><i class="bi bi-cloud-arrow-up"></i> Unggah Bukti</h4>
          <form action="{{ route($site.'.receipt.upload', ['token' => $trx->receipt_token]) }}"
                method="POST" enctype="multipart/form-data">
            @csrf
            <div class="rc-field">
              <label>Jenis</label>
              <input type="hidden" name="type" value="PAYMENT">
              <div class="rc-static">Bukti Pembayaran</div>
            </div>
            <div class="rc-field">
              <label>Berkas <span style="color:#c0392b;">*</span></label>
              <input type="file" name="file" class="rc-input" accept="image/*,application/pdf" required>
              <small class="rc-hint">JPG, PNG, atau PDF. Maks 5 MB.</small>
            </div>
            <div class="rc-field">
              <label>Catatan</label>
              <input type="text" name="note" class="rc-input" placeholder="Opsional">
            </div>
            <button type="submit" class="rc-upload-btn"><i class="bi bi-upload"></i> Unggah</button>
          </form>
        </div>

        {{-- Uploaded attachments --}}
        @if($trx->attachments->count())
        <div class="rc-side">
          <h4 class="rc-side-title"><i class="bi bi-images"></i> Berkas Terunggah</h4>
          <div class="rc-attach-grid">
            @foreach($trx->attachments as $att)
              @php $isImg = \Illuminate\Support\Str::startsWith((string) $att->mime, 'image/'); @endphp
              <a href="{{ asset($att->file_path) }}" target="_blank" class="rc-attach" title="{{ $att->type_label }}">
                @if($isImg)
                  <img src="{{ asset($att->file_path) }}" alt="{{ $att->type_label }}">
                @else
                  <span class="rc-attach-file"><i class="bi bi-file-earmark-text"></i></span>
                @endif
                <span class="rc-attach-tag">{{ $att->type_label }}</span>
              </a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>

  </div>
</section>

<style>
  #receipt .rc-banner {
    display:flex; align-items:center; gap:16px; margin:0 0 18px;
    background:{{ $brand }}0d; border:1px solid {{ $brand }}40; border-radius:10px; padding:18px 20px;
  }
  #receipt .rc-check {
    flex:0 0 auto; width:48px; height:48px; border-radius:50%; background:{{ $brand }};
    color:#fff; font-size:26px; line-height:48px; text-align:center;
  }
  #receipt .rc-banner h3 { margin:0 0 2px; font-size:18px; font-weight:800; }
  #receipt .rc-banner p { margin:0; color:#666; font-size:13px; }

  #receipt .rc-alert { border-radius:8px; padding:12px 16px; font-size:14px; margin-bottom:16px; }
  #receipt .rc-alert-ok { background:#e8f8ee; color:#1e7e44; border:1px solid #b6e6c7; }
  #receipt .rc-alert-err { background:#fdecea; color:#a83228; border:1px solid #f5c6c0; }

  #receipt .rc-invoice {
    background:#fff; border:1px solid #eee; border-radius:12px;
    box-shadow:0 2px 16px rgba(0,0,0,.06); padding:28px;
  }
  #receipt .rc-head { display:flex; justify-content:space-between; flex-wrap:wrap; gap:20px; border-bottom:2px solid #f0f0f0; padding-bottom:18px; margin-bottom:18px; }
  #receipt .rc-logo { max-height:52px; max-width:170px; margin-bottom:10px; display:block; }
  #receipt .rc-seller-name { font-weight:800; font-size:16px; color:#222; }
  #receipt .rc-seller-line { font-size:13px; color:#777; margin-top:2px; }
  #receipt .rc-meta { text-align:right; min-width:210px; }
  #receipt .rc-inv-title { font-size:24px; font-weight:800; letter-spacing:.08em; color:{{ $brand }}; margin-bottom:8px; }
  #receipt .rc-meta-table { margin-left:auto; font-size:13px; }
  #receipt .rc-meta-table td { padding:2px 0; }
  #receipt .rc-meta-table td:first-child { color:#999; padding-right:14px; text-align:right; }
  #receipt .rc-status {
    display:inline-block; background:{{ $gold }}; color:#fff; font-size:11px; font-weight:700;
    padding:2px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.03em;
  }

  #receipt .rc-parties { display:flex; flex-wrap:wrap; gap:24px; margin-bottom:20px; }
  #receipt .rc-party { flex:1 1 220px; }
  #receipt .rc-party-label { font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:#aaa; margin-bottom:4px; }
  #receipt .rc-party-name { font-weight:700; font-size:15px; }
  #receipt .rc-party-line { font-size:13px; color:#666; margin-top:2px; }

  #receipt .rc-table { width:100%; border-collapse:collapse; }
  #receipt .rc-table thead th { font-size:12px; text-transform:uppercase; letter-spacing:.03em; color:#999; border-bottom:2px solid #eee; padding:9px 10px; }
  #receipt .rc-table tbody td { padding:11px 10px; border-bottom:1px solid #f2f2f2; font-size:14px; vertical-align:top; }
  #receipt .rc-variant { color:#888; }
  #receipt .rc-code { font-size:11px; color:#bbb; margin-top:2px; }
  #receipt .rc-tag {
    display:inline-block; margin-left:6px; padding:1px 7px; border-radius:10px;
    background:rgba(64,192,87,.12); color:{{ $brand }}; font-size:10px; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; vertical-align:middle;
  }
  #receipt .rc-included { font-size:12px; color:#999; font-style:italic; }
  #receipt .rc-line-disc { font-size:12px; color:#e03131; font-weight:600; }
  #receipt .text-right { text-align:right; }
  #receipt .text-center { text-align:center; }

  #receipt .rc-totals { margin:16px 0 0 auto; max-width:320px; }
  #receipt .rc-sumline { display:flex; justify-content:space-between; font-size:14px; color:#555; padding:5px 0; }
  #receipt .rc-total { display:flex; justify-content:space-between; font-weight:800; font-size:19px; margin-top:8px; padding-top:10px; border-top:2px solid #eee; }
  #receipt .rc-total span:last-child { color:{{ $gold }}; }
  #receipt .rc-remark { margin-top:16px; font-size:13px; color:#555; border-top:1px dashed #e3e3e3; padding-top:12px; }

  #receipt .rc-actions { margin:18px 0 0; display:flex; gap:12px; }
  #receipt .rc-btn { display:inline-flex; align-items:center; gap:7px; padding:11px 20px; border-radius:8px; font-weight:700; font-size:14px; text-decoration:none; border:0; cursor:pointer; transition:.2s; }
  #receipt .rc-btn-print { background:#f1f3f5; color:#444; }
  #receipt .rc-btn-print:hover { background:#e6e9ec; }
  #receipt .rc-btn-home { background:{{ $brand }}; color:#fff; }
  #receipt .rc-btn-home:hover { background:{{ $brandHover }}; }
  #receipt .rc-btn-wa { background:#25D366; color:#fff; }
  #receipt .rc-btn-wa:hover { background:#1ebe5a; }

  /* ----- right column ----- */
  #receipt .rc-side {
    background:#fff; border:1px solid #eee; border-radius:12px;
    box-shadow:0 2px 16px rgba(0,0,0,.06); padding:20px; margin-bottom:20px;
  }
  #receipt .rc-side-title { font-size:15px; font-weight:800; color:{{ $brand }}; margin-bottom:14px; }
  #receipt .rc-side-title i { margin-right:6px; }

  #receipt .rc-bank { border:1px solid #eee; border-radius:8px; padding:12px 14px; margin-bottom:10px; }
  #receipt .rc-bank:last-child { margin-bottom:0; }
  #receipt .rc-bank-head { display:flex; align-items:center; gap:8px; margin-bottom:4px; }
  #receipt .rc-bank-logo { max-height:22px; max-width:60px; }
  #receipt .rc-bank-name { font-weight:700; font-size:14px; }
  #receipt .rc-bank-acct { font-size:18px; font-weight:800; letter-spacing:.04em; color:#222; }
  #receipt .rc-bank-holder { font-size:13px; color:#666; }
  #receipt .rc-bank-branch { font-size:12px; color:#999; margin-top:2px; }
  #receipt .rc-empty { font-size:13px; color:#999; margin:0; }

  #receipt .rc-field { margin-bottom:12px; }
  #receipt .rc-field label { display:block; font-size:12px; color:#666; margin-bottom:4px; }
  #receipt .rc-input { width:100%; border:1px solid #ddd; border-radius:7px; padding:9px 11px; font-size:14px; }
  #receipt .rc-static { background:#f4f5f7; border:1px solid #e6e8eb; border-radius:7px; padding:9px 11px; font-size:14px; color:#555; }
  #receipt .rc-hint { display:block; color:#aaa; font-size:11px; margin-top:3px; }
  #receipt .rc-upload-btn {
    width:100%; border:0; padding:11px; border-radius:8px; color:#fff; font-weight:700;
    background:{{ $brand }}; cursor:pointer; transition:.2s;
  }
  #receipt .rc-upload-btn:hover { background:{{ $brandHover }}; }

  #receipt .rc-attach-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; }
  #receipt .rc-attach { position:relative; display:block; border:1px solid #eee; border-radius:7px; overflow:hidden; aspect-ratio:1/1; background:#fafafa; }
  #receipt .rc-attach img { width:100%; height:100%; object-fit:cover; }
  #receipt .rc-attach-file { display:flex; align-items:center; justify-content:center; height:100%; font-size:26px; color:{{ $brand }}; }
  #receipt .rc-attach-tag {
    position:absolute; left:0; right:0; bottom:0; background:rgba(0,0,0,.6); color:#fff;
    font-size:9px; text-align:center; padding:2px 3px; text-transform:uppercase; letter-spacing:.02em;
  }

  @media print {
    /* Trim the page margins the browser adds around the sheet. */
    @page { margin: 12mm; }

    /* Hide everything that sits above/around the invoice. */
    #header, #topbar, #page-hero, #footer, .back-to-top,
    header, footer, .rc-banner, .rc-actions, .rc-side, .rc-alert { display:none !important; }

    /* Drop the top spacing the fixed header/hero normally reserve. */
    html, body { margin:0 !important; padding:0 !important; }
    #main { margin-top:0 !important; padding-top:0 !important; }
    #receipt { padding:0 !important; margin:0 !important; }

    #receipt .col-lg-8 { max-width:100%; flex:0 0 100%; }
    #receipt .rc-invoice { box-shadow:none; border:0; padding:0; }
  }
</style>
@endsection
