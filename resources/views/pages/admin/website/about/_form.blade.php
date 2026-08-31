{{-- Shared form for creating / editing an About block.
     Expects an optional $data (About model) when editing. --}}
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

<div class="row">
  <div class="col-md-8">
    <div class="form-group">
      <label>Title <span class="text-danger">*</span></label>
      <input type="text" name="about_title" class="form-control"
             placeholder="e.g. Tentang Layanan Kami"
             value="{{ old('about_title', $data->about_title ?? '') }}">
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Order</label>
      <input type="number" min="0" name="order" class="form-control"
             value="{{ old('order', $data->order ?? '') }}">
      <small class="form-text text-muted">Lowest shows first. Leave empty to add at the end.</small>
    </div>
  </div>
</div>

<div class="form-group">
  <label>Subtitle</label>
  <input type="text" name="about_subtitle" class="form-control"
         placeholder="Optional line under the title"
         value="{{ old('about_subtitle', $data->about_subtitle ?? '') }}">
</div>

<div class="form-group">
  <label>Content</label>
  <textarea name="about_content" class="form-control" rows="9"
            placeholder="One paragraph per block. Leave a blank line between paragraphs.">{{ old('about_content', $data->about_content ?? '') }}</textarea>
  <small class="form-text text-muted">
    Plain text. Separate paragraphs with a blank line &mdash; the site renders each block as its own paragraph.
  </small>
</div>

<div class="form-group">
  <label>Image</label>
  <div class="mb-2">
    <img src="{{ isset($data) && $data->about_image ? asset($data->about_image) : '' }}" alt="" id="img_preview"
         style="{{ isset($data) && $data->about_image ? '' : 'display:none;' }}max-height:150px;max-width:340px;background:#eee;padding:4px;border-radius:4px;">
  </div>
  <input type="file" name="about_image" id="about_image" class="form-control-file" accept="image/*">
  <small class="form-text text-muted">
    Shown beside the text. Uploaded to <code>public/uploads/about/</code>.
    @isset($data) Leave empty to keep the current image. @endisset
  </small>
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
    var fileInput = document.getElementById('about_image');
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
