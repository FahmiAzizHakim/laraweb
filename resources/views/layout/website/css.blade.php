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
    $header_gradient    = $styles['header_gradient']    ?? 'linear-gradient(90deg, rgb(0 199 39) 0%, rgb(0 214 55), rgb(0 152 215) 100%)';
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
  color: #50d8af;
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

#topbar .contact-info i {
  font-style: normal;
  color: {{ $brand_color }};
}

#topbar .contact-info i a,
#topbar .contact-info i span {
  padding-left: 5px;
  color: #444;
}

#topbar .contact-info i a {
  line-height: 0;
  transition: 0.3s;
}

#topbar .contact-info i a:hover {
  color: {{ $brand_hover }};
}

#topbar .social-links a {
  color: #555;
  padding: 0 15px;
  display: inline-block;
  line-height: 1px;
  border-left: 1px solid #e9e9e9;
}

#topbar .social-links a:hover {
  color: #50d8af;
}

#hero {
  height: {{ $hero_height }} !important;
}

#hero .hero-slider {
  height: {{ $hero_slider_height }};
}

#hero .hero-slider::before {
  content: "";
  background: linear-gradient(90deg, rgba(28,28,28,0.9) 0%, rgba(36,104,15,0.7) 66%, rgba(45,187,0,0.7) 100%);
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  z-index: 7;
}

#header {
  /* background: linear-gradient(90deg, rgba(40,166,1,1) 0%, rgba(45,187,0,1) 100%); */
  background: {{ $header_gradient }};
    /* background: ; */
  /* background-color: #40c057 !important; */
}

.back-to-top {
  background-color: {{ $brand_color }} !important;
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

#tracking {
  margin-top: -75px;
}

.tracking-box {
  margin-right: auto;
  margin-left: auto;
  padding: 13px;
  background-color: rgb(255, 255, 255);
  border-radius: 4px;
  box-shadow: 2px 2px 2px beige;
  text-align: left;
  color: black;
  position: relative; /* Ensures z-index is applied */
  z-index: 10; /* Adjust the value as needed */
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

.services-sec {
  margin-left: auto;
  margin-right: auto;
  max-width: {{ $container_width }};
  border: 0.5px solid #e8e8e8;
  box-shadow: 2px 2px 2px gray;
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
  color: #777;
  margin-bottom: 12px;
}

@media (max-width: 991px) {
  #about .about-photo { height: 240px; margin-bottom: 22px; }
  #about .about-block { margin-bottom: 30px; }
}

</style>
