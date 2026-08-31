@php
    // Editable website styles shared from WebController (web_styles table).
    // Each value falls back to the original hard-coded default so the site
    // still renders correctly before the migration/seeder are run.
    $styles = $styles ?? [];

    $brand_color        = $styles['brand_color']        ?? '#40c057';
    $brand_hover        = $styles['brand_hover']        ?? '#48c960';
    $accent_color       = $styles['accent_color']       ?? '#18a3c2';
    $second_color       = $styles['second_color']       ?? '#00f513';
    $third_color        = $styles['third_color']        ?? '#1cb79a';
    $muted_color        = $styles['muted_color']        ?? '#adadad';
    $text_dark          = $styles['text_dark']          ?? '#000000';
    $light_color        = $styles['light_color']        ?? '#ffffff';
    $header_color    = $styles['header_color']    ?? 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)';
    $topbar_color    = $styles['topbar_color']    ?? 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)';
    $button_gradient    = $styles['button_gradient']    ?? 'linear-gradient(90deg, rgb(0 233 46) 0%, rgb(0 152 215) 100%)';
    $container_width    = $styles['container_width']    ?? '1250px';
    $banner_height      = $styles['banner_height']      ?? '300px';
    $banner_height_md   = $styles['banner_height_md']   ?? '225px';
    $banner_height_sm   = $styles['banner_height_sm']   ?? '170px';
    $hero_height        = $styles['hero_height']        ?? '67vh';
    $hero_slider_height = $styles['hero_slider_height'] ?? '76vh';
@endphp
<style>
#hero {
  width: 100%;
  height: 60vh;
  position: relative;
  background: url("../img/hero-carousel/1.jpg") no-repeat;
  background-size: cover;
  padding: 0;
}

#hero .hero-content {
  position: absolute;
  bottom: 0;
  top: 0;
  left: 0;
  right: 0;
  z-index: 10;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
}

#hero .hero-content h2 {
  color: #0c2e8a;
  margin-bottom: 30px;
  font-size: 64px;
  font-weight: 700;
}

#hero .hero-content h2 span {
  color: {{ $second_color }};
  text-decoration: underline;
}

@media (max-width: 767px) {
  #hero .hero-content h2 {
    font-size: 34px;
  }
}

#hero .hero-content .btn-get-started,
#hero .hero-content .btn-projects {
  font-family: "Raleway", sans-serif;
  font-size: 15px;
  font-weight: bold;
  letter-spacing: 1px;
  display: inline-block;
  padding: 10px 32px;
  border-radius: 2px;
  transition: 0.5s;
  margin: 10px;
  color: #fff;
}

#hero .hero-content .btn-get-started {
  background: #0c2e8a;
  border: 2px solid #0c2e8a;
}

#hero .hero-content .btn-get-started:hover {
  background: none;
  color: #0c2e8a;
}

#hero .hero-content .btn-projects {
  background: #50d8af;
  border: 2px solid #50d8af;
}

#hero .hero-content .btn-projects:hover {
  background: none;
  color: #50d8af;
}

#hero .hero-slider {
  z-index: 8;
  height: 60vh;
}

#hero .hero-slider .swiper-slide {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  transition-property: opacity;
}

.banner-icon {
  height: {{ $banner_height }};
}

#hero .hero-title {
  margin: 18px 0 0;
  color: #fff;
  font-size: 42px;
  font-weight: 700;
  line-height: 1.2;
  letter-spacing: .01em;
  text-shadow: 0 3px 14px rgba(0, 0, 0, .55);
}

#hero .hero-cta {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  margin-top: 26px;
  padding: 13px 30px;
  border: 2px solid {{ $brand_color }};
  border-radius: 30px;
  background: {{ $brand_color }}d9;
  color: {{ $light_color }};
  font-size: 15px;
  font-weight: 600;
  letter-spacing: .02em;
  text-transform: uppercase;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(0, 0, 0, .28);
  transition: background .25s ease, transform .25s ease, box-shadow .25s ease;
}

#hero .hero-cta:hover {
  background: {{ $brand_hover }};
  color: {{ $light_color }};
  transform: translateY(-3px);
  box-shadow: 0 10px 26px rgba(0, 0, 0, .34);
}

#hero .hero-cta i { font-size: 18px; }

