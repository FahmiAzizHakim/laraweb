{{-- Order lookup is part of the transaction flow. Whether it appears is
     decided by Website > Sections; the standalone /orders page works either
     way. --}}
<!-- ======= Pesanan Anda (Order Lookup) Section ======= -->
@php
    $ord_brand      = $styles['brand_color'] ?? '#40c057';
    $ord_brandHover = $styles['brand_hover'] ?? '#48c960';
@endphp
<section id="orders">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Pesanan Anda</h2>
      <p>Masukkan email dan 4 digit terakhir nomor telepon yang Anda gunakan saat memesan
         untuk melihat daftar pesanan dan struk Anda.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="ord-card">
          <form action="{{ route($site.'.orders.lookup') }}" method="POST" class="ord-form">
            @csrf
            <div class="ord-field">
              <label>Email</label>
              <input type="email" name="email" class="ord-input" placeholder="email@contoh.com" required>
            </div>
            <div class="ord-field">
              <label>4 Digit Terakhir No. Telepon</label>
              <input type="text" name="phone_last4" class="ord-input" placeholder="1234"
                     inputmode="numeric" maxlength="4" pattern="\d{4}" required>
            </div>
            <button type="submit" class="ord-btn"><i class="bi bi-search"></i> Cari Pesanan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End Pesanan Anda Section -->
