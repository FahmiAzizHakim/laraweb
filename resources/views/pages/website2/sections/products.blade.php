<!-- ======= Catalog Section ======= -->
{{-- The full catalog, kept compact: one tab per service, and each product's
     sizes as tabs inside its own card. No cart or checkout here -- this is a
     catalog, the customer gets in touch instead. --}}
<section id="products" class="products">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Produk &amp; Layanan</h2>
    </div>
    <p class="cat-subtitle">Pilih layanan untuk melihat rincian produknya. Hubungi kami untuk penawaran sesuai kebutuhan Anda.</p>

    @php
      $catalog  = $products ?? collect();
      $catSvcs  = ($services ?? collect())->values();
      $firstSvc = optional($catSvcs->first())->id;
    @endphp

    @if($catSvcs->count())
      {{-- ---------- service tabs ---------- --}}
      <ul class="cat-tabs">
        @foreach($catSvcs as $svc)
          @php $svcCount = $catalog->get($svc->id, collect())->count(); @endphp
          <li>
            <button type="button" class="cat-tab {{ $svc->id === $firstSvc ? 'active' : '' }}"
                    data-cat-panel="cat-svc-{{ $svc->id }}">
              <i class="bi {{ $svc->service_icon ?: 'bi-box-seam' }}"></i>
              {{ $svc->service_name }}
              <span class="cat-tab-count">{{ $svcCount ?: '–' }}</span>
            </button>
          </li>
        @endforeach
      </ul>

      {{-- ---------- one panel per service ---------- --}}
      @foreach($catSvcs as $svc)
        @php $svcProducts = $catalog->get($svc->id, collect()); @endphp

        <div class="cat-panel {{ $svc->id === $firstSvc ? '' : 'd-none' }}" id="cat-svc-{{ $svc->id }}">

          @if($svc->service_description)
            <p class="cat-service-desc">{{ $svc->service_description }}</p>
          @endif

          @if($svcProducts->count())
            <div class="cat-items">
              @foreach($svcProducts as $product)
                @php
                  $image    = $product->images->first();
                  $variants = $product->variants;
                  $specs    = $product->specifications;
                  $prices   = $variants->map(fn ($v) => !is_null($v->variant_price) ? (float) $v->variant_price : (float) $product->products_price);
                  $minPrice = $prices->count() ? $prices->min() : (float) $product->products_price;
                  $maxPrice = $prices->count() ? $prices->max() : (float) $product->products_price;
                  $dims     = array_filter([$product->products_length, $product->products_width, $product->products_height]);
                @endphp

                <article class="cat-item">

                  {{-- image, or an icon placeholder when none is uploaded --}}
                  <div class="cat-media">
                    @if($image)
                      <img src="{{ asset($image->image_url) }}" alt="{{ $image->image_description ?: $product->products_name }}" loading="lazy">
                    @else
                      <div class="cat-media-empty"><i class="bi {{ $svc->service_icon ?: 'bi-image' }}"></i></div>
                    @endif
                  </div>

                  <div class="cat-detail">
                    <div class="cat-item-head">
                      <div class="cat-item-id">
                        <h4>{{ $product->products_name }}</h4>
                        <span class="cat-code">{{ $product->products_code }}</span>
                      </div>
                      <div class="cat-price">
                        @if($maxPrice > 0)
                          <span class="cat-price-label">Mulai Dari</span>
                          <span class="cat-price-value">
                            Rp {{ number_format($minPrice, 0, ',', '.') }}@if($maxPrice > $minPrice) &ndash; {{ number_format($maxPrice, 0, ',', '.') }}@endif
                          </span>
                        @else
                          <span class="cat-price-ask">Hubungi kami</span>
                        @endif
                      </div>
                    </div>

                    @if($product->products_description)
                      <p class="cat-desc">{{ $product->products_description }}</p>
                    @endif

                    {{-- sizes as tabs: one pill per variant, one line of detail --}}
                    @if($variants->count())
                      <div class="cat-sizes">
                        <ul class="cat-vtabs">
                          @foreach($variants as $i => $v)
                            <li>
                              <button type="button" class="cat-vtab {{ $i === 0 ? 'active' : '' }}"
                                      data-cat-panel="var-{{ $product->id }}-{{ $v->id }}">
                                {{ $v->variant_name }}
                              </button>
                            </li>
                          @endforeach
                        </ul>

                        @foreach($variants as $i => $v)
                          @php $vPrice = !is_null($v->variant_price) ? (float) $v->variant_price : (float) $product->products_price; @endphp
                          <div class="cat-vpanel cat-panel {{ $i === 0 ? '' : 'd-none' }}" id="var-{{ $product->id }}-{{ $v->id }}">
                            @if($vPrice > 0)
                              <span class="cat-price-label">Mulai Dari</span>
                            @endif
                            <span class="cat-vprice">{{ $vPrice > 0 ? 'Rp ' . number_format($vPrice, 0, ',', '.') : 'Hubungi kami' }}</span>
                            @if($v->variant_description)<small>{{ $v->variant_description }}</small>@endif
                          </div>
                        @endforeach
                      </div>
                    @endif

                    {{-- specifications + physical figures, on one compact line each --}}
                    @if($specs->count())
                      <ul class="cat-specs">
                        @foreach($specs as $spec)
                          <li><span>{{ $spec->attribute }}</span> {{ $spec->value }}</li>
                        @endforeach
                      </ul>
                    @endif

                    @if($product->products_weight || count($dims) === 3)
                      <ul class="cat-facts">
                        @if($product->products_weight)
                          <li><i class="bi bi-box"></i> {{ number_format($product->products_weight, 0, ',', '.') }} gr</li>
                        @endif
                        @if(count($dims) === 3)
                          <li><i class="bi bi-bounding-box"></i> {{ $product->products_length }} &times; {{ $product->products_width }} &times; {{ $product->products_height }} cm</li>
                        @endif
                      </ul>
                    @endif

                    {{-- Prices are indicative: the final figure depends on the
                         survey, so say so before the buttons. --}}
                    @if($maxPrice > 0)
                      <p class="cat-note">Harga tersebut hanya untuk estimasi &amp; dapat berubah</p>
                    @endif

                    {{-- catalog only: ask, do not order --}}
                    <div class="cat-actions">
                      <a href="#contact" class="cat-btn"><i class="bi bi-chat-dots"></i> Tanya Produk Ini</a>
                      @if(web_property('whatsapp_no'))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', web_property('whatsapp_no')) }}?text={{ urlencode('Halo, saya ingin bertanya tentang ' . $product->products_name . ' (' . $product->products_code . ').') }}"
                           target="_blank" rel="noopener" class="cat-btn cat-btn-wa"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                      @endif
                    </div>
                  </div>
                </article>
              @endforeach
            </div>
          @else
            {{-- Service is live but its products are not specified yet. --}}
            <p class="cat-empty">
              <i class="bi bi-hourglass-split"></i>
              Rincian produk untuk layanan ini sedang kami siapkan. Hubungi kami untuk informasi lebih awal.
            </p>
          @endif

        </div>
      @endforeach
    @else
      <p class="text-center text-muted">Belum ada produk untuk ditampilkan.</p>
    @endif
  </div>

  <script>
    // Service tabs and size tabs share one handler: a tab names the panel it
    // shows, and only siblings inside the same group are switched.
    (function () {
      document.querySelectorAll('#products [data-cat-panel]').forEach(function (tab) {
        tab.addEventListener('click', function () {
          var group = tab.closest('ul');
          var panel = document.getElementById(tab.dataset.catPanel);
          if (!group || !panel) return;

          group.querySelectorAll('[data-cat-panel]').forEach(function (sibling) {
            sibling.classList.remove('active');
            var other = document.getElementById(sibling.dataset.catPanel);
            if (other) other.classList.add('d-none');
          });

          tab.classList.add('active');
          panel.classList.remove('d-none');
        });
      });
    })();
  </script>
</section><!-- End Catalog Section -->
