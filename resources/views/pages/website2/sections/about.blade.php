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
