{{-- One specification row. Params: $i (index or __INDEX__), $s (array|model|null), $sid (id|null) --}}
@php
    $sget = function ($key, $default = '') use ($s) {
        if (is_array($s))  return $s[$key] ?? $default;
        if (is_object($s)) return $s->{$key} ?? $default;
        return $default;
    };
    $sid = $sid ?? null;
@endphp
<div class="spec-row form-row align-items-center mb-2">
  <input type="hidden" name="specifications[{{ $i }}][id]" value="{{ $sid }}">
  <input type="hidden" name="specifications[{{ $i }}][_remove]" value="0" class="s-remove-flag">

  <div class="col-md-4 mb-1">
    <input type="text" class="form-control form-control-sm" name="specifications[{{ $i }}][attribute]"
           value="{{ $sget('attribute') }}" maxlength="100" placeholder="Attribute (e.g. RAM)">
  </div>
  <div class="col-md-7 mb-1">
    <input type="text" class="form-control form-control-sm" name="specifications[{{ $i }}][value]"
           value="{{ $sget('value') }}" maxlength="300" placeholder="Value (e.g. 16GB DDR5)">
  </div>
  <div class="col-md-1 mb-1 text-right">
    <button type="button" class="btn btn-xs btn-danger s-remove" title="Remove"><i class="fas fa-times"></i></button>
  </div>
</div>
