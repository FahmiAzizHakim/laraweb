@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@php
  [$latVal, $lngVal] = array_pad(explode(',', old('location', $data->location ?? '')), 2, '');
  $latVal = trim($latVal) ?: '-6.239005';
  $lngVal = trim($lngVal) ?: '106.907119';
@endphp

@section('css')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endsection

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Website Setting</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form action="{{ url('/website/setting') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $data->id }}">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Website Name <span class="text-danger">*</span></label>
              <input type="text" name="web_name" class="form-control" value="{{ old('web_name', $data->web_name) }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Company Name</label>
              <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $data->company_name) }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Phone Number</label>
              <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $data->phone_number) }}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>WhatsApp Number</label>
              <input type="text" name="whatsapp_no" class="form-control" placeholder="e.g. 62812xxxxxxx"
                     value="{{ old('whatsapp_no', $data->whatsapp_no) }}">
              <small class="form-text text-muted">International format without "+" or spaces (used for the wa.me link).</small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $data->email) }}">
            </div>
          </div>
        </div>

        {{-- Social media links --}}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><i class="fab fa-facebook"></i> Facebook Link</label>
              <input type="url" name="facebook_link" class="form-control" placeholder="https://facebook.com/..."
                     value="{{ old('facebook_link', $data->facebook_link) }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><i class="fab fa-twitter"></i> Twitter / X Link</label>
              <input type="url" name="twitter_link" class="form-control" placeholder="https://twitter.com/..."
                     value="{{ old('twitter_link', $data->twitter_link) }}">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label><i class="fab fa-instagram"></i> Instagram Link</label>
              <input type="url" name="instagram_link" class="form-control" placeholder="https://instagram.com/..."
                     value="{{ old('instagram_link', $data->instagram_link) }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label><i class="fab fa-linkedin"></i> LinkedIn Link</label>
              <input type="url" name="linkedin_link" class="form-control" placeholder="https://linkedin.com/..."
                     value="{{ old('linkedin_link', $data->linkedin_link) }}">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Address</label>
          <textarea name="address" class="form-control" rows="2">{{ old('address', $data->address) }}</textarea>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Logo (Header)</label>
              @if($data->logo)
                <div class="mb-2"><img src="{{ asset($data->logo) }}" id="logo_preview" style="max-height:70px;background:#eee;padding:4px;border-radius:4px;"></div>
              @else
                <div class="mb-2"><img src="" id="logo_preview" style="display:none;max-height:70px;background:#eee;padding:4px;border-radius:4px;"></div>
              @endif
              <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
              <small class="form-text text-muted">Leave empty to keep current. Path: <code>{{ $data->logo ?: '(none)' }}</code></small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Logo (Light — Hero &amp; Footer)</label>
              @if($data->logo_white)
                <div class="mb-2" style="background:#333;display:inline-block;padding:6px;border-radius:4px;"><img src="{{ asset($data->logo_white) }}" id="logow_preview" style="max-height:60px;"></div>
              @else
                <div class="mb-2"><img src="" id="logow_preview" style="display:none;max-height:60px;"></div>
              @endif
              <input type="file" name="logo_white" id="logo_white" class="form-control-file" accept="image/*">
              <small class="form-text text-muted">Leave empty to keep current. Path: <code>{{ $data->logo_white ?: '(none)' }}</code></small>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Location (Map Point)</label>
          <div class="row">
            <div class="col-md-6 mb-2">
              <label class="small mb-0">Latitude</label>
              <input type="number" id="loc_lat" class="form-control" step="any" value="{{ $latVal }}">
            </div>
            <div class="col-md-6 mb-2">
              <label class="small mb-0">Longitude</label>
              <input type="number" id="loc_lng" class="form-control" step="any" value="{{ $lngVal }}">
            </div>
          </div>
          <div id="loc_map" style="height:320px;border:1px solid #ced4da;border-radius:4px;"></div>
          <input type="hidden" name="location" id="location" value="{{ $latVal }},{{ $lngVal }}">
          <small class="form-text text-muted">Click the map or drag the marker to set the point.</small>
        </div>

        <div class="form-group">
          <label>Status</label>
          <div>
            <label class="mr-3"><input type="radio" name="is_active" value="1" {{ old('is_active', $data->is_active) == 1 ? 'checked' : '' }}> Active</label>
            <label><input type="radio" name="is_active" value="0" {{ old('is_active', $data->is_active) == 0 ? 'checked' : '' }}> Inactive</label>
          </div>
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Settings</button>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
@endsection

@section('script')
<script>
  (function () {
    function preview(inputId, imgId) {
      var input = document.getElementById(inputId), img = document.getElementById(imgId);
      if (!input || !img) return;
      input.addEventListener('change', function () {
        if (input.files && input.files[0]) {
          var r = new FileReader();
          r.onload = function (e) { img.src = e.target.result; img.style.display = 'inline-block'; };
          r.readAsDataURL(input.files[0]);
        }
      });
    }
    preview('logo', 'logo_preview');
    preview('logo_white', 'logow_preview');
  })();
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  (function () {
    var latInput = document.getElementById('loc_lat');
    var lngInput = document.getElementById('loc_lng');
    var hidden   = document.getElementById('location');

    var lat = parseFloat(latInput.value); if (isNaN(lat)) lat = -6.239005;
    var lng = parseFloat(lngInput.value); if (isNaN(lng)) lng = 106.907119;

    var map = L.map('loc_map').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    function setVal(la, ln) {
      latInput.value = la.toFixed(6);
      lngInput.value = ln.toFixed(6);
      hidden.value   = la.toFixed(6) + ',' + ln.toFixed(6);
    }
    map.on('click', function (e) { marker.setLatLng(e.latlng); setVal(e.latlng.lat, e.latlng.lng); });
    marker.on('dragend', function () { var p = marker.getLatLng(); setVal(p.lat, p.lng); });
    function fromInputs() {
      var la = parseFloat(latInput.value), ln = parseFloat(lngInput.value);
      if (!isNaN(la) && !isNaN(ln)) { marker.setLatLng([la, ln]); map.setView([la, ln]); hidden.value = la + ',' + ln; }
    }
    latInput.addEventListener('change', fromInputs);
    lngInput.addEventListener('change', fromInputs);
    setTimeout(function () { map.invalidateSize(); }, 200);
  })();
</script>
@endsection
