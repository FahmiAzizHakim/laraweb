{{-- Shown only when Website > Sections has this section on the page. --}}
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
          <a href="{{ url('/'.$site.'/content/'.$content->id) }}" style="text-decoration:none;color:inherit;">
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
</section>
<!-- End Testimonials Section -->
