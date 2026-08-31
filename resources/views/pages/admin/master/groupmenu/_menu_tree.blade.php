{{-- Recursive menu tree with access checkboxes.
     Params: $nodes (collection with ->childrenRecursive), $selected (array of ids), $level (int).
     NB: do NOT name the collection $menus — AdminComposer shares a global $menus (sidebar). --}}
@php $level = $level ?? 0; @endphp

<ul style="list-style:none; margin:0; padding-left:{{ $level == 0 ? 0 : 22 }}px;">
  @foreach($nodes as $menu)
    <li class="py-1">
      <label class="mb-0" style="font-weight:{{ $menu->menu_type == 'FOLDER' ? '600' : '400' }};cursor:pointer;">
        <input type="checkbox" class="menu-check" name="menus[]" value="{{ $menu->id }}"
               {{ in_array($menu->id, $selected) ? 'checked' : '' }}>
        @if($menu->menu_icon)<i class="{{ $menu->menu_icon }}"></i>@endif
        {{ $menu->name_in }}
        <small class="text-muted">({{ $menu->menu_type }})</small>
      </label>

      @if($menu->childrenRecursive && $menu->childrenRecursive->count())
        @include('pages.admin.master.groupmenu._menu_tree', [
          'nodes'    => $menu->childrenRecursive,
          'selected' => $selected,
          'level'    => $level + 1,
        ])
      @endif
    </li>
  @endforeach
</ul>
