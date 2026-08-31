@extends('layout.website.main')

@section('hero')
<!-- ======= hero Section ======= -->
<section id="hero">

  <div class="hero-content" data-aos="fade-up">
    <div class="col-md-12">
      {{-- <h3 class="third-color" style="font-size: 60px; font-weight:700; text-align:center; text-shadow:5px 5px 5px grey;">ENERGIZING YOUR SERVICE</h3> --}}
      <img src="{{ web_asset('logo_white', 'webassets/img/gls/logo-text-w.png') }}" class="banner-icon" alt="{{ web_property('web_name', 'banner') }}">
    </div>
  </div>

  <div class="hero-slider swiper">
    <div class="swiper-wrapper">
      @forelse(($banners ?? collect()) as $banner)
        <div class="swiper-slide" style="background-image: url('{{ asset($banner->banner_img) }}');"></div>
      @empty
        <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/hero1.jpg') }}');"></div>
        <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/hero2.jpeg') }}');"></div>
        <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/hero3.jpg') }}');"></div>
        <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/hero4.jpg') }}');"></div>
        <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/hero5.jpg') }}');"></div>
      @endforelse
    </div>
  </div>

</section><!-- End Hero Section -->
@endsection

@section('main')
  @yield('content')
@endsection

@section('script')
  @yield('script')
  <script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('#track-btn').click(function() {
        $('.title-btn').removeClass('active');
        $(this).addClass('active');
        
        $('#price-form').animate({ left: '100%' }, 300, function() {
          $(this).css('display', 'none');
          $('#track-form').css({ 'display': 'block', 'left': '-100%' });
          $('#track-form').animate({ left: '0' }, 300);
        });
      });

      $('#price-btn').click(function() {
        $('.title-btn').removeClass('active');
        $(this).addClass('active');
        
        $('#track-form').animate({ left: '100%' }, 300, function() {
          $(this).css('display', 'none');
          $('#price-form').css({ 'display': 'block', 'left': '-100%' });
          $('#price-form').animate({ left: '0' }, 300);
        });
      });

    });
  </script>
@endsection