@media (max-width: 991px) {
  #hero .hero-title { font-size: 32px; }
  #hero .hero-cta { margin-top: 20px; padding: 11px 24px; font-size: 14px; }
}

@media (max-width: 575px) {
  #hero .hero-title { font-size: 24px; margin-top: 12px; }
}

@media (max-width: 767px) {
  .banner-icon {
    height: {{ $banner_height_md }};
  }
}

@media (max-width: 450px) {
  .banner-icon {
    height: {{ $banner_height_sm }};
  }
}

#hero .hero-slider::before {
  content: "";
  background: rgb(43 65 32 / 54%);
  /* background: linear-gradient(90deg, rgba(28,28,28,0.9) 0%, rgba(36,104,15,0.7) 66%, rgba(45,187,0,0.7) 100%); */
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  z-index: 7;
}

#orders {
  color: #ffffff;
  background: {{ $header_color }};
}

#orders .section-header h2 {
  color: #ffffff;
}

.container {
  max-width: {{ $container_width }} !important;
}
.text-right {
  text-align: right;
}

.text-center {
  text-align: center;
}

.bold {
  font-weight: 700 !important;
}

.navbar a:hover, .navbar .active, .navbar .active:focus, .navbar li:hover>a {
  color: {{ $light_color }};
  text-shadow: 3px 3px 4px {{ $accent_color }};
}

.navbar .dropdown ul a:hover, .navbar .dropdown ul .active:hover, .navbar .dropdown ul li:hover>a {
  color: {{ $light_color }};
  text-shadow: 3px 3px 4px {{ $accent_color }};
}

.navbar a {
  color: {{ $light_color }};
}

.navbar .dropdown ul {
  background-color: {{ $brand_color }} !important;
}

#topbar {
  background: {{ $topbar_color }};
}

.mobile-nav-toggle {
  color: {{ $second_color }};
}

#topbar .contact-info i {
  font-style: normal;
  color: {{ $brand_color }};
}

#contact .contact-info i {
  color: {{ $accent_color }};
}

#topbar .contact-info i a,
#topbar .contact-info i span {
  padding-left: 5px;
  color: {{ $brand_color }};
}

#topbar .contact-info i a {
  line-height: 0;
  transition: 0.3s;
}

#topbar .contact-info i a:hover {
  color: {{ $brand_hover }};
}

#topbar .social-links a {
  padding: 0 15px;
  display: inline-block;
  line-height: 1px;
  border-left: 1px solid #e9e9e9;
  color: {{ $brand_color }}; ;
}

#topbar .social-links a:hover {
  color: {{ $brand_hover }};
}

#hero {
  height: {{ $hero_height }} !important;
}

#hero .hero-slider {
  height: {{ $hero_slider_height }};
}

#hero.scrolled-offset {
  margin-top: 0px;
}

#page-hero.scrolled-offset {
  margin-top: 0px;
}

#header {
  position: fixed;
  /* background: transparent; */
  background: {{ $header_color }};
  top: 1px;
  right: 0;
  left: 0;
  -webkit-transition: all .5s ease;
  transition: all .5s ease;
  height: 115px !important;
}

#header.fixed-top {
  position: fixed;
  top: -40px;
}

/* ---- floating buttons (bottom right, WhatsApp above the arrow) ---- */
.back-to-top {
  background-color: {{ $brand_color }} !important;
  right: 20px !important;
  bottom: 20px !important;
  width: 46px !important;
  height: 46px !important;
  border-radius: 50%;
  box-shadow: 0 4px 14px rgba(0, 0, 0, .28);
}

.float-wa {
  position: fixed;
  right: 20px;
  bottom: 20px;               /* 78px when the back-to-top button is shown */
  z-index: 997;
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: #25d366;
  color: #fff;
  text-decoration: none;
  box-shadow: 0 4px 16px rgba(0, 0, 0, .3);
  transition: transform .25s ease, background .25s ease;
}

.float-wa:hover {
  background: #1ebe5b;
  color: #fff;
  transform: translateY(-3px);
}

.float-wa i { font-size: 27px; line-height: 0; }

@media (max-width: 575px) {
  .float-wa { width: 46px; height: 46px; bottom: 16px; right: 16px; }
  .float-wa i { font-size: 23px; }
  .back-to-top { right: 16px !important; bottom: 16px !important; }
}

