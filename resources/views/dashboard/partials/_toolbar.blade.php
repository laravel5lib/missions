<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li class="offcanvas-tooltip" @if(request()->segment(2) == '')class="active"@endif><a href="/dashboard">
        <i class="fa fa-tachometer"></i><span>Dashboard</span></a>
    </li>
    <li class="offcanvas-tooltip" @if(request()->segment(2) == 'settings')class="active"@endif>
      <a href="/dashboard/settings"><i class="fa fa-cog"></i><span>Settings</span></a>
    </li>
    <li class="offcanvas-tooltip" @if(request()->segment(2) == 'reservations')class="active"@endif>
      <a href="/dashboard/reservations"><i class="fa fa-ticket"></i><span>Reservations</span></a>
    </li>
    <li class="offcanvas-tooltip" @if(request()->segment(2) == 'records')class="active"@endif>
      <a href="/dashboard/records"><i class="fa fa-archive"></i><span>Records</span></a>
    </li>
    @if(auth()->user()->managing()->count())
    <li class="offcanvas-tooltip" @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/dashboard/groups"><i class="fa fa-users"></i><span>Groups</span></a>
    </li>
    @endif
    <li class="offcanvas-tooltip" @if(request()->segment(2) == 'projects')class="active"@endif>
      <a href="/dashboard/projects"><i class="fa fa-tint" style="margin-left:3px;"></i><span>Projects</span></a>
    </li>
  </ul>
</div>