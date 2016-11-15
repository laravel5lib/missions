<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif> 
        <a href="/admin"><i class="fa fa-tachometer"></i></a>
    </li>
    <li @if(request()->segment(2) == 'campaigns')class="active"@endif>
      <a href="/admin/campaigns"><i class="fa fa-globe"></i></a>
    </li>
    <li @if(request()->segment(2) == 'reservations')class="active"@endif>
      <a href="/admin/reservations/current"><i class="fa fa-ticket"></i></a>
    </li>
    <li @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/admin/groups"><i class="fa fa-users"></i></a>
    </li>
    <li @if(request()->segment(2) == 'users')class="active"@endif>
      <a href="/admin/users"><i class="fa fa-user"></i></a>
    </li>
    <li @if(request()->segment(2) == 'uploads')class="active"@endif>
      <a href="/admin/uploads"><i class="fa fa-picture-o"></i></a>
    </li>
    <li @if(request()->segment(2) == 'funds')class="active"@endif>
      <a href="/admin/funds"><i class="fa fa-usd"></i></a>
    </li>
  </ul>
</div>