.main-color {
  color: {{ $accent_color }} !important;
}
.second-color {
  color: {{ $second_color }} !important;
}
.third-color {
  color: {{ $third_color }} !important;
}
.black-color {
  color: {{ $text_dark }} !important;
}
.white-color {
  color: {{ $text_dark }} !important;
}
.red-color {
  color:red !important;
}

.section-header h2 {
  color:{{ $text_dark }};
  display: inline-block;
  padding-bottom: 8px;
}

.section-header h2::before {
  border-bottom: 3px solid {{ $second_color }};
}

.blue-card {
    box-sizing: content-box;
    padding: 30px 30px 0 30px;
    /* margin: 30px 15px; */
    text-align: center;
    box-shadow: 0px 2px 12px rgba(0, 0, 0, 0.08);
    border-radius: 5px;
    background-color: {{ $brand_color }};
    color: white;
}

.blue-card h3 {
  font-weight: 700;
}

#orders .ord-card {
    background: {{ $topbar_color }}; 
    border:1px solid #eee; 
    border-radius:12px;
    box-shadow:0 2px 16px rgba(0,0,0,.06); 
    padding:24px;
}

#orders .ord-form { 
  display:flex; 
  flex-wrap:wrap; 
  gap:16px; 
  align-items:flex-end; 
}

#orders .ord-field { 
  flex:1 1 220px; 
}

#orders .ord-field label { 
  display:block; 
  font-size:13px; 
  color: {{ $brand_color }};
  font-weight: 600; 
  margin-bottom:5px; 
}

#orders .ord-input { 
  width:100%; 
  border:1px solid #ddd; 
  border-radius:8px; 
  padding:11px 13px; 
  font-size:14px; 
}

#orders .ord-input:focus { 
  outline:none; 
  border-color:{{ $brand_color }}; 
}
#orders .ord-btn {
  flex:0 0 auto; 
  border:0; 
  padding:12px 22px; 
  border-radius:8px; 
  color:#fff; 
  font-weight:700;
  background:{{ $brand_color }}; 
  cursor: pointer; 
  transition: 0.2s; 
  white-space:nowrap;
}

#orders .ord-btn:hover {
   background:{{ $brand_hover }}; 
}

.custom-input-group .btn {
  background: {{ $button_gradient }}; /* Custom button color */
  color: white;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
}

.custom-input-group .form-control {
  border-right: none;
}
.custom-input-group .input-group-append .btn {
  border-left: none;
}

.custom-input-group .input-group-append .btn {
  border-left: none;
}

.custom-input-group .input-group-prepend .input-group-text {
  background-color: white;
  border-right: none;
  padding: 8px;
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
}

.custom-input-group .input-group-prepend img {
  width: 20px; /* Adjust the width as needed */
  height: auto; /* Maintain aspect ratio */
}

#track-input {
  height: 37px;
}

.logistic-btn {
  background-color: {{ $brand_color }};
  border: 0px;
  color: white;
  padding: 8px 15px;
  font-size: 15px;
  font-weight: 600;
  border-radius: 22px;
}

.logistic-btn:hover {
  background-color: {{ $brand_hover }};
}

.track-title {
  font-weight: 600;
  color: {{ $brand_color }};
  display: flex;
  margin-bottom: 25px;
}

.title-btn {
  background-color: white;
  border: 0px;
  color: {{ $brand_color }};
  padding: 10px 15px;
  font-size: 15px;
  font-weight: 600;
  border-radius: 22px;
}

.title-btn:hover {
  background-color: #f7f7f7;
}

.title-btn.active {
  background-color: {{ $brand_color }};
  color: white;
}

.title-btn.active:hover {
  background-color: {{ $brand_hover }};
}

.custom-table {
  margin: 10px 3px;
}

.custom-table thead tr th {
  padding: 10px;
  background: {{ $brand_color }};
  color: white;
  text-align: center;
}

.custom-table thead tr th:first-child {
  border-top-left-radius: 10px
}

.custom-table thead tr th:last-child {
  border-top-right-radius: 10px
}

.custom-table tbody tr td {
  padding: 6px;
  text-align: center;
}

.custom-table thead tr:last-child{
  border-bottom-right-radius: 10px;
  border-bottom-left-radius: 10px;
}

