<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="visible-xs">
      <a class="navbar-brand visible-xs" href="#"><img class="db-brand-xs" src="/images/mm-icon-red.png" alt="MM"></a>
    </div>
    <div class="navbar-header hidden-xs">
      <a class="navbar-brand" href="#"><img src="/images/mm-logo-horiz.png" alt="Missions.Me"></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="nav navbar-nav navbar-right dropdown">
      @if(auth()->check())
      <li><a class="btn btn-primary hidden-xs" href="#">Donate</a></li>
      <li id="userMenu" class="dropdown-toggle hidden-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><a href="#"><img class="img-xs img-circle av-left" src="{{ auth()->user()->avatar->source }}" alt="{{ auth()->user()->name }}"> {{ auth()->user()->name }} <i class="fa fa-angle-down"></i></a></li>
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
        <div class="btn-group hidden-xs">
          <a class="btn btn-link" href="/login">Login</a>
          <a class="btn btn-primary" href="/login">Sign Up</a>
        </div>
      </li>
      @endif
      <top-nav auth="{{ auth()->check()? 1 : 0 }}" admin="{{ (auth()->check() && auth()->user()->can('view-admin'))? 1 : 0 }}" name="{{ auth()->user()->name }}" avatar="{{ auth()->user()->avatar->source }}"></top-nav>
    </div><!-- /.navbar-right -->
  </div><!-- end container -->
</div><!-- end navbar -->