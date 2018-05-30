<div class="navbar navbar-default" style="padding-left: 0; border-bottom: 1px solid #eee;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-6">
        <a href="{{ url('/admin') }}" class="navbar-brand visible-xs">
          <img class="db-brand-xs" src="/images/mm-icon-red.png" alt="MM">
        </a>

        <div class="navbar-header hidden-xs">
          <a class="navbar-brand" href="{{ url('/admin') }}">
            <img src="{{ asset('images/mm-logo-horiz.png') }}" alt="Missions.Me">
          </a>
        </div>
      </div>
      <div class="col-md-6 hidden-xs hidden-sm" style="text-align: center">
        <div class="nav navbar-nav navbar-center" style="float: none; display:inline-block; vertical-align: middle;">
          <li class="{{ in_array(request()->segment(2), ['reservations']) ? 'active' : '' }}">
            <a href="{{ url('/admin/reservations') }}">Reservations</a>
          </li>
          <li class="{{ in_array(request()->segment(2), ['campaigns', 'campaign-groups', 'trips']) ? 'active' : '' }}">
            <a href="{{ url('/admin/campaigns') }}">Trips</a>
          </li>
          <li class="{{ in_array(request()->segment(2), ['users', 'donors', 'representatives']) ? 'active' : '' }}">
            <a href="{{ url('/admin/users') }}">People</a>
          </li>
          <li class="{{ in_array(request()->segment(2), ['funds', 'transactions']) ? 'active' : '' }}">
            <a href="{{ url('/admin/transactions') }}">Donations</a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li class="{{ in_array(request()->segment(2), ['organizations']) ? 'active' : '' }}">
                <a href="{{ url('/admin/organizations') }}">Organizations</a>
              </li>
              <li class="{{ in_array(request()->segment(2), ['records']) ? 'active' : '' }}">
                <a href="{{ url('/admin/records/passports') }}">Travel Documents</a>
              </li>
              <li class="{{ in_array(request()->segment(2), ['causes']) ? 'active' : '' }}">
                <a href="{{ url('/admin/causes') }}">Projects</a>
              </li>
              <li role="separator" class="divider"></li>
              <li class="{{ in_array(request()->segment(2), ['reports']) ? 'active' : '' }}">
                <a href="{{ url('/admin/reports') }}">Reports</a>
              </li>
              <li class="{{ in_array(request()->segment(2), ['leads']) ? 'active' : '' }}">
                <a href="{{ url('/admin/leads') }}">Leads</a>
              </li>
            </ul>
          </li>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 col-xs-6">
        <ul class="nav navbar-nav navbar-right dropdown" style="margin-left:75px;">

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