/* .custom-table thead tr:last-child td:last-child{
  border-bottom-right-radius: 10px;
} */

.custom-table tbody tr:nth-child(odd) {
  background-color: #f2f2f2; /* Light grey background for odd rows */
}

.testimonial-item {
  padding: 0px !important;
  text-align: left;
  box-shadow: 2px 2px 7px #d6d6d6;
  margin-bottom: 27px;
}

.testimonial-item h3 {
  font-weight: 700;
  color: {{ $brand_color }};
}

.testimonial-item h4 {
  font-size: 13px;
  font-weight: 500;
  color: {{ $muted_color }};
}

.testimonial-img {
  width: 100%;
  height: 200px;
  border-radius: 0%;
  border: none;
  margin: 0;
}

.testimonial-item p {
  font-style: normal !important;
}

.form-section {
  transition: opacity 0.5s ease;
}

.track-item {
  padding: 10px !important;
  text-align: left;
  box-shadow: 2px 2px 7px #d6d6d6;
  margin-bottom: 27px;
}

.track-item h3 {
  font-weight: 700;
  font-size: 22px;
  color: {{ $brand_color }};
  margin-bottom: 5px !important;
}

.track-item h4 {
  font-size: 13px;
  font-weight: 500;
  color: {{ $muted_color }};
}

.track-img {
  width: 100%;
  height: 200px;
  border-radius: 0%;
  border: none;
  margin: 0;
}

.track-item p {
  font-style: normal !important;
  margin-bottom: 5px;
}

.service-slider {
  height: 500px;
}

.service-slider .swiper-slide{
  background-size: cover;
}

.service-detail {
  padding: 15px;
  min-height: 220px;
}

.service-detail h1 {
  font-weight: 600;
  font-size: 28px;
}

.service-detail h3 {
  font-size: 22px;
}

.service-subservices {
  background: #f4f4f4;
  padding: 14px;
  height: 220px;
}

.service-subservices h4 {
  font-size: 16px;
  font-weight: 600;
}

.service-subservices .subservice-list {
  height: auto;
  margin-top: 5px;
  margin-bottom: 5px;
}

.service-subservices .subservice-list .service-icon {
  height: 33px;
  width: 33px;
  margin: 5px;
  background-color: {{ $brand_color }};
  border-radius: 5px;
  padding: 7px;
  vertical-align: top;
}

.service-subservices .subservice-list label {
  max-width: 232px;
  vertical-align: -webkit-baseline-middle;
}

.service-more {
  padding: 9px;
}

.service-more button {
  width: 100%;
  border: 0px;
  padding: 9px;
  color: white;
  background: {{ $brand_color }};
  font-weight: 600;
}

.service-more button:hover {
  background: {{ $brand_hover }};
}

.detail-subservice {
  box-shadow: 2px 2px 2px gray;
  border: 0.5px solid #dedede;
  padding: 10px;
  border-radius: 5px;
  font-size: 13px;
  position: static;
  width: 500px;
  background-color: white;
  margin: 20px;
}

.detail-subservice-icon {
  padding-right: 5px;
  border-right: 0.5px solid #dedede;
}

.detail-subservice-icon img {
  width: 28px;
  height: 28px;
}

.detail-subservice-text {
  margin-left: 5px;
}

.detail-subservice-text label {
  font-weight: 700;
}

.detail-subservice-text p {
  margin-bottom: 10px;
}

#footer {
  background: url("{{ asset('webassets/img/gls/footer.jpg') }}");
  background-size: cover;
  background-position: center;
  color: white !important;
}

#price-form {
  margin-right: auto;
  margin-left: auto;
  padding: 13px;
  background-color: rgb(255, 255, 255);
  border-radius: 4px;
  box-shadow: 2px 2px 2px grey;
  text-align: left;
  color: black;
  border: 1px solid #dedede;
}

#price-form h4 {
  font-size: 21px;
  font-weight: 600;
}

.select2-box {
  border: 1px solid lightgrey;
  border-radius: 5px;
  padding: 6px
}

.select2-box .select2-container--default .select2-selection--single {
    border: none !important;
    /* border-radius: 4px; */
}

.select2-box .select2-container .select2-selection--single .select2-selection__rendered {
  padding-left: 0px;
}

.select2-box .select2-container {
  max-width: 100%;
}

