<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li>
      <li @if(request()->is('admin'))class="active"@endif>
        <a href="{{ url('admin') }}">
          <i class="menu-icon fa fa-tachometer }}"></i>
          <span class="text-label">Dashboard</span>
        </a>
      </li>
    </li>

  @foreach(config('navigation.admin.toolbar') as $link)
    @can($link['action'], $link['policy'])
      <li @if(request()->is($link['url']) or request()->is($link['url'] . '/*'))class="active"@endif>
          <a href="{{ url($link['url']) }}">
            <i class="menu-icon fa fa-{{ $link['icon'] }}"></i>
            <span class="text-label" style="padding-left: 5px">{{ $link['label'] }}</span>
          </a>
      </li>
    @endcan
  @endforeach

  </ul>
</div>