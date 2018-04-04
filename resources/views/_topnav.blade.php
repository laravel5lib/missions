<div class="navbar navbar-default">
  <div class="container">

    <a href="{{ url('/') }}" class="navbar-brand visible-xs">
      <img class="db-brand-xs" src="/images/mm-icon-red.png" alt="MM">
    </a>

    <div class="navbar-header hidden-xs">
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/mm-logo-horiz.png') }}" alt="Missions.Me">
      </a>
    </div>

    <div class="nav navbar-nav navbar-right dropdown" style="margin-left:75px;">

      @if(auth()->check())
        <li id="userMenu" class="dropdown-toggle hidden-xs text-capitalize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <a href="#">
            <img class="img-xs img-circle av-left"
                 src="{{ image(auth()->user()->getAvatar()->source . '?w=100') }}"
                 alt="{{ auth()->user()->name }}">
            {{ auth()->user()->name }} <i class="fa fa-angle-down"></i>
          </a>
        </li>
        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="userMenu">
          <li>
            <a href="{{ url('/dashboard/settings') }}" id="top-profile-link">
              <i class="fa fa-user-circle"></i> My Account
            </a>
          </li>
          <li>
            <a href="{{ url('/dashboard') }}">
              <i class="fa fa-dashboard"></i> Dashboard
            </a>
          </li>

          @can('access_backend')
            <li><a href="{{ url('/admin') }}"><i class="fa fa-gears"></i> Admin</a></li>
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
        <li class="hidden-xs">
            <a href="{{ url('/login') }}"><h5 style="display:inline-block;margin-right:15px;">Log In</h5></a>
        </li>
      @endif

      @unless(isset($fundraiser) or isset($isProfile) or auth()->check())
      <li>
        <div class="hidden-xs" style="margin-right:20px;">
          <a class="btn btn-default-hollow" href="{{ url('/donate') }}">Donate</a>
        </div>
      </li>
      @endunless

      <top-nav managing="{{ (auth()->check() && auth()->user()->managing()->count()) or 0 }}" fundraiser="{{ request()->route()->named('fundraiser') or 0 }}"></top-nav>

    </div><!-- /.navbar-right -->
  </div><!-- end container -->
</div><!-- end navbar -->