.select2-box .form-control {
  border: 0;
  padding-left: 0px;
}

#price-form button {
  width: 100%;
  border: 0px;
  padding: 9px;
  color: white;
  background: {{ $brand_color }};
  font-weight: 600;
}

#price-form button {
  background-color: {{ $brand_hover }};
}

.price-card {
  display: block;
  border: 1px solid beige;
  margin: 10px;
  padding: 12px;
  background: {{ $brand_color }};
  color: white;
  border-radius: 10px;
}

.price-service {
  display: block;
  font-weight: 700;
  font-size: 25px;
}

.price-rate {
  display: block;
  font-weight: 500;
  font-size: 25px;
}

.price-leadtime {
  font-size: 14px;
}
.footer-nav {
  color: white;
  font-weight: 500;
  display: block;
}

/* ======= Why Choose Us ======= */
#why-us {
  padding: 60px 0;
}

#why-us .why-box {
  display: flex;
  align-items: center;
  background: #fff;
  border-left: 4px solid {{ $brand_color }};
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  padding: 20px 22px;
  margin-bottom: 24px;
  height: calc(100% - 24px);
  transition: transform .3s ease, box-shadow .3s ease;
}

#why-us .why-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

#why-us .why-box .why-check {
  color: {{ $second_color }};
  font-size: 22px;
  margin-right: 12px;
  flex-shrink: 0;
}

#why-us .why-box .why-icon {
  color: {{ $brand_color }};
  font-size: 30px;
  margin-right: 14px;
  flex-shrink: 0;
}

#why-us .why-box h4 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: {{ $text_dark }};
}

.co-service-list {
    display: inline-block;
    max-width: 243px;
    /* border: 2px solid #04b810; */
    margin: 0px 6px;
    padding: 7px 6px;
    border-radius: 8px;
    background-color: #81db87;
    color: white;
    font-weight: 600;
}

/* ---- About blocks (abouts table; several per site) ---- */
#about .about-block { align-items: center; margin-bottom: 44px; }
#about .about-block:last-child { margin-bottom: 0; }

#about .about-photo {
  height: 340px;
  border-radius: 14px;
  background-size: cover;
  background-position: center;
  background-color: #eef2f0;
  box-shadow: 0 6px 20px rgba(0, 0, 0, .12);
}

#about .about-photo-empty {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c4d2ca;
  font-size: 60px;
}

#about .about-subtitle {
  font-weight: 600;
  color: {{ $muted_color }};
  margin-bottom: 12px;
}

@media (max-width: 991px) {
  #about .about-photo { height: 240px; margin-bottom: 22px; }
  #about .about-block { margin-bottom: 30px; }
}

/* ======= Catalog (tabbed: service tabs, size tabs, no cart) ======= */
#products {
  padding: 60px 0 70px;
  background: #fff;
}

#products .cat-subtitle {
  max-width: 720px;
  margin: -6px auto 26px;
  text-align: center;
  color: {{ $muted_color }};
  font-size: 15px;
}

/* ---- service tabs ---- */
#products .cat-tabs {
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin: 0 0 24px;
  padding: 0;
}

#products .cat-tab {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border: 1px solid #e2e9e5;
  border-radius: 26px;
  background: #f6f9f7;
  color: {{ $text_dark }};
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s ease, border-color .2s ease, color .2s ease;
}

#products .cat-tab:hover { border-color: {{ $brand_color }}; }

#products .cat-tab.active {
  background: {{ $brand_color }};
  border-color: {{ $brand_color }};
  color: {{ $light_color }};
}

#products .cat-tab i { font-size: 16px; }

#products .cat-tab-count {
  min-width: 20px;
  padding: 1px 6px;
  border-radius: 10px;
  background: rgba(0, 0, 0, .07);
  font-size: 11px;
  font-weight: 700;
}

#products .cat-tab.active .cat-tab-count {
  background: rgba(255, 255, 255, .28);
}

#products .cat-service-desc {
  margin: 0 0 20px;
  font-size: 14px;
  color: {{ $muted_color }};
  line-height: 1.7;
}

/* ---- product rows ---- */
#products .cat-items {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

#products .cat-item {
  display: grid;
  grid-template-columns: 220px 1fr;
  background: #fff;
  border: 1px solid #e6ecea;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
  transition: box-shadow .3s ease, border-color .3s ease;
}

