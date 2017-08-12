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
      <li id="userMenu" class="dropdown-toggle hidden-xs text-capitalize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><a href="#"><img class="img-xs img-circle av-left" src="{{ image(auth()->user()->getAvatar()->source . '?w=100') }}" alt="{{ auth()->user()->name }}"> {{ auth()->user()->name }} <i class="fa fa-angle-down"></i></a></li>
      <ul class="dropdown-menu" aria-labelledby="userMenu">
        <li><a href="{{ url(auth()->user()->slug->url) }}" id="top-profile-link"><i class="fa fa-user"></i> Profile</a></li>
        <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        @can ('access-admin')
          <li><a href="{{ url('/admin') }}">Admin</a></li>
        @endcan
        <li role="separator" class="divider"></li>
        @unless(request()->cookie('impersonate'))
          <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
              <i class="fa fa-sign-out"></i> Log Out
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </li>
        @endunless
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