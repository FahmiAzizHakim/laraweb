{{-- The portal on the main index: a banner, and one option per website.
     Standalone page (no website layout) -- it belongs to the group, not to any
     one of the three sites. Expects $options, $styles, $hero. --}}
@php
    $brand      = $styles['brand_color']  ?? '#40c057';
    $brandHover = $styles['brand_hover']  ?? '#48c960';
    $accent     = $styles['accent_color'] ?? '#18a3c2';
    $dark       = $styles['text_dark']    ?? '#101820';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ web_property('company_name', 'Green Logistic Solution') }}</title>
  <meta name="description" content="Pilih layanan: kontrak logistik, instalasi furniture &amp; AC, atau solusi pengisian daya kendaraan listrik.">

  <link href="{{ asset('webassets/img/gls/icon.ico') }}" rel="icon">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Open Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
      color: {{ $dark }};
      background: #f4f7f5;
    }

    /* ---------- banner ---------- */
    .pt-hero {
      position: relative;
      min-height: 340px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      background-image: url('{{ asset($hero) }}');
      background-size: cover;
      background-position: center;
    }
    .pt-hero::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(16, 40, 24, .82) 0%, rgba(16, 40, 24, .68) 100%);
    }
    .pt-hero-inner {
      position: relative;
      z-index: 1;
      padding: 56px 20px;
      max-width: 780px;
    }
    .pt-logo { height: 74px; margin-bottom: 22px; }
    .pt-hero h1 {
      margin: 0 0 12px;
      color: #fff;
      font-size: 34px;
      font-weight: 700;
      line-height: 1.25;
    }
    .pt-hero p {
      margin: 0;
      color: rgba(255, 255, 255, .88);
      font-size: 16px;
    }

    /* ---------- options ---------- */
    .pt-options {
      max-width: 1180px;
      margin: -58px auto 0;
      padding: 0 20px 70px;
      position: relative;
      z-index: 2;
    }
    .pt-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
      gap: 26px;
    }
    .pt-card {
      display: flex;
      flex-direction: column;
      background: #fff;
      border: 1px solid #e6ecea;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 18px rgba(0, 0, 0, .07);
      text-decoration: none;
      color: inherit;
      transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .pt-card:hover {
      transform: translateY(-6px);
      border-color: {{ $brand }};
      box-shadow: 0 16px 34px rgba(0, 0, 0, .14);
      color: inherit;
    }
    .pt-card-img {
      height: 158px;
      background-size: cover;
      background-position: center;
      background-color: #dfe7e2;
    }
    .pt-card-body {
      display: flex;
      flex-direction: column;
      flex: 1;
      padding: 22px 22px 24px;
    }
    .pt-card-icon {
      width: 46px;
      height: 46px;
      margin: -46px 0 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      background: {{ $brand }};
      color: #fff;
      font-size: 22px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, .18);
    }
    .pt-card h2 {
      margin: 0 0 8px;
      font-size: 19px;
      font-weight: 700;
      line-height: 1.35;
    }
    .pt-card p {
      margin: 0 0 20px;
      font-size: 14px;
      color: #6c7a72;
      line-height: 1.6;
    }
    .pt-card-go {
      margin-top: auto;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 700;
      color: {{ $brand }};
      text-transform: uppercase;
      letter-spacing: .04em;
    }
    .pt-card:hover .pt-card-go { color: {{ $brandHover }}; }
    .pt-card-go i { transition: transform .25s ease; }
    .pt-card:hover .pt-card-go i { transform: translateX(4px); }

    .pt-empty {
      background: #fff;
      border: 1px dashed #cfd9d4;
      border-radius: 14px;
      padding: 40px;
      text-align: center;
      color: #6c7a72;
    }

    /* ---------- footer ---------- */
    .pt-footer {
      border-top: 1px solid #e2e9e5;
      background: #fff;
      padding: 26px 20px;
      text-align: center;
      font-size: 13px;
      color: #8b9a92;
    }
    .pt-footer a { color: {{ $accent }}; text-decoration: none; }

    @media (max-width: 575px) {
      .pt-hero h1 { font-size: 26px; }
      .pt-options { margin-top: -40px; }
    }
  </style>
</head>
<body>

  {{-- ================= Banner ================= --}}
  <section class="pt-hero">
    <div class="pt-hero-inner">
      <img src="{{ web_asset('logo_white', 'webassets/img/gls/logo-text-w.png') }}" alt="{{ web_property('company_name', 'GLS') }}" class="pt-logo">
      <h1>{{ web_property('company_name', 'PT. Green Logistic Solution') }}</h1>
      <p>Pilih layanan yang Anda butuhkan.</p>
    </div>
  </section>

  {{-- ================= Options ================= --}}
  <section class="pt-options">
    @if($options->isEmpty())
      <div class="pt-empty">Belum ada layanan yang tersedia.</div>
    @else
      <div class="pt-grid">
        @foreach($options as $option)
          <a href="{{ $option['url'] }}" class="pt-card">
            <div class="pt-card-img" @if($option['banner']) style="background-image:url('{{ asset($option['banner']) }}');" @endif></div>
            <div class="pt-card-body">
              <div class="pt-card-icon"><i class="bi {{ $option['icon'] }}"></i></div>
              <h2>{{ $option['website']->web_name }}</h2>
              <p>{{ $option['tagline'] }}</p>
              <span class="pt-card-go">Masuk <i class="bi bi-arrow-right"></i></span>
            </div>
          </a>
        @endforeach
      </div>
    @endif
  </section>

  <footer class="pt-footer">
    &copy; {{ date('Y') }} {{ web_property('company_name', 'PT. Green Logistic Solution') }}
    @if(web_property('email')) &middot; <a href="mailto:{{ web_property('email') }}">{{ web_property('email') }}</a> @endif
    @if(web_property('phone_number')) &middot; {{ web_property('phone_number') }} @endif
  </footer>

</body>
</html>
