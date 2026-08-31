{{-- Pricing is optional: with no package defined the whole section
     stays out of the page rather than rendering an empty header. --}}
@if(($packages ?? collect())->count())
<!-- ======= Packages / Pricing Section ======= -->
<section id="packages" class="packages">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Paket &amp; Harga</h2>
    </div>
    <p class="pkg-subtitle">Pilih paket instalasi yang paling sesuai dengan kebutuhan Anda. Semua paket sudah termasuk teknisi bersertifikat dan garansi pengerjaan.</p>

    @php
      // One tab per service that actually has packages, in the same order the
      // catalog uses. A package with no service of its own falls back to the
      // service of its first product; anything still unresolved is grouped last.
      $pkgByService = $packages->groupBy(fn ($p) => $p->resolved_service_id ?: 0);

      $pkgGroups = ($services ?? collect())
          ->filter(fn ($svc) => $pkgByService->has($svc->id))
          ->map(fn ($svc) => [
              'key'      => 'pkg-svc-' . $svc->id,
              'label'    => $svc->service_name,
              'icon'     => $svc->service_icon ?: 'bi-box-seam',
              'packages' => $pkgByService->get($svc->id),
          ])
          ->values();

      if ($pkgByService->has(0)) {
          $pkgGroups->push([
              'key'      => 'pkg-svc-other',
              'label'    => 'Lainnya',
              'icon'     => 'bi-box-seam',
              'packages' => $pkgByService->get(0),
          ]);
      }
    @endphp

    {{-- Service tabs, the same idea as the catalog section: shown even for a
         single service, so the two sections read the same way. --}}
    @if($pkgGroups->count())
      <ul class="pkg-tabs">
        @foreach($pkgGroups as $group)
          <li>
            <button type="button" class="pkg-tab {{ $loop->first ? 'active' : '' }}"
                    data-pkg-panel="{{ $group['key'] }}">
              <i class="bi {{ $group['icon'] }}"></i>
              {{ $group['label'] }}
              <span class="pkg-tab-count">{{ $group['packages']->count() }}</span>
            </button>
          </li>
        @endforeach
      </ul>
    @endif

    @foreach($pkgGroups as $group)
    @php
      $groupPackages = $group['packages'];
      // Four across when there are four or more tiers, otherwise spread them evenly.
      $cols = ['1' => 'col-lg-6', '2' => 'col-lg-6', '3' => 'col-lg-4'][(string) $groupPackages->count()] ?? 'col-lg-3';

      // A lone package would sit against the left edge of the row, so centre it.
      $rowAlign = $groupPackages->count() === 1 ? 'justify-content-center' : '';
    @endphp
    <div class="pkg-panel {{ $loop->first ? '' : 'd-none' }}" id="{{ $group['key'] }}">
    <div class="row pkg-row {{ $rowAlign }}">
      @foreach($groupPackages as $pkg)
      <div class="{{ $cols }} col-md-6">
        <div class="pkg-card">
          <div class="pkg-head">
            <h4 class="pkg-name">{{ $pkg->package_name }}</h4>
            @if($pkg->package_description)
              <p class="pkg-tagline">{{ $pkg->package_description }}</p>
            @endif
          </div>

          {{-- With an image: it sits beside the price AND the contents on
               desktop, and stacks under the title on phones (col-12). --}}
          <div class="pkg-body row">
            @if($pkg->cover_image)
              <div class="col-12 col-md-5 pkg-media">
                <img src="{{ asset($pkg->cover_image) }}" alt="{{ $pkg->package_name }}" loading="lazy">
              </div>
            @endif

            <div class="col-12 {{ $pkg->cover_image ? 'col-md-7' : '' }} pkg-body-main">
              {{-- packages.package_price, struck through only when a discount applies. --}}
              @if($pkg->package_discount > 0)
                <div class="pkg-old">
                  <span class="pkg-old-price">Rp {{ number_format($pkg->package_price, 0, ',', '.') }}</span>
                  <span class="pkg-discount">- Rp {{ number_format($pkg->package_discount, 0, ',', '.') }}</span>
                </div>
              @endif
              {{-- package_price - package_discount, or an invitation to ask
                   when the package is priced on survey. --}}
              @if($pkg->net_price > 0)
                <span class="pkg-price-label">Mulai Dari</span>
                <div class="pkg-price">
                  <span class="pkg-currency">Rp</span>
                  <span class="pkg-amount">{{ number_format($pkg->net_price, 0, ',', '.') }}</span>
                </div>
                <p class="pkg-note">Harga tersebut hanya untuk estimasi &amp; dapat berubah</p>
              @else
                <div class="pkg-price">
                  <span class="pkg-ask">Hubungi Kami</span>
                </div>
              @endif

              @if($pkg->details->count())
                <ul class="pkg-features">
                  @foreach($pkg->details as $detail)
                    @php
                      // Only product lines carry specifications.
                      $specs = $detail->line_type === 'product' && $detail->product
                          ? $detail->product->specifications
                          : collect();
                    @endphp
                    <li>
                      <i class="bi bi-check-circle-fill"></i>
                      <div class="pkg-feature-body">
                        <span>{{ $detail->qty > 1 ? $detail->qty.'x ' : '' }}{{ $detail->line_label }}</span>

                        {{-- The product's own specifications, as a sub-list. --}}
                        @if($specs->count())
                          <ul class="pkg-specs">
                            @foreach($specs as $spec)
                              <li><span>{{ $spec->attribute }}</span> {{ $spec->value }}</li>
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>

          {{-- Last child of the card, so it lines up across cards of
               different heights (margin-top:auto in the stylesheet).
               Opens WhatsApp with the package already named when the site has a
               number set (Website > Website Setting); otherwise it falls back
               to the contact form further down the page. --}}
          @php
            $waNumber = preg_replace('/[^0-9]/', '', (string) web_property('whatsapp_no'));
            $waText   = 'Halo, saya ingin bertanya tentang paket ' . $pkg->package_name
                      . ' (' . $pkg->package_code . ').';
          @endphp
          @if($waNumber)
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waText) }}"
               target="_blank" rel="noopener" class="pkg-btn">
              <i class="bi bi-whatsapp"></i> Tanya Paket Ini
            </a>
          @else
            <a href="#contact" class="pkg-btn">Tanya Paket Ini</a>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    </div>
    @endforeach

    <div class="pkg-guarantee">
      <span><i class="bi bi-arrow-counterclockwise"></i> Garansi uang kembali 30 hari</span>
      <span><i class="bi bi-shield-check"></i> Teknisi bersertifikat &amp; terverifikasi</span>
      <span><i class="bi bi-headset"></i> Dukungan pelanggan 24/7</span>
    </div>
  </div>

  <script>
    // Each tab names the panel it opens; only one panel in the group is shown.
    (function () {
      var tabs = document.querySelectorAll('#packages [data-pkg-panel]');
      if (!tabs.length) return;

      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (other) {
            other.classList.remove('active');
            var panel = document.getElementById(other.dataset.pkgPanel);
            if (panel) panel.classList.add('d-none');
          });

          tab.classList.add('active');
          var open = document.getElementById(tab.dataset.pkgPanel);
          if (open) open.classList.remove('d-none');
        });
      });
    })();
  </script>
</section><!-- End Packages Section -->
@endif
