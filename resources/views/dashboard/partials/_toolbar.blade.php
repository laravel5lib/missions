<div id="myNavmenu" class="navmenu navmenu-inverse navmenu-fixed-left offcanvas-sm">
  <ul class="nav navmenu-nav">
    <li @if(request()->segment(2) == '')class="active"@endif><a href="/dashboard">
        <i class="fa fa-tachometer"></i></a>
    </li>
    <li @if(request()->segment(2) == 'settings')class="active"@endif>
      <a href="/dashboard/settings"><i class="fa fa-cog"></i></a>
    </li>
    <li @if(request()->segment(2) == 'reservations')class="active"@endif>
      <a href="/dashboard/reservations"><i class="fa fa-ticket"></i></a>
    </li>
    <li @if(request()->segment(2) == 'records')class="active"@endif>
      <a href="/dashboard/records"><i class="fa fa-archive"></i></a>
    </li>
    @if(auth()->user()->managing()->count())
    <li @if(request()->segment(2) == 'groups')class="active"@endif>
      <a href="/dashboard/groups"><i class="fa fa-users"></i></a>
    </li>
    @endif
    <li @if(request()->segment(2) == 'projects')class="active"@endif>
      <a href="/dashboard/projects"><i class="fa fa-tint" style="margin-left:3px;"></i></a>
    </li>
    <li style="padding:0;border-top:1px solid #1d1d1d;border-bottom:1px solid #1d1d1d;">
      <a class="text-uppercase" style="text-align:center;font-weight:400;padding:10px 0px;" v-on:click="startTour"><i class="fa fa-question-circle-o" style="font-size:14px;display:block;"></i><span style="font-size:8px;margin-top:0;">Tour</span></a>
    </li>
  </ul>
</div>