#products .cat-item:hover {
  border-color: {{ $brand_color }};
  box-shadow: 0 8px 22px rgba(0, 0, 0, .09);
}

#products .cat-media {
  background: #f4f7f5;
  min-height: 180px;
  display: flex;
}

#products .cat-media > img {
  width: 100%;
  height: 100%;
  min-height: 180px;
  object-fit: cover;
  display: block;
}

#products .cat-media-empty {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c4d2ca;
  font-size: 44px;
}

#products .cat-detail { padding: 18px 22px 20px; }

#products .cat-item-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 14px;
  flex-wrap: wrap;
}

#products .cat-item-head h4 {
  margin: 0 0 2px;
  font-size: 18px;
  font-weight: 700;
  color: {{ $text_dark }};
}

#products .cat-code {
  font-size: 11px;
  letter-spacing: .05em;
  text-transform: uppercase;
  color: {{ $muted_color }};
}

#products .cat-price { text-align: right; white-space: nowrap; }

#products .cat-price-label {
  display: block;
  margin-bottom: -1px;
  font-size: 11px;
  font-weight: 700;
  line-height: 1.15;
  letter-spacing: .04em;
  text-transform: uppercase;
  color: {{ $text_dark }};
}

/* The variant panel is a flex row, so the label needs its own line above
   the price rather than sitting beside it. */
#products .cat-vpanel .cat-price-label { flex: 0 0 100%; }

#products .cat-price-value {
  font-size: 17px;
  font-weight: 700;
  color: {{ $second_color }};
}

#products .cat-price-ask {
  font-size: 15px;
  font-weight: 700;
  color: {{ $brand_color }};
}

#products .cat-desc {
  margin: 10px 0 0;
  font-size: 14px;
  color: #55625b;
  line-height: 1.65;
}

/* ---- size tabs inside a product ---- */
#products .cat-sizes {
  margin-top: 14px;
  padding: 12px 14px;
  border: 1px solid #eef2f0;
  border-radius: 10px;
  background: #fafcfb;
}

#products .cat-vtabs {
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin: 0 0 10px;
  padding: 0;
}

#products .cat-vtab {
  padding: 5px 14px;
  border: 1px solid #dde5e0;
  border-radius: 20px;
  background: #fff;
  color: {{ $text_dark }};
  font-family: inherit;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s ease, border-color .2s ease, color .2s ease;
}

#products .cat-vtab:hover { border-color: {{ $brand_color }}; }

#products .cat-vtab.active {
  background: {{ $brand_color }};
  border-color: {{ $brand_color }};
  color: {{ $light_color }};
}

#products .cat-vpanel {
  display: flex;
  align-items: baseline;
  /* row-gap 0: the label sits tight above the price it labels, while the
     10px still separates the price from the size description beside it. */
  gap: 0 10px;
  flex-wrap: wrap;
}

#products .cat-vprice {
  font-size: 16px;
  font-weight: 700;
  color: {{ $second_color }};
}

#products .cat-vpanel small { font-size: 12px; color: {{ $muted_color }}; }

/* ---- specifications + figures, compact ---- */
#products .cat-specs,
#products .cat-facts {
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  gap: 6px 18px;
  margin: 12px 0 0;
  padding: 0;
  font-size: 13px;
  color: {{ $text_dark }};
}

#products .cat-specs li span {
  color: {{ $muted_color }};
  font-weight: 600;
  margin-right: 4px;
}

#products .cat-facts { color: {{ $muted_color }}; }
#products .cat-facts i { color: {{ $brand_color }}; margin-right: 5px; }

/* ---- ask, do not order ---- */
#products .cat-note {
  margin: 16px 0 0;
  font-size: 12px;
  font-style: italic;
  color: {{ $muted_color }};
}

#products .cat-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 10px;
}

#products .cat-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border: 2px solid {{ $brand_color }};
  border-radius: 22px;
  background: {{ $brand_color }};
  color: {{ $light_color }};
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: background .25s ease, color .25s ease;
}

#products .cat-btn:hover { background: {{ $brand_hover }}; color: {{ $light_color }}; }

#products .cat-btn-wa { background: transparent; color: {{ $brand_color }}; }
#products .cat-btn-wa:hover { background: {{ $brand_color }}; color: {{ $light_color }}; }

