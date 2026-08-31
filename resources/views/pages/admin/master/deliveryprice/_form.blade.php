{{-- Shared form for creating / editing a delivery price.
     Expects $provinces; optional $data (DeliveryPrice model) when editing.
     Province is a UI filter only (not stored). City/district/subdistrict
     options load via AJAX (cascading). --}}
@php
  $data = $data ?? null;
  $selProv = old('province_code', $provinceCode ?? '');
  $selCity = old('city_code', $data->city_code ?? '');
  $selDist = old('district_code', $data->district_code ?? '');
  $selSub  = old('subdistrict_code', $data->subdistrict_code ?? '');
@endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="alert alert-info py-2">
  <i class="fas fa-info-circle"></i>
  Pick a <strong>Province</strong> to narrow the city list. A <strong>City</strong> alone sets a
  city-wide price; add a <strong>District</strong> or <strong>Subdistrict</strong> for more specific
  prices. Lookups fall back subdistrict &rarr; district &rarr; city.
</div>

<div class="form-group">
  <label>Province</label>
  <select name="province_code" id="dp_province" class="form-control">
    <option value="">-- Select province --</option>
    @foreach($provinces as $p)
      <option value="{{ $p->province_code }}" {{ $selProv == $p->province_code ? 'selected' : '' }}>
        {{ $p->province_name }}
      </option>
    @endforeach
  </select>
  <small class="form-text text-muted">Filter only &mdash; not saved.</small>
</div>

<div class="form-group">
  <label>City <span class="text-danger">*</span></label>
  <select name="city_code" id="dp_city" class="form-control" data-selected="{{ $selCity }}">
    <option value="">-- Select province first --</option>
  </select>
</div>

<div class="form-group">
  <label>District <small class="text-muted">(optional)</small></label>
  <select name="district_code" id="dp_district" class="form-control" data-selected="{{ $selDist }}">
    <option value="">-- Whole city --</option>
  </select>
</div>

<div class="form-group">
  <label>Subdistrict <small class="text-muted">(optional)</small></label>
  <select name="subdistrict_code" id="dp_subdistrict" class="form-control" data-selected="{{ $selSub }}">
    <option value="">-- Whole district --</option>
  </select>
</div>

<div class="form-group">
  <label>Price <span class="text-danger">*</span></label>
  <div class="input-group">
    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
    <input type="number" step="0.01" min="0" name="price" class="form-control"
           value="{{ old('price', $data->price ?? '') }}" placeholder="0.00">
  </div>
</div>

<div class="form-group">
  <label>Status</label>
  <div>
    <label class="mr-3">
      <input type="radio" name="is_active" value="1"
             {{ old('is_active', $data->is_active ?? 1) == 1 ? 'checked' : '' }}> Active
    </label>
    <label>
      <input type="radio" name="is_active" value="0"
             {{ old('is_active', $data->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive
    </label>
  </div>
</div>

@section('script')
<script>
(function () {
  var provSel = document.getElementById('dp_province');
  var citySel = document.getElementById('dp_city');
  var distSel = document.getElementById('dp_district');
  var subSel  = document.getElementById('dp_subdistrict');

  var CITY_URL = "{{ url('/master/deliveryprice/cities') }}";
  var DIST_URL = "{{ url('/master/deliveryprice/districts') }}";
  var SUB_URL  = "{{ url('/master/deliveryprice/subdistricts') }}";

  function resetSelect(sel, placeholder) {
    sel.innerHTML = '<option value="">' + placeholder + '</option>';
  }

  function fill(sel, rows, valKey, textKey, selectedVal) {
    rows.forEach(function (r) {
      var opt = document.createElement('option');
      opt.value = r[valKey];
      opt.textContent = r[textKey];
      if (selectedVal && String(selectedVal) === String(r[valKey])) opt.selected = true;
      sel.appendChild(opt);
    });
  }

  function loadCities(province, selected, then) {
    resetSelect(citySel, '-- Select city --');
    resetSelect(distSel, '-- Whole city --');
    resetSelect(subSel, '-- Whole district --');
    if (!province) { resetSelect(citySel, '-- Select province first --'); if (then) then(); return; }
    fetch(CITY_URL + '/' + encodeURIComponent(province))
      .then(function (r) { return r.json(); })
      .then(function (rows) {
        rows.forEach(function (row) {
          var opt = document.createElement('option');
          opt.value = row.city_code;
          opt.textContent = row.city_name + (row.city_type ? ' (' + row.city_type + ')' : '');
          if (selected && String(selected) === String(row.city_code)) opt.selected = true;
          citySel.appendChild(opt);
        });
        if (then) then();
      })
      .catch(function () { if (then) then(); });
  }

  function loadDistricts(city, selected, then) {
    resetSelect(distSel, '-- Whole city --');
    resetSelect(subSel, '-- Whole district --');
    if (!city) { if (then) then(); return; }
    fetch(DIST_URL + '/' + encodeURIComponent(city))
      .then(function (r) { return r.json(); })
      .then(function (rows) {
        fill(distSel, rows, 'district_code', 'district_name', selected);
        if (then) then();
      })
      .catch(function () { if (then) then(); });
  }

  function loadSubdistricts(district, selected) {
    resetSelect(subSel, '-- Whole district --');
    if (!district) return;
    fetch(SUB_URL + '/' + encodeURIComponent(district))
      .then(function (r) { return r.json(); })
      .then(function (rows) {
        fill(subSel, rows, 'subdistrict_code', 'subdistrict_name', selected);
      });
  }

  provSel.addEventListener('change', function () { loadCities(provSel.value, null); });
  citySel.addEventListener('change', function () { loadDistricts(citySel.value, null); });
  distSel.addEventListener('change', function () { loadSubdistricts(distSel.value, null); });

  // Initial hydration (edit mode or after a validation error).
  var preCity = citySel.getAttribute('data-selected');
  var preDist = distSel.getAttribute('data-selected');
  var preSub  = subSel.getAttribute('data-selected');
  if (provSel.value) {
    loadCities(provSel.value, preCity, function () {
      if (preCity) loadDistricts(preCity, preDist, function () {
        if (preDist) loadSubdistricts(preDist, preSub);
      });
    });
  }
})();
</script>
@endsection
