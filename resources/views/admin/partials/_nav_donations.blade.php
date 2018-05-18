<ul class="nav nav-tabs nav-secondary" style="margin-bottom: 0px">
  <li class="{{ in_array(request()->segment(2), ['transactions']) ? 'active' : '' }}">
    <a href="{{ url('admin/transactions') }}">Transactions</a>
  </li>
  <li class="{{ in_array(request()->segment(2), ['funds']) ? 'active' : '' }}">
    <a href="{{ url('admin/funds') }}">Funds</a>
  </li>
</ul>