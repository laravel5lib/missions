<ul class="nav nav-tabs">
    <li role="presentation" class="{{ set_active('admin/donors') }}">
        <a href="{{ url('admin/donors') }}"><i class="fa fa-users"></i> Donors</a>
    </li>
    <li role="presentation" class="{{ set_active('admin/funds') }}">
        <a href="{{ url('admin/funds') }}"><i class="fa fa-usd"></i> Funds</a>
    </li>
    <li role="presentation" class="{{ set_active('admin/transactions') }}">
        <a href="{{ url('admin/transactions') }}"><i class="fa fa-tasks"></i> Transactions</a>
    </li>
</ul>