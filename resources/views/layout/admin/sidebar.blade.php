<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach($menus as $menu)
      @if($menu['menu_type'] == "FOLDER")
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="{{ $menu['menu_icon'] }}"></i>
            <p>
              {{ $menu['name_in'] }}
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
              @foreach($menu['children'] as $child)
                @if($child['menu_type'] == "FOLDER")
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                        {{ $child['name_in'] }}
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                      @foreach($child['children'] as $grchild)
                        <li class="nav-item">
                          <a href="{{ url($grchild['menu_url']) }}" class="nav-link">
                            <!-- <i class="{{ $grchild['menu_icon'] }}"></i> -->
                            <p>{{ $grchild['name_in'] }}</p>
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                @else
                  <li class="nav-item">
                    <a href="{{ url($child['menu_url']) }}" class="nav-link">
                      <!-- <i class="{{ $child['menu_icon'] }}"></i> -->
                      <p>{{ $child['name_in'] }}</p>
                    </a>
                  </li>
                @endif
              @endforeach
          </ul>
        </li>
      @else
        <li class="nav-item">
          <a href="{{ url($menu['menu_url']) }}" class="nav-link">
            <i class="{{ $menu['menu_icon'] }}"></i>
            <p>
              {{ $menu['name_in'] }}
              @if($menu['menu_url'] === 'message' && ($unreadMessages ?? 0) > 0)
                <span class="badge badge-danger right">{{ $unreadMessages }}</span>
              @endif
            </p>
          </a>
        </li>
      @endif

    @endforeach

</ul>
