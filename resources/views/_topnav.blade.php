<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <a href="/" class="navbar-brand visible-xs"><img class="db-brand-xs" src="/images/mm-icon-red.png" alt="MM"></a>
    <div class="navbar-header hidden-xs">
      <a class="navbar-brand" href="/"><img src="/images/mm-logo-horiz.png" alt="Missions.Me"></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="nav navbar-nav navbar-right dropdown" style="margin-left:75px;">
      @if(auth()->check())
      <li><a class="navbar-btn btn btn-primary hidden-xs" href="/donate">Donate</a></li>
      <li id="userMenu" class="dropdown-toggle hidden-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><a href="#"><img class="img-xs img-circle av-left" src="{{ image(auth()->user()->getAvatar()->source . '?w=100') }}" alt="{{ auth()->user()->name }}"> {{ auth()->user()->name }} <i class="fa fa-angle-down"></i></a></li>
      <ul class="dropdown-menu" aria-labelledby="userMenu">
        <li><a href="{{ url(auth()->user()->slug->url) }}">My Profile</a></li>
        @can('access-dashboard')
          <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        @endcan
        @can ('access-admin')
          <li><a href="{{ url('/admin') }}">Admin</a></li>
        @endcan
        <li role="separator" class="divider"></li>
        <li><a href="{{ url('/logout') }}">Sign Out <i style="margin-top:6px;" class="fa fa-chevron-right pull-right"></i></a></li>
        @if(request()->cookie('impersonate'))
          <li><a href="{{ url('/admin/users/stop') }}">Stop Impersonating</a></li>
        @endif
      </ul>
      @else
      <li>
        <div class="btn-group hidden-xs" style="margin-right:20px;">
          <a class="btn btn-link" href="{{ url('/login') }}">Login</a>
          <a class="btn btn-primary" href="{{ url('/login?action=signup') }}">Sign Up</a>
        </div>
      </li>
      @endif
      <top-nav managing="{{ (auth()->check() && auth()->user()->managing()->count()) or 0 }}">
      </top-nav>
    </div><!-- /.navbar-right -->
  </div><!-- end container -->
</div><!-- end navbar -->