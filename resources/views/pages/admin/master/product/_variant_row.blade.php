{{-- One variant row. Params: $i (index or __INDEX__), $v (array|model|null), $vid (id|null) --}}
@php
    $vget = function ($key, $default = '') use ($v) {
        if (is_array($v))  return $v[$key] ?? $default;
        if (is_object($v)) return $v->{$key} ?? $default;
        return $default;
    };
    $vid    = $vid ?? null;
    $active = $vget('is_active', 1);
@endphp
<div class="variant-row border rounded p-2 mb-2" style="background:#fafafa;">
  <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $vid }}">
  <input type="hidden" name="variants[{{ $i }}][_remove]" value="0" class="v-remove-flag">

  <div class="form-row">
    <div class="col-md-3 mb-2">
      <input type="text" class="form-control form-control-sm" name="variants[{{ $i }}][variant_name]"
             value="{{ $vget('variant_name') }}" placeholder="Variant name *">
    </div>
    <div class="col-md-2 mb-2">
      <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="variants[{{ $i }}][variant_price]"
             value="{{ $vget('variant_price') }}" placeholder="Price">
    </div>
    <div class="col-md-4 mb-2">
      <input type="text" class="form-control form-control-sm" name="variants[{{ $i }}][variant_description]"
             value="{{ $vget('variant_description') }}" placeholder="Description">
    </div>
    <div class="col-md-2 mb-2">
      <label class="mb-0" style="font-weight:400;font-size:13px;cursor:pointer;">
        <input type="checkbox" name="variants[{{ $i }}][is_active]" value="1" {{ $active ? 'checked' : '' }}> Active
      </label>
    </div>
    <div class="col-md-1 mb-2 text-right">
      <button type="button" class="btn btn-xs btn-danger v-remove" title="Remove"><i class="fas fa-times"></i></button>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-3 mb-1"><input type="number" min="0" class="form-control form-control-sm" name="variants[{{ $i }}][variant_weight]" value="{{ $vget('variant_weight') }}" placeholder="Weight (g)"></div>
    <div class="col-md-3 mb-1"><input type="number" min="0" class="form-control form-control-sm" name="variants[{{ $i }}][variant_width]"  value="{{ $vget('variant_width') }}"  placeholder="Width (cm)"></div>
    <div class="col-md-3 mb-1"><input type="number" min="0" class="form-control form-control-sm" name="variants[{{ $i }}][variant_length]" value="{{ $vget('variant_length') }}" placeholder="Length (cm)"></div>
    <div class="col-md-3 mb-1"><input type="number" min="0" class="form-control form-control-sm" name="variants[{{ $i }}][variant_height]" value="{{ $vget('variant_height') }}" placeholder="Height (cm)"></div>
  </div>
</div>