#products .cat-empty {
  margin: 0;
  padding: 26px;
  border: 1px dashed #dbe4de;
  border-radius: 12px;
  text-align: center;
  font-size: 14px;
  color: {{ $muted_color }};
  background: #fafcfb;
}

#products .cat-empty i { color: {{ $brand_color }}; margin-right: 6px; }

@media (max-width: 767px) {
  #products .cat-item { grid-template-columns: 1fr; }
  #products .cat-media, #products .cat-media > img { min-height: 170px; }
  #products .cat-detail { padding: 16px 16px 18px; }
  #products .cat-item-head { flex-direction: column; }
  #products .cat-price { text-align: left; }
  #products .cat-tab { padding: 8px 14px; font-size: 13px; }
}

/* ======= Packages / Pricing ======= */
#packages {
  padding: 60px 0 70px;
  background: #f7fbf8;
}

/* service tabs, same shape as the catalog's */
#packages .pkg-tabs {
  list-style: none;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
  margin: 0 0 30px;
  padding: 0;
}

#packages .pkg-tab {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border: 1px solid #e2e9e5;
  border-radius: 26px;
  background: #fff;
  color: {{ $text_dark }};
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background .2s ease, border-color .2s ease, color .2s ease;
}

#packages .pkg-tab:hover { border-color: {{ $brand_color }}; }

#packages .pkg-tab.active {
  background: {{ $brand_color }};
  border-color: {{ $brand_color }};
  color: {{ $light_color }};
}

#packages .pkg-tab i { font-size: 16px; }

#packages .pkg-tab-count {
  min-width: 20px;
  padding: 1px 6px;
  border-radius: 10px;
  background: rgba(0, 0, 0, .07);
  font-size: 11px;
  font-weight: 700;
}

#packages .pkg-tab.active .pkg-tab-count { background: rgba(255, 255, 255, .28); }

#packages .pkg-subtitle {
  max-width: 720px;
  margin: -6px auto 40px;
  text-align: center;
  color: {{ $muted_color }};
  font-size: 15px;
}

#packages .pkg-row {
  align-items: stretch;
}

#packages .pkg-card {
  position: relative;
  display: flex;
  flex-direction: column;
  height: calc(100% - 30px);
  margin-bottom: 30px;
  padding: 26px 24px 28px;
  background: #fff;
  border: 1px solid #e6ecea;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}

#packages .pkg-card:hover {
  transform: translateY(-6px);
  border-color: {{ $brand_color }};
  box-shadow: 0 12px 26px rgba(0, 0, 0, 0.12);
}

#packages .pkg-card.pkg-popular {
  border: 2px solid {{ $brand_color }};
  box-shadow: 0 6px 22px rgba(64, 192, 87, 0.25);
}

#packages .pkg-badge {
  position: absolute;
  top: -14px;
  left: 50%;
  transform: translateX(-50%);
  padding: 5px 16px;
  border-radius: 20px;
  background: {{ $button_gradient }};
  color: {{ $light_color }};
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1px;
  white-space: nowrap;
}

/* The card body: image on one side, price + contents on the other. It grows
   so the ask button below it can sit at the bottom of the card. */
#packages .pkg-body {
  flex: 1;
  margin: 0 0 18px;
}

#packages .pkg-media {
  margin-bottom: 18px;
  padding: 0;
}

#packages .pkg-media img {
  width: 100%;
  /* Tall enough to read on a phone, where it spans the card's full width. */
  height: 220px;
  /* contain: show the whole product, letterboxed rather than cropped. */
  object-fit: contain;
  display: block;
  border: 1px solid #eef2f0;
  border-radius: 10px;
  background: #f4f7f5;
}

@media (min-width: 768px) {
  #packages .pkg-media {
    /* Beside the text now, so it can take the height the contents give it. */
    margin-bottom: 0;
    padding-right: 4px;
  }

  #packages .pkg-media img {
    /* The column stretches to the row's height, so the image fills it and
       object-fit keeps the whole product visible, centred. */
    height: 100%;
    min-height: 260px;
  }
}

#packages .pkg-head {
  margin-bottom: 14px;
}

#packages .pkg-name {
  margin: 0 0 4px;
  font-size: 20px;
  font-weight: 700;
  color: {{ $text_dark }};
}

