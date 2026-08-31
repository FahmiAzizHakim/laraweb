@extends('layout.website.hero')
@section('content')

<section id="tracking">
  <div class="tracking-box col-md-5">
    {{-- <div class="track-title mb-2"> --}}
      {{-- <button id="price-btn"class="title-btn col-md-6">Check Price</button> --}}
    {{-- </div> --}}
    <div id="track-form" class="form-section row">
      <div class="input-group custom-input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <img src="{{ asset('webassets/img/gls/icons/icons8-search-64.png') }}" alt="Search Icon">
          </span>
        </div>
        <input type="text" class="form-control" id="cn_no" placeholder="Track Your Shipment">
        <div class="input-group-append">
            <button id="track-button" class="btn" type="button">Track</button>
        </div>
      </div>
      {{-- <div class="text-center">
        <a href="">
          <img src="{{asset('webassets/img/gls/icons/ship-loc-green.png')}}"/>
          <p>Check Our Prices</p>
        </a>
      </div> --}}
    </div>
    {{-- <div id="price-form" class="form-section" style="display: none;">
      <select class="form-control select-form my-2" placeholder="From">
        <option value="">From</option>
        <option value="">Jakarta</option>
        <option value="">Bandung</option>
        <option value="">Jogjakarta</option>
        <option value="">Surabaya</option>
      </select>
      <select class="form-control select-form my-2" placeholder="To">
        <option value="">To</option>
        <option value="">Medan</option>
        <option value="">Palembang</option>
        <option value="">Batam</option>
        <option value="">Balikpapan</option>
      </select>
      <input type="number" class="form-control my-2" placeholder="Weight"/>
      <button class="logistic-btn">Check</button>
    </div> --}}
  </div>

</section>

<!-- ======= Price Section ======= -->
<section id="price">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Check Price</h2>
    </div>
    {{-- <div class="track-title mb-2"> --}}
      {{-- <button id="price-btn"class="title-btn col-md-6">Check Price</button> --}}
    {{-- </div> --}}
    <div id="price-form" class="form-section row">
      <div class="col-md-12 my-2">
        <h4 class="my-2">From</h4>
        <div class="row">
          <div class="col-md-4">
            <div class="select2-box">
              <label>Province<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="from_province"></select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="select2-box">
              <label>City<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="from_city"></select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="select2-box">
              <label>Origin Address<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="origin"></select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 my-2">
        <h4 class="my-2">To</h4>
        <div class="row">
          <div class="col-md-4">
            <div class="select2-box">
              <label>Province<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="to_province"></select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="select2-box">
              <label>City<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="to_city"></select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="select2-box">
              <label>Destination Address<span class="red-color">*</span></label>
              <select class="form-control form-select2" id="destination"></select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 my-2">
        <h4 class="my-2">Weight</h4>
        <div class="row">
          <div class="col-md-4">
            <div class="select2-box">
              <label>Shipment Weight (Kg)<span class="red-color">*</span></label>
              <input type="number" class="form-control" id="weight" placeholder="Weight(Kg)">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <button id="fetch-price">Check Price</button>
      </div>
      
      <div class="row" id="price-result">
      </div>
      
    </div>
  </div>

</section>

<!-- ======= Services Section ======= -->
<section id="services">
  <div class="container" data-aos="fade-up">
    <div class="services-sec row">
      <div class="col-md-6 p-0">
        <div class="service-slider swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/transportation.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/white-van2.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/container-port.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/white-van.jpg') }}');"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6 p-0">
        <div class="service-detail">
          <h1>Cargo Shipping</h1>
          <h3>For All Shippers</h3>
          <p>Discover shipping and logistics service options from GLS Cargo Services</p>
        </div>
        <div class="service-subservices">
          <h4>Service Available</h4>
          <div class="row">
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/plane-white2.png')}}"></img>
              <label>Air Freight</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/truck-white2.png')}}"></img>
              <label>Road Freight</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/boat-white2.png')}}"></img>
              <label>Ocean Freight</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/train-white2.png')}}"></img>
              <label>Rail Freight</label>
            </div>
          </div>
        </div>
        <div class="service-more">
          <a href="{{url('/service')}}"><button>Explore GLS Cargo Services</button></a>
        </div>
      </div>
    </div>
  </div>
</section><!-- End Services Section -->

<section id="services">
  <div class="container" data-aos="fade-up">
    <div class="services-sec row">
      <div class="col-md-6 p-0">
        <div class="service-detail">
          <h1>GLS Logistics Services</h1>
          <h3>Business Only</h3>
          <p>Find out how GLS Supply chain can revolutionize your business as a 3PL provider.</p>
        </div>
        <div class="service-subservices">
          <h4>Service Available</h4>
          <div class="row">
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/plane-white2.png')}}"></img>
              <label>Fulfillment by GLS (B2C)</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/truck-white2.png')}}"></img>
              <label>Warehouse management Services</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/boat-white2.png')}}"></img>
              <label>VAS (Value Added Services)</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/train-white2.png')}}"></img>
              <label>Consolidation Center</label>
            </div>
            <div class="col-md-6 subservice-list">
              <img class="service-icon" src="{{ asset('webassets/img/gls/icons/train-white2.png')}}"></img>
              <label>Multi Users Facility warehouse</label>
            </div>
          </div>
        </div>
        <div class="service-more">
          <a href="{{url('/service')}}"><button>Explore GLS Supply Chain</button></a>
        </div>
      </div>
      <div class="col-md-6 p-0">
        <div class="service-slider swiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/1.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/2.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/3.jpeg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/4.jpeg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/5.jpeg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/6.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/7.jpg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/8.jpeg') }}');"></div>
            <div class="swiper-slide" style="background-image: url('{{ asset('webassets/img/gls/warehouse/9.jpg') }}');"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- End Services Section -->

