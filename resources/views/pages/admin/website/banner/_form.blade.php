{{-- Shared form for creating / editing a hero banner.
     Expects an optional $data (Banner model) when editing. --}}
@php $data = $data ?? null; @endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="form-group">
  <label>Title <span class="text-danger">*</span></label>
  <input type="text" name="banner_title" class="form-control"
         placeholder="e.g. Hero 1"
         value="{{ old('banner_title', $data->banner_title ?? '') }}">
</div>

<div class="form-group">
  <label>Image {!! isset($data) ? '' : '<span class="text-danger">*</span>' !!}</label>
  @if(isset($data) && $data->banner_img)
    <div class="mb-2">
      <img src="{{ asset($data->banner_img) }}" alt="current" id="img_preview"
           style="max-height:130px;max-width:320px;background:#eee;padding:4px;border-radius:4px;">
    </div>
  @else
    <div class="mb-2">
      <img src="" alt="" id="img_preview"
           style="display:none;max-height:130px;max-width:320px;background:#eee;padding:4px;border-radius:4px;">
    </div>
  @endif
  <input type="file" name="banner_img" id="banner_img" class="form-control-file" accept="image/*">
  <small class="form-text text-muted">
    Recommended a wide landscape image. Uploaded to <code>public/uploads/banner/</code>.
    @isset($data) Leave empty to keep the current image. @endisset
  </small>
</div>

<div class="form-group">
  <label>Text</label>
  <textarea name="banner_text" class="form-control" rows="3"
            placeholder="Optional caption / description">{{ old('banner_text', $data->banner_text ?? '') }}</textarea>
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
    var fileInput = document.getElementById('banner_img');
    var preview   = document.getElementById('img_preview');
    if (fileInput && preview) {
      fileInput.addEventListener('change', function () {
        if (fileInput.files && fileInput.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'inline-block';
          };
          reader.readAsDataURL(fileInput.files[0]);
        }
      });
    }
  })();
</script>
@endsection
