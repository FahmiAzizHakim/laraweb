{{-- Shared form for creating / editing a package.
     Expects optional $data (Package), $products, $charges, $services. --}}
@php $data = $data ?? null; @endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Service</label>
      <select name="service_id" class="form-control">
        <option value="">-- Not tied to a service --</option>
        @foreach($services as $svc)
          <option value="{{ $svc->id }}" {{ (string) old('service_id', $data->service_id ?? '') === (string) $svc->id ? 'selected' : '' }}>
            {{ $svc->service_name }}
          </option>
        @endforeach
      </select>
      <small class="form-text text-muted">The service this package is listed under on the site.</small>
    </div>
  </div>
  <div class="col-md-5">
    <div class="form-group">
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" name="package_name" class="form-control" value="{{ old('package_name', $data->package_name ?? '') }}">
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <label>Code <span class="text-danger">*</span></label>
      <input type="text" name="package_code" class="form-control" value="{{ old('package_code', $data->package_code ?? '') }}">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Price</label>
      <input type="number" step="0.01" min="0" name="package_price" class="form-control"
             value="{{ old('package_price', $data->package_price ?? 0) }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Discount</label>
      <input type="number" step="0.01" min="0" name="package_discount" class="form-control"
             value="{{ old('package_discount', $data->package_discount ?? 0) }}">
      <small class="form-text text-muted">Cannot exceed the price.</small>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Status</label>
      <div>
        <label class="mr-3"><input type="radio" name="is_active" value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'checked' : '' }}> Active</label>
        <label><input type="radio" name="is_active" value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive</label>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <label>Description</label>
  <textarea name="package_description" class="form-control" rows="3">{{ old('package_description', $data->package_description ?? '') }}</textarea>
</div>

<div class="form-group">
  <label>Contents</label>
  <small class="form-text text-muted mb-2">
    One line per item, with a quantity. Each line is a product, an other charge, or a
    free-text benefit (e.g. &ldquo;24 month guarantee&rdquo;). Lines with nothing selected are ignored.
  </small>

  @php
    $oldDetails = old('details');
    $existingDetails = isset($data) ? $data->details : collect();
    $detailStartIndex = is_array($oldDetails) ? count($oldDetails) : $existingDetails->count();
  @endphp

  <div id="details-wrap">
    @if(is_array($oldDetails))
      @foreach($oldDetails as $i => $od)
        @include('pages.admin.master.package._detail_row', ['i' => $i, 'd' => $od, 'did' => $od['id'] ?? null])
      @endforeach
    @else
      @foreach($existingDetails as $i => $detail)
        @include('pages.admin.master.package._detail_row', ['i' => $i, 'd' => $detail, 'did' => $detail->id])
      @endforeach
    @endif
  </div>

  <button type="button" class="btn btn-sm btn-default" id="add-detail"><i class="fas fa-plus"></i> Add Line</button>

  <template id="detail-template">
    @include('pages.admin.master.package._detail_row', ['i' => '__INDEX__', 'd' => null, 'did' => null])
  </template>
</div>

<div class="form-group">
  <label>Remark</label>
  <textarea name="remark" class="form-control" rows="2">{{ old('remark', $data->remark ?? '') }}</textarea>
</div>

@section('script')
<script>
  (function () {
    var wrap = document.getElementById('details-wrap');
    var tpl  = document.getElementById('detail-template');
    var idx  = {{ $detailStartIndex }};

    // Show and submit only the field matching the row's selected type.
    function applyType(row) {
      var type = row.querySelector('.d-type').value;
      row.dataset.type = type;
      row.querySelectorAll('.d-field').forEach(function (field) {
        var on = field.dataset.for === type;
        field.style.display = on ? '' : 'none';
        field.disabled = !on;
        if (!on) field.value = '';
      });
    }

    document.getElementById('add-detail').addEventListener('click', function () {
      var html = tpl.innerHTML.replace(/__INDEX__/g, idx++);
      var holder = document.createElement('div');
      holder.innerHTML = html.trim();
      var row = holder.firstElementChild;
      wrap.appendChild(row);
      applyType(row);
    });

    wrap.addEventListener('change', function (e) {
      if (e.target.classList.contains('d-type')) {
        applyType(e.target.closest('.detail-row'));
      }
    });

    wrap.addEventListener('click', function (e) {
      var hit = e.target.closest('.d-remove');
      if (!hit) return;
      var row = hit.closest('.detail-row');
      var idInput = row.querySelector('input[name$="[id]"]');
      if (idInput && idInput.value) {
        // Existing line: flag for deletion and hide.
        row.querySelector('.d-remove-flag').value = '1';
        row.style.display = 'none';
      } else {
        row.remove();
      }
    });
  })();
</script>
@endsection
