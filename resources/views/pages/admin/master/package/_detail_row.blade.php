{{-- One package detail line. Params: $i (index or __INDEX__), $d (array|model|null), $did (id|null),
     $products, $charges. A line is exactly one of: product / other charge / other benefit. --}}
@php
    $dget = function ($key, $default = '') use ($d) {
        if (is_array($d))  return $d[$key] ?? $default;
        if (is_object($d)) return $d->{$key} ?? $default;
        return $default;
    };
    $did = $did ?? null;

    // On repopulate the type comes back on the row; on a model it is derived.
    $type = $dget('line_type') ?: 'product';

    // Only the field for the selected type is shown and submitted; Bootstrap's
    // display:block on .form-control beats the [hidden] attribute, so use style.
    $off = fn ($kind) => $type === $kind ? '' : 'style="display:none;" disabled';
@endphp
<div class="detail-row border rounded p-2 mb-2" style="background:#fafafa;" data-type="{{ $type }}">
  <input type="hidden" name="details[{{ $i }}][id]" value="{{ $did }}">
  <input type="hidden" name="details[{{ $i }}][_remove]" value="0" class="d-remove-flag">

  <div class="form-row align-items-center">
    <div class="col-md-3 mb-1">
      <select class="form-control form-control-sm d-type" name="details[{{ $i }}][line_type]">
        <option value="product" {{ $type === 'product' ? 'selected' : '' }}>Product</option>
        <option value="charge"  {{ $type === 'charge'  ? 'selected' : '' }}>Other Charge</option>
        <option value="benefit" {{ $type === 'benefit' ? 'selected' : '' }}>Other Benefit</option>
      </select>
    </div>

    <div class="col-md-7 mb-1">
      {{-- Only the field matching the selected type stays enabled, so the other
           two are never submitted. --}}
      <select class="form-control form-control-sm d-field" data-for="product"
              name="details[{{ $i }}][product_id]" {!! $off('product') !!}>
        <option value="">-- Select product --</option>
        @foreach($products as $product)
          <option value="{{ $product->id }}" {{ $dget('product_id') == $product->id ? 'selected' : '' }}>
            {{ $product->products_name }} ({{ $product->products_code }})
          </option>
        @endforeach
      </select>

      <select class="form-control form-control-sm d-field" data-for="charge"
              name="details[{{ $i }}][other_charge_id]" {!! $off('charge') !!}>
        <option value="">-- Select other charge --</option>
        @foreach($charges as $charge)
          <option value="{{ $charge->id }}" {{ $dget('other_charge_id') == $charge->id ? 'selected' : '' }}>
            {{ $charge->name }} ({{ number_format($charge->price, 2) }})
          </option>
        @endforeach
      </select>

      <input type="text" class="form-control form-control-sm d-field" data-for="benefit"
             name="details[{{ $i }}][other_benefit]" maxlength="200"
             value="{{ $dget('other_benefit') }}" placeholder="e.g. 24 month guarantee, 24/7 customer service, installation"
             {!! $off('benefit') !!}>
    </div>

    <div class="col-md-1 mb-1">
      <input type="number" min="1" max="65535" class="form-control form-control-sm d-qty"
             name="details[{{ $i }}][qty]" value="{{ $dget('qty', 1) ?: 1 }}" title="Quantity">
    </div>

    <div class="col-md-1 mb-1 text-right">
      <button type="button" class="btn btn-xs btn-danger d-remove" title="Remove"><i class="fas fa-times"></i></button>
    </div>
  </div>
</div>
