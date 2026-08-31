@extends('layout.website2.inner')

@php
    $brand = $styles['brand_color'] ?? '#40c057';
    $brandHover = $styles['brand_hover'] ?? '#48c960';
@endphp

@section('content')
<section id="done" style="padding-top:70px; padding-bottom:90px;">
  <div class="container" data-aos="fade-up">
    <div class="dn-box">
      <div class="dn-icon"><i class="bi bi-check-lg"></i></div>
      <h2>Terima kasih!</h2>
      <p>Pesanan Anda telah kami terima. Tim kami akan segera menghubungi Anda untuk konfirmasi
         dan pengaturan pengiriman.</p>
      <a href="{{ route($site.'.home') }}" class="dn-home"><i class="bi bi-house"></i> Kembali ke Beranda</a>
    </div>
  </div>
</section>

<style>
  #done .dn-box {
    max-width:520px; margin:0 auto; text-align:center;
    background:#fff; border:1px solid #eee; border-radius:14px;
    box-shadow:0 4px 24px rgba(0,0,0,.07); padding:48px 32px;
  }
  #done .dn-icon {
    width:84px; height:84px; margin:0 auto 22px; border-radius:50%;
    background:{{ $brand }}; color:#fff; font-size:44px; line-height:84px;
  }
  #done h2 { font-weight:800; margin-bottom:12px; }
  #done p { color:#666; font-size:15px; margin-bottom:26px; }
  #done .dn-home {
    display:inline-block; background:{{ $brand }}; color:#fff; font-weight:700;
    padding:11px 22px; border-radius:8px; text-decoration:none; transition:.2s;
  }
  #done .dn-home:hover { background:{{ $brandHover }}; }
</style>
@endsection
