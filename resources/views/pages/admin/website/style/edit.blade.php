@extends('layout.admin.main')

@section('title')
<h1>{{ $title }}</h1>
@endsection

@php
    $isColors = $data->style_group === 'Colors';

    // Pre-parse a size value ("300px", "67vh", ...) into number + unit.
    $sizeNumber = '';
    $sizeUnit   = 'px';
    if ($data->style_type === 'size'
        && preg_match('/^\s*(-?\d*\.?\d+)\s*([a-z%]+)\s*$/i', $data->style_value, $m)) {
        $sizeNumber = $m[1];
        $sizeUnit   = strtolower($m[2]);
    }
    $units = ['px', '%', 'em', 'rem', 'vh', 'vw', 'pt', 'vmin', 'vmax'];
    if ($sizeUnit && !in_array($sizeUnit, $units)) {
        array_unshift($units, $sizeUnit);
    }

    $value = old('style_value', $data->style_value);
@endphp

@section('content')
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">Edit Style Value</h3>
      <div class="card-tools">
        <a href="{{ url('/website/style') }}">
          <button type="button" class="btn btn-sm btn-default">
            <i class="fas fa-arrow-left"></i> <span>&nbsp; Back</span>
          </button>
        </a>
      </div>
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

      <div class="row">
        <div class="col-md-8">

          {{-- Read-only context --}}
          <dl class="row mb-3">
            <dt class="col-sm-3">Group</dt>
            <dd class="col-sm-9">{{ $data->style_group ?: '-' }}</dd>

            <dt class="col-sm-3">Label</dt>
            <dd class="col-sm-9">{{ $data->style_label ?: '-' }}</dd>

            <dt class="col-sm-3">Key</dt>
            <dd class="col-sm-9"><code>{{ $data->style_key }}</code></dd>

            @unless($isColors)
            <dt class="col-sm-3">Type</dt>
            <dd class="col-sm-9"><span class="badge badge-info">{{ ucfirst($data->style_type) }}</span></dd>
            @endunless

            @if($data->description)
            <dt class="col-sm-3">Description</dt>
            <dd class="col-sm-9">{{ $data->description }}</dd>
            @endif
          </dl>

          <form action="{{ url('/website/style/'.$data->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if($isColors)
              {{-- ========================================================
                   COLORS GROUP: switchable type (color / gradient / text)
                   Changing the type swaps the input and clears the value.
                 ======================================================== --}}
              <div class="form-group">
                <label>Type <span class="text-danger">*</span></label>
                <select name="style_type" id="style_type" class="form-control" style="max-width:220px;">
                  @php $curType = old('style_type', $data->style_type); @endphp
                  @foreach(['color' => 'Color', 'gradient' => 'Gradient', 'text' => 'Text'] as $val => $lbl)
                    <option value="{{ $val }}" {{ $curType === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                  @endforeach
                </select>
                <small class="form-text text-muted">Changing the type resets the value.</small>
              </div>

              <div class="form-group">
                <label>Value <span class="text-danger">*</span></label>
                <input type="hidden" name="style_value" id="style_value" value="{{ $value }}" data-raw="{{ $value }}">

                {{-- COLOR --}}
                <div class="value-widget" data-type="color">
                  <div class="d-flex align-items-center">
                    <input type="color" id="color_input" class="form-control p-0 mr-2" style="width:60px;height:38px;">
                    <input type="text" id="color_text" class="form-control" style="max-width:200px;" maxlength="7" placeholder="#40c057">
                  </div>
                  <small class="form-text text-muted">Pick a colour or type a hex value.</small>
                </div>

                {{-- GRADIENT --}}
                <div class="value-widget" data-type="gradient">
                  <div class="d-flex align-items-center flex-wrap">
                    <div class="mr-3 mb-2">
                      <label class="mb-0 d-block small">Start colour</label>
                      <input type="color" id="grad_c1" class="form-control p-0" style="width:60px;height:38px;">
                    </div>
                    <div class="mr-3 mb-2">
                      <label class="mb-0 d-block small">End colour</label>
                      <input type="color" id="grad_c2" class="form-control p-0" style="width:60px;height:38px;">
                    </div>
                    <div class="mr-3 mb-2">
                      <label class="mb-0 d-block small">Angle</label>
                      <div class="input-group" style="width:130px;">
                        <input type="number" id="grad_angle" class="form-control" value="90">
                        <div class="input-group-append"><span class="input-group-text">deg</span></div>
                      </div>
                    </div>
                  </div>
                  <div id="grad_preview" class="mt-1 mb-2" style="height:38px;border:1px solid #ced4da;border-radius:4px;"></div>
                </div>

                {{-- TEXT --}}
                <div class="value-widget" data-type="text">
                  <input type="text" id="text_input" class="form-control" placeholder="Free text value">
                </div>
              </div>

            @else
              {{-- ========================================================
                   NON-COLORS GROUP: fixed type (value only)
                 ======================================================== --}}
              <div class="form-group">
                <label>Value <span class="text-danger">*</span></label>

                @if($data->style_type === 'size')
                  <div class="input-group" style="max-width:260px;">
                    <input type="number" id="size_number" class="form-control" step="any" value="{{ $sizeNumber }}">
                    <div class="input-group-append">
                      <select id="size_unit" class="form-control">
                        @foreach($units as $u)
                          <option value="{{ $u }}" {{ $u === $sizeUnit ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <input type="hidden" name="style_value" id="style_value" value="{{ $value }}">
                  <small class="form-text text-muted">Enter a number and choose a unit (e.g. <code>300</code> + <code>px</code>).</small>
                @else
                  <input type="text" name="style_value" id="style_value" class="form-control" value="{{ $value }}">
                  <small class="form-text text-muted">Free text value.</small>
                @endif
              </div>
            @endif

            <button type="submit" class="btn btn-success">
              <i class="fas fa-save"></i> Update
            </button>
            <a href="{{ url('/website/style') }}" class="btn btn-default">Cancel</a>
          </form>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
  </div>
@endsection

@section('script')
<script>
  (function () {
    var isColors = @json($isColors);

    // Normalise any CSS colour (hex, rgb, rgb(a)) to #rrggbb.
    function cssColorToHex(input) {
      if (!input) return '#000000';
      var probe = document.createElement('div');
      probe.style.color = '';
      probe.style.color = String(input).trim();
      document.body.appendChild(probe);
      var computed = getComputedStyle(probe).color;
      document.body.removeChild(probe);
      var nums = computed.match(/\d+(\.\d+)?/g);
      if (!nums || nums.length < 3) return '#000000';
      return '#' + nums.slice(0, 3).map(function (n) {
        return ('0' + Math.round(parseFloat(n)).toString(16)).slice(-2);
      }).join('');
    }

    // ================= NON-COLORS (fixed type) =================
    if (!isColors) {
      var sizeNum = document.getElementById('size_number');
      if (sizeNum) {
        var unit = document.getElementById('size_unit');
        var hiddenS = document.getElementById('style_value');
        var rebuildSize = function () { hiddenS.value = (sizeNum.value === '' ? '0' : sizeNum.value) + unit.value; };
        sizeNum.addEventListener('input', rebuildSize);
        unit.addEventListener('change', rebuildSize);
        rebuildSize();
      }
      return;
    }

    // ================= COLORS (switchable type) =================
    var typeSel = document.getElementById('style_type');
    var hidden  = document.getElementById('style_value');
    var widgets = document.querySelectorAll('.value-widget');

    var colorInput = document.getElementById('color_input');
    var colorText  = document.getElementById('color_text');
    var c1 = document.getElementById('grad_c1');
    var c2 = document.getElementById('grad_c2');
    var angle = document.getElementById('grad_angle');
    var prev  = document.getElementById('grad_preview');
    var textInput = document.getElementById('text_input');

    function showWidget(type) {
      widgets.forEach(function (w) { w.style.display = (w.getAttribute('data-type') === type) ? 'block' : 'none'; });
    }

    // ---- color ----
    function syncColor() { hidden.value = colorText.value; }
    colorInput.addEventListener('input', function () { colorText.value = colorInput.value; syncColor(); });
    colorText.addEventListener('input', function () {
      if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(colorText.value.trim())) colorInput.value = colorText.value.trim();
      syncColor();
    });

    // ---- gradient ----
    function rebuildGrad() {
      var v = 'linear-gradient(' + (angle.value || 90) + 'deg, ' + c1.value + ' 0%, ' + c2.value + ' 100%)';
      hidden.value = v;
      prev.style.background = v;
    }
    c1.addEventListener('input', rebuildGrad);
    c2.addEventListener('input', rebuildGrad);
    angle.addEventListener('input', rebuildGrad);

    // ---- text ----
    textInput.addEventListener('input', function () { hidden.value = textInput.value; });

    // Prefill the active widget from the stored value (no clearing).
    function prefill(type) {
      if (type === 'color') {
        var v = (hidden.value || '').trim();
        colorText.value = v;
        if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(v)) colorInput.value = v;
      } else if (type === 'gradient') {
        var raw = hidden.getAttribute('data-raw') || hidden.value || '';
        var found = raw.match(/#[0-9a-fA-F]{3,6}|rgba?\([^)]*\)/gi) || [];
        if (found.length) { c1.value = cssColorToHex(found[0]); c2.value = cssColorToHex(found[found.length - 1]); }
        var am = raw.match(/(-?\d+)deg/); if (am) angle.value = am[1];
        if (hidden.value) prev.style.background = hidden.value;
      } else if (type === 'text') {
        textInput.value = hidden.value || '';
      }
    }

    // Initial state (keep existing value).
    showWidget(typeSel.value);
    prefill(typeSel.value);

    // On type change: reset everything to empty, then show the new widget.
    typeSel.addEventListener('change', function () {
      hidden.value = '';
      hidden.setAttribute('data-raw', '');
      colorInput.value = '#000000';
      colorText.value = '';
      c1.value = '#000000';
      c2.value = '#000000';
      angle.value = '90';
      prev.style.background = '';
      textInput.value = '';
      showWidget(typeSel.value);
    });
  })();
</script>
@endsection
