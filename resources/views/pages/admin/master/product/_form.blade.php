{{-- Shared form for creating / editing a product.
     Expects optional $data (Product), $services, $categories, $selected (category ids). --}}
@php $data = $data ?? null; @endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Service <span class="text-danger">*</span></label>
      <select name="service_id" class="form-control">
        <option value="">-- Select service --</option>
        @foreach($services as $svc)
          <option value="{{ $svc->id }}" {{ old('service_id', $data->service_id ?? '') == $svc->id ? 'selected' : '' }}>
            {{ $svc->service_name }}
          </option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" name="products_name" class="form-control" value="{{ old('products_name', $data->products_name ?? '') }}">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Code <span class="text-danger">*</span></label>
      <input type="text" name="products_code" class="form-control" value="{{ old('products_code', $data->products_code ?? '') }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Price</label>
      <input type="number" step="0.01" min="0" name="products_price" class="form-control" value="{{ old('products_price', $data->products_price ?? 0) }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Weight (g)</label>
      <input type="number" min="0" name="products_weight" class="form-control" value="{{ old('products_weight', $data->products_weight ?? '') }}">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Width (cm)</label>
      <input type="number" min="0" name="products_width" class="form-control" value="{{ old('products_width', $data->products_width ?? '') }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Length (cm)</label>
      <input type="number" min="0" name="products_length" class="form-control" value="{{ old('products_length', $data->products_length ?? '') }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Height (cm)</label>
      <input type="number" min="0" name="products_height" class="form-control" value="{{ old('products_height', $data->products_height ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Description</label>
  <textarea name="products_description" class="form-control" rows="3">{{ old('products_description', $data->products_description ?? '') }}</textarea>
</div>

<div class="form-group">
  <label>Categories</label>
  <div class="border rounded p-2" style="max-height:180px;overflow:auto;background:#fafafa;">
    @forelse($categories as $cat)
      <label class="d-block mb-1" style="font-weight:400;cursor:pointer;">
        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
               {{ in_array($cat->id, old('categories', $selected ?? [])) ? 'checked' : '' }}>
        {{ $cat->category_name }} <small class="text-muted">({{ $cat->category_code }})</small>
      </label>
    @empty
      <span class="text-muted">No categories yet. Create some under Master data &raquo; Categories.</span>
    @endforelse
  </div>
</div>

<div class="form-group">
  <label>Images</label>

  @if(isset($data) && $data->images->count())
    <div class="row mb-2">
      @foreach($data->images as $img)
        <div class="col-md-3 mb-2 text-center">
          <img src="{{ asset($img->image_url) }}" alt="" style="max-height:90px;max-width:100%;border:1px solid #dee2e6;border-radius:4px;">
          <label class="d-block small mt-1 mb-0" style="cursor:pointer;">
            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> remove
          </label>
        </div>
      @endforeach
    </div>
  @endif

  <input type="file" name="images[]" class="form-control-file" accept="image/*" multiple>
  <small class="form-text text-muted">You can select multiple images. Uploaded to <code>public/uploads/product/</code>.</small>
</div>

<div class="form-group">
  <label>Variants</label>
  <small class="form-text text-muted mb-2">Optional. Add variants (e.g. sizes, models). If a variant has no price it falls back to the product price.</small>

  @php
    $oldVariants = old('variants');
    $existingVariants = isset($data) ? $data->variants : collect();
    $startIndex = is_array($oldVariants) ? count($oldVariants) : $existingVariants->count();
  @endphp

  <div id="variants-wrap">
    @if(is_array($oldVariants))
      @foreach($oldVariants as $i => $ov)
        @include('pages.admin.master.product._variant_row', ['i' => $i, 'v' => $ov, 'vid' => $ov['id'] ?? null])
      @endforeach
    @else
      @foreach($existingVariants as $i => $variant)
        @include('pages.admin.master.product._variant_row', ['i' => $i, 'v' => $variant, 'vid' => $variant->id])
      @endforeach
    @endif
  </div>

  <button type="button" class="btn btn-sm btn-default" id="add-variant"><i class="fas fa-plus"></i> Add Variant</button>

  <template id="variant-template">
    @include('pages.admin.master.product._variant_row', ['i' => '__INDEX__', 'v' => null, 'vid' => null])
  </template>
</div>

<div class="form-group">
  <label>Specifications</label>
  <small class="form-text text-muted mb-2">Optional attribute / value pairs (e.g. RAM &rarr; 16GB, CPU &rarr; AMD Ryzen 5). Rows with an empty attribute or value are ignored.</small>

  @php
    $oldSpecs = old('specifications');
    $existingSpecs = isset($data) ? $data->specifications : collect();
    $specStartIndex = is_array($oldSpecs) ? count($oldSpecs) : $existingSpecs->count();
  @endphp

  <div id="specs-wrap">
    @if(is_array($oldSpecs))
      @foreach($oldSpecs as $i => $os)
        @include('pages.admin.master.product._specification_row', ['i' => $i, 's' => $os, 'sid' => $os['id'] ?? null])
      @endforeach
    @else
      @foreach($existingSpecs as $i => $spec)
        @include('pages.admin.master.product._specification_row', ['i' => $i, 's' => $spec, 'sid' => $spec->id])
      @endforeach
    @endif
  </div>

  <button type="button" class="btn btn-sm btn-default" id="add-spec"><i class="fas fa-plus"></i> Add Specification</button>

  <template id="spec-template">
    @include('pages.admin.master.product._specification_row', ['i' => '__INDEX__', 's' => null, 'sid' => null])
  </template>
</div>

<div class="form-group">
  <label>Remark</label>
  <textarea name="remark" class="form-control" rows="2">{{ old('remark', $data->remark ?? '') }}</textarea>
</div>

@section('script')
<script>
  (function () {
    // Repeatable rows (variants, specifications) share the same add / remove behaviour.
    function repeater(opt) {
      var wrap = document.getElementById(opt.wrap);
      var tpl  = document.getElementById(opt.template);
      var btn  = document.getElementById(opt.addButton);
      var idx  = opt.startIndex;

      if (!wrap || !tpl || !btn) return;

      btn.addEventListener('click', function () {
        var html = tpl.innerHTML.replace(/__INDEX__/g, idx++);
        var holder = document.createElement('div');
        holder.innerHTML = html.trim();
        wrap.appendChild(holder.firstElementChild);
      });

      wrap.addEventListener('click', function (e) {
        var hit = e.target.closest(opt.removeButton);
        if (!hit) return;
        var row = hit.closest(opt.row);
        var idInput = row.querySelector('input[name$="[id]"]');
        if (idInput && idInput.value) {
          // Existing record: flag for deletion and hide.
          row.querySelector(opt.removeFlag).value = '1';
          row.style.display = 'none';
        } else {
          row.remove();
        }
      });
    }

    repeater({
      wrap: 'variants-wrap', template: 'variant-template', addButton: 'add-variant',
      row: '.variant-row', removeButton: '.v-remove', removeFlag: '.v-remove-flag',
      startIndex: {{ $startIndex }}
    });

    repeater({
      wrap: 'specs-wrap', template: 'spec-template', addButton: 'add-spec',
      row: '.spec-row', removeButton: '.s-remove', removeFlag: '.s-remove-flag',
      startIndex: {{ $specStartIndex }}
    });
  })();
</script>
@endsection

<div class="form-group">
  <label>Status</label>
  <div>
    <label class="mr-3"><input type="radio" name="is_active" value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'checked' : '' }}> Active</label>
    <label><input type="radio" name="is_active" value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive</label>
  </div>
</div>
