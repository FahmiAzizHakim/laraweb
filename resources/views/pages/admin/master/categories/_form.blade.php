{{-- Shared form for creating / editing a category. Expects optional $data and $parents. --}}
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
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" name="category_name" class="form-control"
             value="{{ old('category_name', $data->category_name ?? '') }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Code <span class="text-danger">*</span></label>
      <input type="text" name="category_code" class="form-control"
             placeholder="Unique per website"
             value="{{ old('category_code', $data->category_code ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Parent Category</label>
  <select name="parent_id" class="form-control">
    <option value="">-- None (top level) --</option>
    @foreach($parents as $parent)
      <option value="{{ $parent->id }}"
        {{ old('parent_id', $data->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
        {{ $parent->category_name }} ({{ $parent->category_code }})
      </option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label>Remark</label>
  <textarea name="remark" class="form-control" rows="2">{{ old('remark', $data->remark ?? '') }}</textarea>
</div>

<div class="form-group">
  <label>Status</label>
  <div>
    <label class="mr-3"><input type="radio" name="is_active" value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'checked' : '' }}> Active</label>
    <label><input type="radio" name="is_active" value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'checked' : '' }}> Inactive</label>
  </div>
</div>
