<ul class="nav nav-pills nav-stacked">
  <li class="{{ in_array(request()->segment(2), ['transactions']) ? 'active' : '' }}">
    <a href="{{ url('admin/transactions') }}">Transactions</a>
  </li>
  <li class="{{ in_array(request()->segment(2), ['funds']) ? 'active' : '' }}">
    <a href="{{ url('admin/funds') }}">Funds</a>
  </li>
</ul>