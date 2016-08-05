<div class="navbar navbar-default offcanvas-mm visible-xs visible-sm">
  <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="visible-xs" href="#"><img class="img-xs db-brand-xs" src="/images/mm-icon-red.png" alt="MM"></a>
</div>
<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header hidden-xs">
      <a class="navbar-brand" href="#"><img src="/images/mm-logo-horiz.png" alt="Missions.Me"></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="nav navbar-nav navbar-right dropdown">

      @if(auth()->check())
      <li><a class="btn btn-primary" href="#">Donate</a></li>
      <li id="userMenu" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><a href="#"><img class="img-xs img-circle av-left"src="/images/nelson-prof-pic.jpg" alt="Zech Nelson"> {{ auth()->user()->name }} <i class="fa fa-angle-down"></i></a></li>
      <ul class="dropdown-menu" aria-labelledby="userMenu">
        <li><a href="#">My Profile</a></li>
        <li><a href="#">My Group</a></li>
        <li><a href="/dashboard">Dashboard</a></li>
        @can ('view-admin')
          <li><a href="/admin">Admin</a></li>
        @endcan
        <li role="separator" class="divider"></li>
        <li><a href="/logout">Sign Out</a></li>
      </ul>
      @else
      <li>
        <div class="btn-group">
          <a class="btn btn-link" href="/login">Login</a>
          <a class="btn btn-primary" href="/login">Sign Up</a>
        </div>
      </li>
      @endif
      <top-nav></top-nav>
    </div><!-- /.navbar-right -->
  </div><!-- end container -->
</div><!-- end navbar -->