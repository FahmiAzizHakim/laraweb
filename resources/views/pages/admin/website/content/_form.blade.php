{{-- Shared form for creating / editing a content article.
     Expects an optional $data (Content model) when editing. --}}
@php $data = $data ?? null; @endphp

@section('css')
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

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
  <input type="text" name="content_title" class="form-control"
         placeholder="Article title"
         value="{{ old('content_title', $data->content_title ?? '') }}">
</div>

<div class="form-group">
  <label>Subtitle</label>
  <input type="text" name="content_subtitle" class="form-control"
         placeholder="Optional subtitle"
         value="{{ old('content_subtitle', $data->content_subtitle ?? '') }}">
</div>

<div class="form-group">
  <label>Cover Image {!! isset($data) ? '' : '<span class="text-danger">*</span>' !!}</label>
  @if(isset($data) && $data->media)
    <div class="mb-2">
      <img src="{{ asset($data->media) }}" alt="current" id="img_preview"
           style="max-height:130px;max-width:320px;background:#eee;padding:4px;border-radius:4px;">
    </div>
  @else
    <div class="mb-2">
      <img src="" alt="" id="img_preview"
           style="display:none;max-height:130px;max-width:320px;background:#eee;padding:4px;border-radius:4px;">
    </div>
  @endif
  <input type="file" name="media" id="media" class="form-control-file" accept="image/*">
  <small class="form-text text-muted">
    Shown in the "News &amp; Articles" card and at the top of the article. Uploaded to <code>public/uploads/content/</code>.
    @isset($data) Leave empty to keep the current image. @endisset
  </small>
</div>

<div class="form-group">
  <label>Description (excerpt)</label>
  <textarea name="description" class="form-control" rows="3"
            placeholder="Short summary shown on the article card">{{ old('description', $data->description ?? '') }}</textarea>
</div>

<div class="form-group">
  <label>Content (body)</label>
  <textarea name="content" id="content_body" class="form-control">{{ old('content', $data->content ?? '') }}</textarea>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Reference</label>
      <input type="text" name="reference" class="form-control"
             placeholder="Optional source / author"
             value="{{ old('reference', $data->reference ?? '') }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Additional URL</label>
      <input type="text" name="additional_url" class="form-control"
             placeholder="https://..."
             value="{{ old('additional_url', $data->additional_url ?? '') }}">
    </div>
  </div>
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
<script src="{{ asset('admin-lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
  $(function () {
    // Rich text editor for the article body.
    $('#content_body').summernote({
      height: 320,
      placeholder: 'Write the article body...',
      dialogsInBody: true,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
      ],
    });
  });

  (function () {
    // Cover image live preview.
    var fileInput = document.getElementById('media');
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
