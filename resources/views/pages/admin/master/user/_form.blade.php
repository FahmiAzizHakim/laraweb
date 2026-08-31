{{-- Shared form for creating / editing a user.
     Expects an optional $data (User) and $groups (access groups). --}}
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
  <label>Nama <span class="text-danger">*</span></label>
  <input type="text" name="name" class="form-control"
         placeholder="Full name"
         value="{{ old('name', $data->name ?? '') }}">
</div>

<div class="form-group">
  <label>Email <span class="text-danger">*</span></label>
  <input type="email" name="email" class="form-control"
         placeholder="name@example.com"
         value="{{ old('email', $data->email ?? '') }}">
</div>

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label>Password {!! isset($data) ? '' : '<span class="text-danger">*</span>' !!}</label>
      <input type="password" name="password" class="form-control" autocomplete="new-password"
             placeholder="{{ isset($data) ? 'Leave blank to keep current' : 'Minimum 8 characters' }}">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password"
             placeholder="Repeat password">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Akses Group <span class="text-danger">*</span></label>
  <select name="roles_code" class="form-control">
    <option value="">-- Select group --</option>
    @foreach($groups as $group)
      <option value="{{ $group->code }}"
        {{ old('roles_code', $data->roles_code ?? '') == $group->code ? 'selected' : '' }}>
        {{ $group->name }} ({{ $group->code }})
      </option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label>Status</label>
  <div>
    <label class="mr-3">
      <input type="radio" name="is_active" value="1"
             {{ old('is_active', $data->is_active ?? 1) == 1 ? 'checked' : '' }}> Aktif
    </label>
    <label>
      <input type="radio" name="is_active" value="0"
             {{ old('is_active', $data->is_active ?? 1) == 0 ? 'checked' : '' }}> Nonaktif
    </label>
  </div>
</div>
