{{-- Shared form for creating / editing a bank.
     Expects an optional $data (Bank model) when editing. --}}
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
      <label>Bank Name <span class="text-danger">*</span></label>
      <input type="text" name="bank_name" class="form-control"
             placeholder="e.g. Bank Central Asia (BCA)"
             value="{{ old('bank_name', $data->bank_name ?? '') }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Branch</label>
      <input type="text" name="branch" class="form-control"
             placeholder="e.g. KCP Jakarta"
             value="{{ old('branch', $data->branch ?? '') }}">
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Account Number <span class="text-danger">*</span></label>
      <input type="text" name="bank_account" class="form-control"
             placeholder="e.g. 1234567890"
             value="{{ old('bank_account', $data->bank_account ?? '') }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Account Name <span class="text-danger">*</span></label>
      <input type="text" name="account_name" class="form-control"
             placeholder="Account holder name"
             value="{{ old('account_name', $data->account_name ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Logo</label>
  @if(isset($data) && $data->logo)
    <div class="mb-2"><img src="{{ asset($data->logo) }}" id="logo_preview" style="max-height:50px;max-width:150px;background:#eee;padding:4px;border-radius:4px;"></div>
  @else
    <div class="mb-2"><img src="" id="logo_preview" style="display:none;max-height:50px;max-width:150px;background:#eee;padding:4px;border-radius:4px;"></div>
  @endif
  <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*">
  <small class="form-text text-muted">Optional bank logo. @isset($data) Leave empty to keep current. @endisset</small>
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
    var input = document.getElementById('logo'), img = document.getElementById('logo_preview');
    if (!input || !img) return;
    input.addEventListener('change', function () {
      if (input.files && input.files[0]) {
        var r = new FileReader();
        r.onload = function (e) { img.src = e.target.result; img.style.display = 'inline-block'; };
        r.readAsDataURL(input.files[0]);
      }
    });
  })();
</script>
@endsection
