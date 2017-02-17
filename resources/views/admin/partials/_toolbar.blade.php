<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif> 
        <a href="/admin"><i class="menu-icon fa fa-tachometer"></i><span class="text-label">Dashboard</span></a>
    </li>
    <li @if(in_array(request()->segment(2), ['campaigns', 'trips']))class="active"@endif>
      <a href="/admin/campaigns"><i class="menu-icon fa fa-globe" style="margin-left:2px;margin-right:24px;"></i><span class="text-label">Campaigns</span></a>
    </li>
    <li @if(in_array(request()->segment(2), ['reservations', 'interests']))class="active"@endif>
      <a href="/admin/reservations/current"><i class="menu-icon fa fa-ticket"></i><span class="text-label">Reservations</span></a>
    </li>
    <li @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/admin/groups"><i class="menu-icon fa fa-users"></i><span class="text-label">Groups</span></a>
    </li>
    <li @if(request()->segment(2) == 'users')class="active"@endif>
      <a href="/admin/users"><i class="menu-icon fa fa-user" style="margin-left:3px;margin-right:26px;"></i><span class="text-label">Users</span></a>
    </li>
    <li @if(request()->segment(2) == 'uploads')class="active"@endif>
      <a href="/admin/uploads"><i class="menu-icon fa fa-picture-o"></i><span class="text-label">Uploads</span></a>
    </li>
    <li @if(in_array(request()->segment(2), ['funds', 'transactions', 'donors']))class="active"@endif>
      <a href="/admin/funds"><i class="menu-icon fa fa-usd" style="margin-left:3px;margin-right:27px;"></i><span class="text-label">Funds</span></a>
    </li>
    <li @if(in_array(request()->segment(2), ['causes', 'projects', 'initiatives']))class="active"@endif>
        <a href="/admin/causes"><i class="menu-icon fa fa-tint" style="margin-left:3px;margin-right:27px;"></i><span class="text-label">Projects</span></a>
    </li>
    <li @if(request()->segment(2) == 'records')class="active"@endif>
      <a href="/admin/records"><i class="menu-icon fa fa-archive"></i><span class="text-label">Records</span></a>
    </li>
  </ul>
</div>