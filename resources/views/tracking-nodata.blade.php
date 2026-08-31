@extends('layout.website.main')

@section('main')
<section id="articles" style="margin-top:120px; min-height:40vh;">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Shipment not found !</h2>
    </div>

    <p class="text-center">Nomor resi tidak ditemukan. Periksa kembali nomor resi Anda.</p>
    <div class="text-center mt-3">
      <a href="{{ url('/#tracking') }}" class="logistic-btn" style="text-decoration:none;">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
</section><!-- End Section -->
@endsection

@section('script')
@endsection
