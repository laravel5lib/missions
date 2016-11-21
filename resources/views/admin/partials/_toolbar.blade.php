<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif> 
        <a href="/admin"><div class="offcanvas-tooltip"><i class="fa fa-tachometer"></i><span>Dashboard</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'campaigns')class="active"@endif>
      <a href="/admin/campaigns"><div class="offcanvas-tooltip"><i class="fa fa-globe"></i><span>Campaigns</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'reservations')class="active"@endif>
      <a href="/admin/reservations/current"><div class="offcanvas-tooltip"><i class="fa fa-ticket"></i><span>Reservations</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/admin/groups"><div class="offcanvas-tooltip"><i class="fa fa-users"></i><span>Groups</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'users')class="active"@endif>
      <a href="/admin/users"><div class="offcanvas-tooltip"><i class="fa fa-user"></i><span>Users</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'uploads')class="active"@endif>
      <a href="/admin/uploads"><div class="offcanvas-tooltip"><i class="fa fa-picture-o"></i><span>Uploads</span></div></a>
    </li>
    <li @if(request()->segment(2) == 'funds')class="active"@endif>
      <a href="/admin/funds"><div class="offcanvas-tooltip"><i class="fa fa-usd" style="margin-left:3px;"></i><span>Funds</span></div></a>
    </li>
  </ul>
</div>