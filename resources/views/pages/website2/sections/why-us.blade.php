<!-- ======= Why Choose Us Section ======= -->
<section id="why-us" class="why-us">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Mengapa Memilih Kami</h2>
    </div>

    <div class="row">
      @php
        $whyUs = [
          ['icon' => 'bi-person-badge',      'title' => 'Teknisi Berpengalaman'],
          ['icon' => 'bi-lightning-charge',  'title' => 'Pengerjaan Cepat'],
          ['icon' => 'bi-shield-check',      'title' => 'Bergaransi'],
          ['icon' => 'bi-clock',             'title' => 'Tepat Waktu'],
          ['icon' => 'bi-cash-coin',         'title' => 'Harga Transparan'],
          ['icon' => 'bi-tools',             'title' => 'Peralatan Lengkap'],
        ];
      @endphp

      @foreach($whyUs as $item)
      <div class="col-lg-4 col-md-6">
        <div class="why-box">
          <i class="bi bi-check-circle-fill why-check"></i>
          <i class="bi {{ $item['icon'] }} why-icon"></i>
          <h4>{{ $item['title'] }}</h4>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section><!-- End Why Choose Us Section -->
