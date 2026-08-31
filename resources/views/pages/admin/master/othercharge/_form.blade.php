{{-- Shared form for creating / editing an other charge.
     Expects an optional $data (OtherCharge model) when editing. --}}
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
      <label>Code <span class="text-danger">*</span></label>
      <input type="text" name="code" class="form-control"
             placeholder="e.g. HANDLING"
             value="{{ old('code', $data->code ?? '') }}">
    </div>
  </div>
  <div class="col-md-8">
    <div class="form-group">
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" name="name" class="form-control"
             placeholder="e.g. Handling Fee"
             value="{{ old('name', $data->name ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Description</label>
  <textarea name="description" class="form-control" rows="3"
            placeholder="Describe the charge">{{ old('description', $data->description ?? '') }}</textarea>
</div>

<div class="form-group">
  <label>Price <span class="text-danger">*</span></label>
  <input type="number" name="price" class="form-control" step="0.01" min="0"
         placeholder="0.00"
         value="{{ old('price', isset($data) ? $data->price : '0.00') }}">
</div>

<div class="form-group">
  <label>Remark</label>
  <textarea name="remark" class="form-control" rows="2"
            placeholder="Internal note (optional)">{{ old('remark', $data->remark ?? '') }}</textarea>
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
