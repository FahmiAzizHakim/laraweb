{{-- Inner-page layout for website 2 (any page except the index).
     Shows a short hero strip (a bit of background, no logo, no service buttons),
     mainly so the fixed header has something to sit over.
     Child pages define @section('content'). --}}
@extends('layout.website2.main')

@php
    $pageHeroBg = optional(($banners ?? collect())->first())->banner_img
        ?? 'webassets/img/gls/hero1.jpg';
@endphp

@section('hero')
<section id="page-hero">
  <div class="page-hero-overlay"></div>
</section>

<style>
  #page-hero {
    position: relative;
    height: 200px;                 /* small strip; the fixed header overlaps the top */
    background-image: url('{{ asset($pageHeroBg) }}');
    background-size: cover;
    background-position: center;
  }
  #page-hero .page-hero-overlay {
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: rgba(43, 65, 32, 0.54);
  }
</style>
@endsection

@section('main')
  @yield('content')
@endsection

@section('script')
  @yield('script')
@endsection
