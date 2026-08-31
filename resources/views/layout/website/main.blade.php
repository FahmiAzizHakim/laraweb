<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ web_property('web_name', 'Green Logistics Solution') }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('webassets/img/gls/icon.ico') }}" rel="icon">
  <link href="{{ asset('webassets/img/gls/icon.ico') }}" rel="apple-touch-icon">

  <meta name="author" content="{{ web_property('company_name') }}">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('webassets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('webassets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="{{ asset('webassets/css/style.css') }}" rel="stylesheet">
  {{-- <link href="{{ asset('webassets/css/custom.css') }}" rel="stylesheet"> --}}
  @include('layout.website.css')
  <!-- =======================================================
  * Template Name: Reveal
  * Template URL: https://bootstrapmade.com/reveal-bootstrap-corporate-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:{{ web_property('email') }}">{{ web_property('email') }}</a></i>
        <i class="bi bi-phone d-flex align-items-center ms-4"><span>{{ web_property('phone_number') }}</span></i>
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        @if(web_property('whatsapp_no'))<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', web_property('whatsapp_no')) }}" target="_blank" rel="noopener" class="whatsapp"><i class="bi bi-whatsapp"></i></a>@endif
        @if(web_property('facebook_link'))<a href="{{ web_property('facebook_link') }}" target="_blank" rel="noopener" class="facebook"><i class="bi bi-facebook"></i></a>@endif
        @if(web_property('instagram_link'))<a href="{{ web_property('instagram_link') }}" target="_blank" rel="noopener" class="instagram"><i class="bi bi-instagram"></i></a>@endif
        @if(web_property('twitter_link'))<a href="{{ web_property('twitter_link') }}" target="_blank" rel="noopener" class="twitter"><i class="bi bi-twitter"></i></a>@endif
        @if(web_property('linkedin_link'))<a href="{{ web_property('linkedin_link') }}" target="_blank" rel="noopener" class="linkedin"><i class="bi bi-linkedin"></i></a>@endif
      </div>
    </div>
  </section><!-- End Top Bar-->

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex justify-content-between">

      <div id="logo">
        {{-- <h1><a href="index.html">Reve<span>al</span></a></h1> --}}
        <!-- Uncomment below if you prefer to use an image logo -->
        <a href="{{ route('home') }}"><img src="{{ web_asset('logo', 'webassets/img/gls/gls-bold.png') }}" height="70" alt="{{ web_property('web_name', 'logo') }}"></a>
      </div>

      <nav id="navbar" class="navbar">
        @if (url()->current() == route('home'))
        <ul>
          <li><a class="nav-link scrollto active" href="#">Home</a></li>
          <li><a class="nav-link scrollto" href="#tracking">Tracking</a></li>
          <li><a class="nav-link scrollto" href="#price">Check Price</a></li>
          <li><a class="nav-link scrollto" href="#services">Service</a></li>
          <li><a class="nav-link scrollto" href="#about">About</a></li>
          <li><a class="nav-link scrollto" href="#clients">Clients</a></li>
          <li><a class="nav-link scrollto" href="#articles">Articles</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
        </ul>
        @else
        <ul>
          <li><a class="nav-link scrollto active" href="{{ route('home') }}">Home</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#tracking">Tracking</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#price">Check Price</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#services">Service</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#about">About</a></li>          
          <li><a class="nav-link scrollto" href="{{ route('home') }}#clients">Clients</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#articles">Articles</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#contact">Contact</a></li>
        </ul>
        @endif
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= hero Section ======= -->
  @yield('hero')
  <!-- End Hero Section -->

  <main id="main">
    @yield('main')
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container py-4">
      <div class="row">
        <div class="col-md-3">
          <img src="{{ web_asset('logo_white', 'webassets/img/gls/logo-text-w.png') }}" height="150" alt="{{ web_property('web_name', 'logo') }}">
        </div>
        <div class="col-md-4">
          <div class="row">
            <h4 class="mb-2 bold">Pages</h4>
            <div class="col-md-6">
              <a class="footer-nav" href="#">Home</a>
              <a class="footer-nav" href="#tracking">Tracking</a>
              <a class="footer-nav" href="#price">Check Price</a>
              <a class="footer-nav" href="#services">Services</a>
            </div>
            <div class="col-md-6">
              <a class="footer-nav" href="#about">About</a>
              <a class="footer-nav" href="#clients">Client</a>
              <a class="footer-nav" href="#articles">Articles</a>
              <a class="footer-nav" href="#contacts">Contact</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <h4 class="mb-2 bold">Contact Us</h4>
          <p class="mb-2">{{ web_property('address') }}</p>
          <p class="mb-2">{{ web_property('phone_number') }}</p>
          <p class="mb-2">{{ web_property('email') }}</p>
        </div>
      </div>
      <div class="copyright">
        &copy; Copyright <strong>{{ web_property('company_name', 'PT. Green Logistic Solution') }}</strong>. All Rights Reserved 2024
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Reveal
      -->
        {{-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> --}}
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('webassets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('webassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('webassets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('webassets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('webassets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('webassets/vendor/php-email-form/validate.js') }}"></script>


  <!-- Template Main JS File -->
  <script src="{{ asset('webassets/js/main.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  @yield('script')
</body>

</html>