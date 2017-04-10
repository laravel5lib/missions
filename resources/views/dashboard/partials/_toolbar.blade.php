<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif><a href="/dashboard">
        <i class="menu-icon fa fa-tachometer"></i><span class="text-label">Dashboard</span></a>
    </li>
    <li @if(request()->segment(2) == 'settings')class="active"@endif>
      <a href="/dashboard/settings"><i class="menu-icon fa fa-cog" style="margin-left:1px;margin-right:24px;"></i><span class="text-label">Settings</span></a>
    </li>
    <li @if(request()->segment(2) == 'reservations')class="active"@endif>
      <a href="/dashboard/reservations"><i class="menu-icon fa fa-ticket"></i><span class="text-label">Reservations</span></a>
    </li>
    <li @if(request()->segment(2) == 'records')class="active"@endif>
      <a href="/dashboard/records"><i class="menu-icon fa fa-archive"></i><span class="text-label">Records</span></a>
    </li>
    @if(auth()->user()->managing()->count())
    <li @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/dashboard/groups"><i class="menu-icon fa fa-users"></i><span class="text-label">Groups</span></a>
    </li>
    @endif
    <li @if(request()->segment(2) == 'projects')class="active"@endif>
      <a href="/dashboard/projects"><i class="menu-icon fa fa-tint" style="margin-left:3px;margin-right:27px;"></i><span class="text-label">Projects</span></a>
    </li>
    <li @if(request()->segment(2) == 'reports')class="active"@endif>
      <a href="/dashboard/reports"><i class="menu-icon fa fa-line-chart"></i><span class="text-label">Reports</span></a>
    </li>
    <li>
      <a v-on:click="startTour"><i class="menu-icon fa fa-question-circle-o" style="margin-left:3px;margin-right:27px;"></i><span class="text-label">Tour</span></a>
    </li>
  </ul>
</div>