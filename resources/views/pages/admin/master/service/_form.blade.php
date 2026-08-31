{{-- Shared form for creating / editing a service.
     Expects an optional $data (Service model) when editing. --}}
@php $data = $data ?? null; @endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="form-group">
  <label>Service Name <span class="text-danger">*</span></label>
  <input type="text" name="service_name" class="form-control"
         placeholder="e.g. Cargo Shipping"
         value="{{ old('service_name', $data->service_name ?? '') }}">
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="service_title" class="form-control"
             placeholder="Headline"
             value="{{ old('service_title', $data->service_title ?? '') }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Subtitle</label>
      <input type="text" name="service_subtitle" class="form-control"
             placeholder="Sub headline"
             value="{{ old('service_subtitle', $data->service_subtitle ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Description</label>
  <textarea name="service_description" class="form-control" rows="3"
            placeholder="Describe the service">{{ old('service_description', $data->service_description ?? '') }}</textarea>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Image</label>
      @if(isset($data) && $data->service_image)
        <div class="mb-2"><img src="{{ asset($data->service_image) }}" id="image_preview" style="max-height:110px;max-width:220px;background:#eee;padding:4px;border-radius:4px;"></div>
      @else
        <div class="mb-2"><img src="" id="image_preview" style="display:none;max-height:110px;max-width:220px;background:#eee;padding:4px;border-radius:4px;"></div>
      @endif
      <input type="file" name="service_image" id="service_image" class="form-control-file" accept="image/*">
      <small class="form-text text-muted">Uploaded to <code>public/uploads/service/</code>. @isset($data) Leave empty to keep current. @endisset</small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Icon</label>
      @if(isset($data) && $data->service_icon)
        <div class="mb-2"><img src="{{ asset($data->service_icon) }}" id="icon_preview" style="max-height:60px;max-width:60px;background:#eee;padding:4px;border-radius:4px;"></div>
      @else
        <div class="mb-2"><img src="" id="icon_preview" style="display:none;max-height:60px;max-width:60px;background:#eee;padding:4px;border-radius:4px;"></div>
      @endif
      <input type="file" name="service_icon" id="service_icon" class="form-control-file" accept="image/*">
      <small class="form-text text-muted">Small icon image. @isset($data) Leave empty to keep current. @endisset</small>
    </div>
  </div>
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
    preview('service_image', 'image_preview');
    preview('service_icon', 'icon_preview');
  })();
</script>
@endsection
