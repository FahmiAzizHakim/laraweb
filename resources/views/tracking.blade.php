@extends('layout.website.main')

@section('main')
<section id="articles" style="margin-top:120px;">
  <div class="container" data-aos="fade-up">
    <div class="section-header">
      <h2>Tracking AWB</h2>
    </div>

    <div>
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <table style="width: 390px">
            <tbody>
              <tr>
                <th>Shipment No</th>
                <td>:</td>
                <td class="text-right">{{ $cn_no }}</td>
              </tr>
              <tr>
                <th style="width: 130px;">Shipment Date</th>
                <td>:</td>
                <td class="text-right">{{ $cn_date }}</td>
              </tr>
              <tr>
                <th>Service</th>
                <td>:</td>
                <td class="text-right">{{ $service }}</td>
              </tr>
              <tr>
                <th>Description</th>
                <td>:</td>
                <td class="text-right">{{ $description }}</td>
              </tr>
              <tr>
                <th>Kilo</th>
                <td>:</td>
                <td class="text-right">{{ $kilo }}Kg</td>
              </tr>
              <tr>
                <th>Koli</th>
                <td>:</td>
                <td class="text-right">{{ $koli }}</td>
              </tr>
              {{-- <tr>
                <th>Total Biaya</th>
                <td>:</td>
                <td id="formattedNumber" class="text-right"></td>
              </tr> --}}
              <tr>
                <th>Status Terakhir</th>
                <td>:</td>
                <td class="text-right"><strong>{{ $laststatus['status'] }}</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-6 col-sm-12">
          @foreach($process as $track)
          <div class="track-item">
            <h3>{{ $track['status'] }}</h3>
            <p>{{ $track['location'] }}</p>
            <h4>{{ $track['time'] }}</h4>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section><!-- End Testimonials Section -->
@endsection

@section('script')
<script>
// Function to format number as currency
function formatCurrency(number, locale, currency) {
    return number.toLocaleString(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Example usage
var number = {{ $grandtotal ?? 0 }};
var formattedNumber = formatCurrency(number, 'id-ID', 'IDR'); // Output: $1,234,567.89

// Display the formatted number (element is optional)
var totalEl = document.getElementById('formattedNumber');
if (totalEl) { totalEl.textContent = formattedNumber; }
</script>
@endsection