<!-- ======= About Section ======= -->
{{-- Driven by the abouts table (Website > About). The first row is the site's
     main block; any further rows render underneath it, alternating sides. --}}
@php $aboutBlocks = ($abouts ?? collect()); @endphp

@if($aboutBlocks->count())
<section id="about">
  <div class="container" data-aos="fade-up">
    @foreach($aboutBlocks as $about)
      <div class="row about-block {{ $loop->index % 2 ? 'flex-lg-row-reverse' : '' }}">
        <div class="col-lg-6 about-img">
          @if($about->about_image)
            <div class="about-photo" style="background-image: url('{{ asset($about->about_image) }}');"></div>
          @else
            <div class="about-photo about-photo-empty"><i class="bi bi-buildings"></i></div>
          @endif
        </div>

        <div class="col-lg-6 content">
          <h2 class="second-color">{{ $about->about_title }}</h2>
          @if($about->about_subtitle)
            <p class="about-subtitle">{{ $about->about_subtitle }}</p>
          @endif
          @foreach($about->paragraphs as $paragraph)
            <p>{{ $paragraph }}</p>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</section><!-- End About Section -->
@endif

<!-- ======= Clients Section ======= -->
<section id="clients">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Clients</h2>
    </div>

    <div class="clients-slider swiper" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper-wrapper align-items-center">
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/globalunion2.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/kopikren.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/techmandala.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/Donggi-Senoro.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/bukabangunan-raw.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/logo-dekoruma.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/shopee.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/tokopedia.png') }}" class="img-fluid" height="30" alt=""></div>
        <div class="swiper-slide"><img src="{{ asset('webassets/img/gls/clients/swi-jetty.png') }}" class="img-fluid" height="30" alt=""></div>
      </div>
      <div class="swiper-pagination"></div>
    </div>


  </div>
</section><!-- End Clients Section -->

<!-- ======= Testimonials Section ======= -->
<section id="articles">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>News And Articles</h2>
    </div>

    <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper-wrapper">

        @forelse(($contents ?? collect()) as $content)
        <div class="swiper-slide">
          <a href="{{ route('content.read', $content->id) }}" style="text-decoration:none;color:inherit;">
          <div class="testimonial-item">
            <img src="{{ $content->media ? asset($content->media) : asset('webassets/img/gls/hero1.jpg') }}" class="testimonial-img" alt="{{ $content->content_title }}">
            <div class="p-3">
              <h3>{{ \Illuminate\Support\Str::limit($content->content_title, 60) }}</h3>
              <p>{{ \Illuminate\Support\Str::limit(strip_tags($content->description ?: $content->content), 120) }}</p>
              <h4>{{ optional($content->created_at)->format('d M Y') }}</h4>
            </div>
          </div>
          </a>
        </div><!-- End testimonial item -->
        @empty
        <div class="swiper-slide">
          <div class="testimonial-item">
            <img src="{{ asset('webassets/img/gls/hero1.jpg') }}" class="testimonial-img" alt="">
            <div class="p-3">
              <h3>Coming Soon</h3>
              <p>News and articles will appear here.</p>
            </div>
          </div>
        </div><!-- End testimonial item -->
        @endforelse

      </div>
      <div class="swiper-pagination"></div>
    </div>

  </div>
</section><!-- End Testimonials Section -->

