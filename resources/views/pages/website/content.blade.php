@extends('layout.website.main')

@section('main')
<section id="content-detail" class="py-5" style="margin-top:120px;">
  <div class="container" data-aos="fade-up">
    <div class="row justify-content-center">
      <div class="col-lg-9">

        <a href="{{ route('home') }}#articles" class="logistic-btn mb-4 d-inline-block" style="text-decoration:none;">
          <i class="bi bi-arrow-left"></i> Back to News &amp; Articles
        </a>

        <h1 class="bold mt-3" style="font-weight:700;">{{ $content->content_title }}</h1>

        @if($content->content_subtitle)
          <h4 class="main-color">{{ $content->content_subtitle }}</h4>
        @endif

        <p class="text-muted mb-4">
          <i class="bi bi-calendar3"></i>
          {{ optional($content->created_at)->format('d M Y') }}
          @if($content->reference)
            &nbsp;|&nbsp; <i class="bi bi-person"></i> {{ $content->reference }}
          @endif
        </p>

        @if($content->media)
          <img src="{{ asset($content->media) }}" alt="{{ $content->content_title }}"
               class="img-fluid mb-4" style="width:100%;max-height:500px;object-fit:cover;border-radius:8px;box-shadow:2px 2px 12px rgba(0,0,0,0.12);">
        @endif

        <div class="content-body" style="font-size:16px;line-height:1.8;">
          @if($content->content)
            {!! $content->content !!}
          @else
            <p>{{ $content->description }}</p>
          @endif
        </div>

        @if($content->additional_url)
          <div class="mt-4">
            <a href="{{ $content->additional_url }}" target="_blank" rel="noopener" class="logistic-btn" style="text-decoration:none;">
              Read More <i class="bi bi-box-arrow-up-right"></i>
            </a>
          </div>
        @endif

      </div>
    </div>
  </div>
</section>
@endsection
