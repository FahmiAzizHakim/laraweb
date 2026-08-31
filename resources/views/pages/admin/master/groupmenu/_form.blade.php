{{-- Shared form for creating / editing a user access group.
     Expects an optional $data (UserMenuGroup), plus $menuTree and $selected. --}}
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
  <div class="col-md-6">
    <div class="form-group">
      <label>Code <span class="text-danger">*</span></label>
      <input type="text" name="code" class="form-control"
             placeholder="e.g. superadmin, staff"
             value="{{ old('code', $data->code ?? '') }}"
             {{ isset($data) ? 'readonly' : '' }}>
      <small class="form-text text-muted">
        Referenced by users (roles_code). Letters, numbers, _ and - only.
        @isset($data) Locked while editing to keep user links intact. @endisset
      </small>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" name="name" class="form-control"
             placeholder="Group name"
             value="{{ old('name', $data->name ?? '') }}">
    </div>
  </div>
</div>

<div class="form-group">
  <label>Description</label>
  <input type="text" name="desc" class="form-control"
         placeholder="Optional description"
         value="{{ old('desc', $data->desc ?? '') }}">
</div>

<div class="form-group">
  <label>Status</label>
  <div>
    <label class="mr-3">
      <input type="radio" name="activestatus" value="1"
             {{ old('activestatus', $data->activestatus ?? 1) == 1 ? 'checked' : '' }}> Active
    </label>
    <label>
      <input type="radio" name="activestatus" value="0"
             {{ old('activestatus', $data->activestatus ?? 1) == 0 ? 'checked' : '' }}> Inactive
    </label>
  </div>
</div>

<div class="form-group">
  <label>Menu Access</label>
  <div class="mb-2">
    <button type="button" class="btn btn-xs btn-default" id="check-all"><i class="fas fa-check-double"></i> Select all</button>
    <button type="button" class="btn btn-xs btn-default" id="uncheck-all"><i class="far fa-square"></i> Clear all</button>
  </div>
  <div class="border rounded p-3" style="max-height:420px;overflow:auto;background:#fafafa;">
    @if($menuTree->count())
      @include('pages.admin.master.groupmenu._menu_tree', ['nodes' => $menuTree, 'selected' => $selected, 'level' => 0])
    @else
      <span class="text-muted">No menus available.</span>
    @endif
  </div>
  <small class="form-text text-muted">Checked menus will appear in this group's sidebar. Selecting a child also enables its parent folder.</small>
</div>

@section('script')
<script>
  (function () {
    function boxes() { return document.querySelectorAll('input.menu-check'); }

    var checkAll = document.getElementById('check-all');
    var uncheckAll = document.getElementById('uncheck-all');

    if (checkAll) checkAll.addEventListener('click', function () {
      boxes().forEach(function (b) { b.checked = true; });
    });
    if (uncheckAll) uncheckAll.addEventListener('click', function () {
      boxes().forEach(function (b) { b.checked = false; });
    });

    // When a child is checked, auto-check its ancestor folders so the menu shows.
    boxes().forEach(function (b) {
      b.addEventListener('change', function () {
        if (!b.checked) return;
        var li = b.closest('li');
        var parentUl = li ? li.parentElement : null;
        while (parentUl) {
          var parentLi = parentUl.closest('li');
          if (!parentLi) break;
          var parentBox = parentLi.querySelector(':scope > label > input.menu-check');
          if (parentBox) parentBox.checked = true;
          parentUl = parentLi.parentElement;
        }
      });
    });
  })();
</script>
@endsection
