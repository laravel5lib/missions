<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif> 
        <a href="/admin"><i class="fa fa-tachometer"></i></a>
    </li>
    <li @if(in_array(request()->segment(2), ['campaigns', 'trips']))class="active"@endif>
      <a href="/admin/campaigns"><i class="fa fa-globe"></i></a>
    </li>
    <li @if(in_array(request()->segment(2), ['reservations', 'interests']))class="active"@endif>
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
    <li @if(in_array(request()->segment(2), ['funds', 'transactions', 'donors']))class="active"@endif>
      <a href="/admin/funds"><i class="fa fa-usd" style="margin-left:3px;"></i></a>
    </li>
    <li @if(in_array(request()->segment(2), ['causes', 'projects', 'initiatives']))class="active"@endif>
        <a href="/admin/causes"><i class="fa fa-tint" style="margin-left:3px;"></i></a>
    </li>
    <li @if(request()->segment(2) == 'records')class="active"@endif>
      <a href="/admin/records"><i class="fa fa-archive"></i></a>
    </li>
  </ul>
</div>