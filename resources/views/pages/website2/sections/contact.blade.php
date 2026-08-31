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
      <form action="{{ route($site.'.contact') }}" method="post" role="form">
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