#packages .pkg-tagline {
  margin: 0;
  font-size: 13px;
  color: {{ $muted_color }};
}

#packages .pkg-price-wrap {
  margin-bottom: 18px;
}

#packages .pkg-old {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
}

#packages .pkg-old-price {
  font-size: 14px;
  color: {{ $muted_color }};
  text-decoration: line-through;
}

#packages .pkg-discount {
  padding: 2px 8px;
  border-radius: 4px;
  background: #ffe3e3;
  color: #e03131;
  font-size: 12px;
  font-weight: 700;
}

#packages .pkg-price-label {
  display: block;
  margin-bottom: -2px;
  font-size: 11px;
  font-weight: 700;
  line-height: 1.15;
  letter-spacing: .04em;
  text-transform: uppercase;
  color: {{ $text_dark }};
}

#packages .pkg-price {
  display: flex;
  margin-bottom: 16px;
  align-items: baseline;
  flex-wrap: wrap;
  gap: 4px;
  color: {{ $text_dark }};
}

#packages .pkg-currency {
  font-size: 16px;
  font-weight: 600;
}

#packages .pkg-amount {
  font-size: 32px;
  font-weight: 700;
  line-height: 1.1;
}

#packages .pkg-note {
  margin: 6px 0 0;
  font-size: 12px;
  font-style: italic;
  color: {{ $muted_color }};
}

#packages .pkg-ask {
  font-size: 24px;
  font-weight: 700;
  line-height: 1.1;
  color: {{ $brand_color }};
}

#packages .pkg-period {
  font-size: 13px;
  color: {{ $muted_color }};
}

#packages .pkg-btn {
  display: block;
  width: 100%;
  padding: 12px 10px;
  border: 2px solid {{ $brand_color }};
  border-radius: 10px;
  background: {{ $brand_color }}bb;
  color: {{ $light_color }};
  font-size: 14px;
  font-weight: 600;
  text-align: center;
  text-transform: uppercase;
  text-decoration: none;
  transition: background .3s ease;
}

/* the ask button is the card's last child: push it to the bottom so the
   buttons line up across cards of different heights */
#packages .pkg-card > .pkg-btn { margin-top: auto; }

#packages .pkg-btn i { margin-right: 6px; }

#packages .pkg-btn:hover {
  background: {{ $brand_hover }};
}

#packages .pkg-card.pkg-popular .pkg-btn {
  background: {{ $button_gradient }};
  border-color: transparent;
}

#packages .pkg-note {
  margin: 12px 0 0;
  font-size: 12px;
  color: {{ $muted_color }};
  text-align: center;
}

#packages .pkg-features {
  list-style: none;
  margin: 16px 0 0;
  padding: 16px 0 0;
  border-top: 1px solid #eef2f0;
}

#packages .pkg-features li {
  display: flex;
  align-items: flex-start;
  gap: 9px;
  margin-bottom: 11px;
  font-size: 14px;
  color: {{ $text_dark }};
}

#packages .pkg-feature-body { min-width: 0; }

/* the product's specifications, nested under its line */
#packages .pkg-specs {
  list-style: none;
  margin: 6px 0 0;
  padding: 0 0 0 10px;
  border-left: 2px solid #eef2f0;
}

#packages .pkg-features .pkg-specs li {
  display: block;
  margin-bottom: 3px;
  font-size: 12px;
  line-height: 1.5;
  color: {{ $muted_color }};
}

#packages .pkg-specs li span {
  color: {{ $text_dark }};
  font-weight: 600;
}

#packages .pkg-specs li span::after { content: ':'; }

#packages .pkg-features li i {
  flex-shrink: 0;
  margin-top: 3px;
  color: {{ $brand_color }};
  font-size: 15px;
}

#packages .pkg-guarantee {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 14px 34px;
  margin-top: 26px;
  font-size: 14px;
  color: {{ $text_dark }};
}

#packages .pkg-guarantee i {
  margin-right: 7px;
  color: {{ $brand_color }};
}

@media (max-width: 991px) {
  #packages .pkg-card {
    height: calc(100% - 30px);
  }
}

@media (max-width: 767px) {
  #packages .pkg-amount {
    font-size: 28px;
  }

  #packages .pkg-media {
    height: 150px;
  }
}

</style>
