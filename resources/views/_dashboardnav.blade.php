<div class="navbar navbar-default" style="padding-left: 0; border-bottom: 1px solid #eee;">
  <div class="container-fluid">
    <div style="display: flex; justify-content: space-between;">
      <div>

        <a href="{{ url('/dashboard') }}" class="navbar-brand visible-xs">
          <img class="db-brand-xs" src="/images/mm-icon-red.png" alt="MM">
        </a>

        <div class="navbar-header hidden-xs">
          <a class="navbar-brand" href="{{ url('/dashboard') }}">
            <img src="{{ asset('images/mm-logo-horiz.png') }}" alt="Missions.Me">
          </a>
        </div>
      </div>

        <div class="hidden-xs hidden-sm text-center">
          <div class="nav navbar-nav navbar-center" style="float: none; display:inline-block; vertical-align: middle;">
            <li class="{{ request()->segment(2) == 'reservations' ? 'active' : '' }}">
              <a href="/dashboard/reservations">My Trip</a>
            </li>
            <li class="{{ request()->segment(2) == 'records' ? 'active' : '' }}">
              <a href="/dashboard/records/passports">My Documents</a>
            </li>

            @if(auth()->user()->managing()->count())
            <li class="{{ request()->segment(2) == 'groups' ? 'active' : '' }}">
              <a href="/dashboard/groups">My Team</a>
            </li>
            <li class="{{ request()->segment(2) == 'reports' ? 'active' : '' }}">
              <a href="/dashboard/reports">My Reports</a>
            </li>
            @endif
          </div>
        </div>

      <div>
        <ul class="nav navbar-nav navbar-right dropdown">

          @if(auth()->check())
            <li id="userMenu" class="dropdown-toggle hidden-xs text-capitalize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <a href="#">
                <img class="img-xs img-circle av-left"
                    src="{{ image(auth()->user()->getAvatar()->source . '?w=100') }}"
                    alt="{{ auth()->user()->name }}">
                <span class="hidden-md">{{ auth()->user()->name }}</span> <i class="fa fa-angle-down"></i>
              </a>
            </li>
            <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="userMenu">
              <li>
                <a href="{{ url('/dashboard/settings') }}" id="top-profile-link">
                  <i class="fa fa-user-circle"></i> Profile Settings
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
              <a class="btn btn-default-hollow" href="{{ url('/give-a-donation') }}">Donate</a>
            </div>
          </li>
          @endunless

          <top-nav managing="{{ (auth()->check() && auth()->user()->managing()->count()) or 0 }}" fundraiser="{{ request()->route()->named('fundraiser') or 0 }}"></top-nav>

        </ul><!-- /.navbar-right -->
      </div>
    </div>
  </div><!-- end container -->
</div><!-- end navbar -->