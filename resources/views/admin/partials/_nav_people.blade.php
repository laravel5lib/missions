<ul class="nav nav-pills nav-stacked">
  <li class="{{ in_array(request()->segment(2), ['users']) ? 'active' : '' }}">
    <a href="{{ url('admin/users') }}">Users</a>
  </li>
  <li class="{{ in_array(request()->segment(2), ['donors']) ? 'active' : '' }}">
    <a href="{{ url('admin/donors') }}">Donors</a>
  </li>
  <li class="{{ in_array(request()->segment(2), ['representatives']) ? 'active' : '' }}">
    <a href="{{ url('admin/representatives') }}">Trip Reps</a>
  </li>
</ul>