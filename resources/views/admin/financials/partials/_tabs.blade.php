<ul class="nav nav-tabs">
    <li role="presentation" class="{{ set_active('admin/donors') }}">
        <a href="{{ url('admin/donors') }}">Donors</a>
    </li>
    <li role="presentation" class="{{ set_active('admin/funds') }}">
        <a href="{{ url('admin/funds') }}">Funds</a>
    </li>
    <li role="presentation" class="{{ set_active('admin/transactions') }}">
        <a href="{{ url('admin/transactions') }}">Transactions</a>
    </li>
</ul>