<!-- ======= Contact Section ======= -->
<section id="contact">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Contact Us</h2>
    </div>

    <div class="row contact-info">

      <div class="col-md-4">
        <div class="contact-address">
          <i class="bi bi-geo-alt"></i>
          <h3>Address</h3>
          <address>{{ web_property('address') }}</address>
        </div>
      </div>

      <div class="col-md-4">
        <div class="contact-phone">
          <i class="bi bi-phone"></i>
          <h3>Phone Number</h3>
          <p><a href="tel:{{ str_replace(' ', '', web_property('phone_number')) }}">{{ web_property('phone_number') }}</a></p>
        </div>
      </div>

      <div class="col-md-4">
        <div class="contact-email">
          <i class="bi bi-envelope"></i>
          <h3>Email</h3>
          <p><a href="mailto:{{ web_property('email') }}">{{ web_property('email') }}</a></p>
        </div>
      </div>

    </div>
  </div>

  <div class="container mb-4">
    @php
      $location = web_property('location', '-6.239005,106.907119');
      [$lat, $lng] = array_pad(explode(',', $location), 2, '');
      $lat = trim($lat) ?: '-6.239005';
      $lng = trim($lng) ?: '106.907119';
    @endphp
    <iframe src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&z=16&hl=en&output=embed" width="100%" height="380" frameborder="0" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>

  <div class="container">
    <div class="form">
      <form action="{{ route('contact.send') }}" method="post" role="form">
        @csrf
        <div class="row">
          <div class="form-group col-md-6">
            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" value="{{ old('name') }}" required>
          </div>
          <div class="form-group col-md-6 mt-3 mt-md-0">
            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" value="{{ old('email') }}" required>
          </div>
        </div>
        <div class="form-group mt-3">
          <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" value="{{ old('subject') }}" required>
        </div>
        <div class="form-group mt-3">
          <textarea class="form-control" name="message" rows="5" placeholder="Message" required>{{ old('message') }}</textarea>
        </div>

        <div class="my-3">
          @if(session('message_sent'))
            <div class="sent-message" style="display:block;">{{ session('message_sent') }}</div>
          @endif
          @if($errors->any())
            <div class="error-message" style="display:block;">
              @foreach($errors->all() as $error){{ $error }}<br>@endforeach
            </div>
          @endif
        </div>

        <div class="text-center"><button type="submit">Send Message</button></div>
      </form>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection

@section('script')
<script>
  $(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#from_province').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/provinces', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.PROVINCE_NAME, // Use 'PROVINCE_NAME' as the id
                text: item.PROVINCE_NAME // Display 'PROVINCE_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a province',
      minimumInputLength: 4,
    });

    $('#from_city').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/cities', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#from_province").val(), // Use 'name' as the search term parameter
            city: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CITY_NAME, // Use 'CITY_NAME' as the id
                text: item.CITY_NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a city',
      minimumInputLength: 4,
    });

    $('#origin').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/geo', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#from_province").val(), // Use 'name' as the search term parameter
            city: $("#from_city").val(), // Use 'name' as the search term parameter
            name: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CODE, // Use 'CITY_NAME' as the id
                text: item.NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for Address',
      minimumInputLength: 4,
    }); 


    $('#to_province').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/provinces', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.PROVINCE_NAME, // Use 'PROVINCE_NAME' as the id
                text: item.PROVINCE_NAME // Display 'PROVINCE_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a province',
      minimumInputLength: 4,
    });

    $('#to_city').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/cities', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#to_province").val(), // Use 'name' as the search term parameter
            city: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CITY_NAME, // Use 'CITY_NAME' as the id
                text: item.CITY_NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for a city',
      minimumInputLength: 4,
    });

    $('#destination').select2({
      ajax: {
        type: 'POST', // Use POST method
        url: '/geo', // Replace with your API endpoint
        dataType: 'json',
        delay: 250,
        headers: {
          'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
        },
        data: function (params) {
          return {
            prov: $("#to_province").val(), // Use 'name' as the search term parameter
            city: $("#to_city").val(), // Use 'name' as the search term parameter
            name: params.term, // Use 'name' as the search term parameter
          };
        },
        processResults: function (data) {
          return {
            results: $.map(data.data, function (item) {
              return {
                id: item.CODE, // Use 'CITY_NAME' as the id
                text: item.NAME // Display 'CITY_NAME' as the text
              };
            })
          };
        },
        cache: true
      },
      placeholder: 'Search for Address',
      minimumInputLength: 4,
    }); 

    function fetchPriceData() {
      $('#price-result').empty();
      $.ajax({
        url: '/rates', // Your Laravel endpoint
        method: 'POST',
        contentType: 'application/json',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: JSON.stringify({
          from: $("#origin").val(), // Use 'name' as the search term parameter
          to: $("#destination").val(), // Use 'name' as the search term parameter
          weight: $("#weight").val(), // Use 'name' as the search term parameter
        }),
        success: function(response) {
          $('#price-result').empty(); // Clear existing results
          if(response.data && response.data != undefined) {
            let data = response.data
            // Assume the response is an array of price data
            // Function to format the rate as IDR currency
            function formatCurrency(rate) {
              return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
              }).format(rate);
            }

            // Assume the response is an array of price data
            data.forEach(function(item) {
              var priceHtml = `
                <div class="col-md-4 col-sm-12 price-tag">
                  <div class="price-card">
                    <label class="price-service">${item.service_name}</label>
                    <label class="price-rate">${formatCurrency(item.rate)}</label>
                    <label class="price-leadtime">estimate: ${item.leadtime} days</label>
                  </div>
                </div>
              `;
              $('#price-result').append(priceHtml);
            });
          } else {
            alert("Price not Available in System")
          }
        },
        error: function(xhr, status, error) {
          alert("price not found")
        }
      });
    }

      $('#fetch-price').click(function() {
        fetchPriceData();
      });

      $('#track-button').click(function() {
        var u_id = $("#cn_no").val(); // Get the user ID from data attribute
        var url = '/tracking?cn_no=' + u_id; // Construct the URL

        // Open a new tab and focus on it
        var newTab = window.open(url, '_blank');
        newTab.focus();
      });
  });
</script>
@endsection